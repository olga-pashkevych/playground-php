<?php

enum PaymentType: string
{
    case CreditCard = 'credit_card';
    case Paypal = 'paypal';
}

interface PaymentMethod
{
    public function pay(float $amount): string;
}

class CreditCardPayment implements PaymentMethod
{
    public function pay(float $amount): string
    {
        return "Paid {$amount} using Credit Card";
    }
}

class PaypalPayment implements PaymentMethod
{
    public function pay(float $amount): string
    {
        return "Paid {$amount} using Paypal";
    }
}

class PaymentFactory
{
    public function create(PaymentType $type): PaymentMethod
    {
        return match ($type) {
            PaymentType::CreditCard => new CreditCardPayment(),
            PaymentType::Paypal => new PaypalPayment(),
            default => throw new InvalidArgumentException("Unsupported payment type: {$type->value}"),
        };
    }
}

$factory = new PaymentFactory();
$paymentMethod = $factory->create(PaymentType::CreditCard);

echo $paymentMethod->pay(100.0);


