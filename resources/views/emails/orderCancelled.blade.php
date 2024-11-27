<!DOCTYPE html>
<html>

<head>
    <title>Order Cancelled Notification</title>
</head>

<body>
    <h1>Dear App Owner,</h1>
    <p>An order with transaction key: {{ $order->transaction_key }} has been cancelled.</p>
    <p><strong>Order Details:</strong></p>
    <ul>
        <li>Transaction Key: {{ $order->transaction_key }}</li>
        <li>Status: Cancelled</li>
        <li>Order Date: {{ $order->created_at }}</li>
    </ul>
    <p>If you have any questions or need to take further action, please check the order details in the admin panel.</p>
    <p>Thank you for your attention.</p>
</body>

</html>
