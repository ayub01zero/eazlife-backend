<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserNotification;
use App\Notifications\OrderCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, ['user_id' => 'exists:users,id|required|numeric']);

        $orders = Order::where('user_id', $request->input('user_id'))
            ->with('company')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function getActiveOrders(Request $request)
    {
        $this->validate($request, ['user_id' => 'exists:users,id|required|numeric']);

        $orderedOrders = Order::with('company')
            ->where('user_id', Auth::id())
            ->wherePaid(true)
            ->whereNull('delivered_at')
            ->whereNull('sent_at')
            ->get();

        $acceptedOrders = Order::with('company')
            ->where('user_id', Auth::id())
            ->wherePaid(true)
            ->whereNull('delivered_at')
            ->whereNotNull('sent_at')
            ->get();

        return response()->json([
            'ordered' => $orderedOrders,
            'accepted' => $acceptedOrders,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'company_id' => 'required|exists:companies,id|numeric',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id|',
            'price' => 'required|numeric',
            'name' => 'required|string|min:2|max:255',
            'address' => 'sometimes|string|min:2|max:255',
            'email' => 'sometimes|email',
            'phone' => 'required|string|min:2|max:255',
            'delivery_at' => 'sometimes|nullable|date',
            'pickup_at' => 'sometimes|nullable|date',
        ]);

        $deliveryCost = Company::find($request->input('company_id'))->delivery_cost;

        // Double check if the price is correct
        $price = 0;
        if ($request->input('delivery_at')) {
            $price = $deliveryCost;
        }

        foreach ($request->input('products') as $product) {
            $extrasPrice = 0;
            foreach ($product['extras'] as $extra) {
                $extrasPrice += $extra['price'] * $extra['quantity'];
            }
            $price += ($product['price'] + $extrasPrice) * $product['quantity'];
        }

        if ($price != $request->input('price')) {
            return response()->json([
                'errors' => [
                    'price' => 'The price is incorrect.',
                ],
            ], 400);
        }

        if ($request->input('delivery_at')) {
            $deliveryAt = Carbon::parse($request->input('delivery_at'));
            $deliveryAtFormatted = $deliveryAt->format('Y-m-d H:i:s');
        } else if ($request->input('pickup_at')) {
            $pickupAt = Carbon::parse($request->input('pickup_at'));
            $pickupAtFormatted = $pickupAt->format('Y-m-d H:i:s');
        }

        $order = Order::create([
            'user_id' => Auth::user()->id,
            'company_id' => $request->input('company_id'),
            'price' => $request->input('price'),
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'business' => $request->input('company'),
            'delivery_at' => $request->input('delivery_at') ? $deliveryAtFormatted : null,
            'pickup_at' => $request->input('pickup_at') ? $pickupAtFormatted : null,
            'paid' => false,
        ]);

        foreach ($request->input('products') as $product) {
            // Create the pivot model directly
            $orderProductPivot = new OrderProduct();
            $orderProductPivot->order_id = $order->id;
            $orderProductPivot->product_id = $product['id'];
            $orderProductPivot->quantity = $product['quantity'];
            $orderProductPivot->price = $product['price'];
            $orderProductPivot->save();

            foreach ($product['extras'] as $extra) {
                // Attach the extra to the pivot table 'extra_order_product'
                DB::table('extra_order_product')->insert([
                    'order_product_id' => $orderProductPivot->id,
                    'extra_id' => $extra['id'],
                    'quantity' => $extra['quantity'],
                    'price' => $extra['price'],
                ]);
            }
        }

        if ($order) {
            $order->user->notify(new OrderCreated());
        }

        $company = Company::find($order->company_id);

        UserNotification::create([
            'user_id' => $order->user_id,
            'title' => $company->name . ' has received your order.',
            'body' => 'Update',
            'notifiable_id' => $order->id,
            'notifiable_type' => Order::class,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Order created.',
            'order' => $order->with('company'),
        ]);
    }
}
