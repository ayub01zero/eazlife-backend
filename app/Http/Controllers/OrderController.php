<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserNotification;
use App\Notifications\OrderCanceled;
use App\Notifications\OrderSent;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::with('company')->findOrFail($id);

        $this->authorize('manageCompany', $order->company);

        $order->products = OrderProduct::where('order_id', $order->id)
            ->with('product')
            ->get();

        return view('orders.show', [
            'order' => $order
        ]);
    }

    public function send(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|integer'
        ]);

        $order = Order::with('company')->find($validatedData['order_id']);

        $this->authorize('manageCompany', $order->company);

        if ($order) {
            $order->user->notify(new OrderSent());

            $order->sent_at = Carbon::now();

            $address = $order->address;

            $client = new Client();
            $apiKey = env('GOOGLE_MAPS_API_KEY');
            $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}");
            $data = json_decode($response->getBody(), true);

            if (isset($data['results'][0])) {
                $lat = $data['results'][0]['geometry']['location']['lat'];
                $lng = $data['results'][0]['geometry']['location']['lng'];
            }

            $company = Company::select(
                '*',
                DB::raw("ROUND(ST_Distance_Sphere(location, ST_GeomFromText('POINT({$lng} {$lat})')) / 1000, 1) as distance"),
            )
                ->where('id', $order->company_id)
                ->first();

            $averageScooterSpeed = 30; // km/h (considering traffic conditions)

            $distance_km = $company->distance;
            $delivery_time = ceil(($distance_km / $averageScooterSpeed) * 60);

            $order->delivery_at = Carbon::now()->addMinutes($delivery_time);
            $order->save();

            UserNotification::create([
                'user_id' => $order->user_id,
                'title' => 'Your order from ' . $company->name . ' has been sent.',
                'body' => $delivery_time . ' minutes',
                'notifiable_id' => $order->id,
                'notifiable_type' => Order::class,
            ]);
        }

        return redirect()->back();
    }

    public function pay(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|integer'
        ]);

        $order = Order::with('company')->find($validatedData['order_id']);

        $this->authorize('manageCompany', $order->company);

        if ($order) {
            $order->paid = true;
            $order->save();
        }

        return redirect()->back();
    }

    public function cancel(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|integer'
        ]);

        $order = Order::with('company')->find($validatedData['order_id']);

        $this->authorize('manageCompany', $order->company);

        if ($order) {
            $order->user->notify(new OrderCanceled());
            $order->canceled_at = Carbon::now();
            $order->save();
        }

        $company = Company::find($order->company_id);

        UserNotification::create([
            'user_id' => $order->user_id,
            'title' => 'Your order from ' . $company->name . ' has been canceled.',
            'body' => 'Update',
            'notifiable_id' => $order->id,
            'notifiable_type' => Order::class,
        ]);

        return redirect()->back();
    }
}
