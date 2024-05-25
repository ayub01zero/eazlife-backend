{{-- order-details.blade.php --}}
<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-gray-700 border-b border-gray-200 text-white">
                <h2 class="font-semibold text-xl leading-tight mb-4">
                    Order Details #{{ $order->id }}
                </h2>
                <div class="mb-4">
                    <p><strong>Customer Name:</strong> {{ $order->name }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                    <p><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p><strong>Address:</strong> {{ $order->address }}</p>
                    <p><strong>Delivery At:</strong> {{ $order->delivery_at }}</p>
                    <p><strong>Pickup At:</strong> {{ $order->pickup_at }}</p>
                    <p><strong>Price:</strong> ${{ $order->price }}</p>
                </div>
                <div class="mt-4">
                    @if ($order->pickup_at && !$order->cancelled_at)
                        <form action="/orders/cancel" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md"
                                type="submit">Cancel</button>
                        </form>
                    @elseif ($order->cancelled_at)
                        <div class="bg-red-700 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md">
                            <span>Canceled</span>
                        </div>
                    @else
                        @if ($order->sent_at && !$order->paid)
                            <form action="/orders/pay" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <button
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md"
                                    type="submit">Pay</button>
                            </form>
                        @elseif(!$order->sent_at)
                            <form action="/orders/send" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <button
                                    class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-md"
                                    type="submit">Send</button>
                            </form>
                            <form action="/orders/cancel" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md"
                                    type="submit">Cancel</button>
                            </form>
                        @elseif($order->paid)
                            <div class="bg-green-700 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
                                <span>Paid</span>
                            </div>
                        @endif
                    @endif
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Products</h3>
                    @forelse ($order->products as $product)
                        <div class="mt-2">
                            <p><strong>Product:</strong> {{ $product->product->name }}</p>
                            <p><strong>Price:</strong> ${{ $product->product->price }}</p>
                            <p><strong>Quantity:</strong> x{{ $product->product->quantity }}</p>
                            <p><strong>Extras:</strong></p>
                            <ul>
                                @forelse ($product->orderedExtras as $extra)
                                    <li>{{ $extra->name }} - ${{ $extra->price }} x {{ $extra->quantity }}</li>
                                @empty
                                    <li>No extras for this product.</li>
                                @endforelse
                            </ul>
                        </div>
                    @empty
                        <p>No products in this order.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
