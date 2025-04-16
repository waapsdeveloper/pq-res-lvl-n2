{{-- filepath: c:\xampp\htdocs\pq-res-lvl-n2\resources\views\mail\order_details.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Your Order Details</title>
</head>
<body>
    <h1>Thank you for your order, {{ $order->customer->name }}!</h1>
    <p>Here are the details of your order:</p>

    <h3>Order Number: {{ $order->order_number }}</h3>
    <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y, g:i a') }}</p>
    <p><strong>Total Price:</strong> ${{ number_format($order->final_total, 2) }}</p>

    <h3>Order Items:</h3>
    <ul>
        @foreach ($order->orderProducts as $product)
            <li>
                {{ $product->product->name }} - Quantity: {{ $product->quantity }} - Price: ${{ number_format($product->price, 2) }}
            </li>
        @endforeach
    </ul>

    <p>If you have any questions, feel free to contact us.</p>
    <br>
    <p>Best regards,</p>
    <p>The Axe Restaurant Team</p>
</body>
</html>
