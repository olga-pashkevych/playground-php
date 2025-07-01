<?php

//echo 'hello world';

global $orders;

class Order
{
    public string $id;
    public int $userId;
    public float $totalAmount;
}

class OrderProcessor
{
    public function addOrder(Order $order): void
    {
        $GLOBALS['orders'][] = $order;
    }

    public function getOrdersByUserId(int $userId): array
    {
        $neededOrders = [];

        array_walk($GLOBALS['orders'], function ($order) use ($userId, &$neededOrders) {
            if ($order->userId === $userId) {
                return $neededOrders[] = $order;
            }
        });

        return $neededOrders;
    }

    public function getTotalRevenue(): float
    {
        $seekingAmount = 0.0;

        array_walk($GLOBALS['orders'], function ($order) use (&$seekingAmount) {
            return $seekingAmount += $order->totalAmount;
        });

        return $seekingAmount;
    }
}

for ($i = 0; $i <= 3; $i++) {
    $order = new Order();
    $order->id = "order-$i";
    $order->userId = $i;
    $order->totalAmount = 100.0 + ($i * 10.0);

    $processor = new OrderProcessor();
    $processor->addOrder($order);
}

$processor = new OrderProcessor();
$processor->addOrder($order);

echo "Total revenue: " . $processor->getTotalRevenue() . "\n";

$neededOrders = $processor->getOrdersByUserId(1);

foreach ($neededOrders as $order){
    echo "Order ID: {$order->id}, User ID: {$order->userId}, Total Amount: {$order->totalAmount}\n";
}
