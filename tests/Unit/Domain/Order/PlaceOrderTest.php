<?php

use Domain\Customer\ValueObjects\CustomerId;
use Domain\Order\Entities\Order;
use Domain\Order\Entities\OrderItem;
use Domain\Order\ValueObjects\OrderId;
use Domain\Order\ValueObjects\OrderStatus;
use Domain\Product\ValueObjects\ProductId;

$faker = Faker\Factory::create('pt_BR');

test('it should place an order', function () use ($faker){
    $items = [
        OrderItem::create(
            ProductId::generate(),
            'Product 1',
            100,
            1
        ),
        OrderItem::create(
            ProductId::generate(),
            'Product 2',
            200,
            2
        ),
    ];

    $customerId = CustomerId::generate();
    $order = Order::place($customerId, $items);

    expect($order->id())->toBeInstanceOf(OrderId::class);
    expect($order->customerId())->toBe($customerId);
    expect($order->status()->value())->toBe('pending');
    expect($order->items())->toBe($items);
    expect($order->total())->toBe(500.0);
});
