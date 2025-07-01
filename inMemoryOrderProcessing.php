<?php

//echo 'hello world';

class Order
{
    public function __construct(private string $id, private int $userId, private float $totalAmount) {}

    public function getId(): string
    {
        return $this->id;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }
}

class OrderProcessor
{
    private $orders; // This will be used to store orders in memory

    public function addOrder(Order $order): void
    {
        $this->orders[] = $order;
    }

    public function getOrdersByUserId(int $userId): array
    {
        $neededOrders = [];

        array_filter($this->orders, function ($order) use ($userId, &$neededOrders) {
            if ($order->getUserId() === $userId) {
                return $neededOrders[] = $order;
            }
        });

        return $neededOrders;
    }

    public function getTotalRevenue(): float
    {
        $seekingAmount = 0.0;

        array_filter($this->orders, function ($order) use (&$seekingAmount) {
            return $seekingAmount += $order->getTotalAmount();
        });

        return $seekingAmount;
    }
}

$processor = new OrderProcessor();

for ($i = 0; $i <= 3; $i++) {
    $order = new Order("order-$i", $i, 100.0 + ($i * 10.0));
    $processor->addOrder($order);
}

echo "Total revenue: " . $processor->getTotalRevenue() . "\n";

$neededOrders = $processor->getOrdersByUserId(1);

foreach ($neededOrders as $order) {
    echo "Order ID: {$order->getId()}, User ID: {$order->getUserId()}, Total Amount: {$order->getTotalAmount()}\n";
}
