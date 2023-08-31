<?php

use Domain\_Shared\ValueObjects\BrickMoney;
use Domain\Customer\ValueObjects\CustomerId;
use Domain\Order\Entities\Delivery;
use Domain\Order\Entities\Invoice;
use Domain\Order\Entities\Order;
use Domain\Order\Entities\OrderItem;
use Domain\Order\ValueObjects\OrderId;
use Domain\Order\ValueObjects\ShippingAddress;
use Domain\Product\ValueObjects\ProductId;

$faker = Faker\Factory::create('pt_BR');

it('should place an order', function () use ($faker) {
    $items = [
        OrderItem::create(
            ProductId::generate(),
            'Product 1',
            BrickMoney::createFromBrl('100.0'),
            1
        ),
        OrderItem::create(
            ProductId::generate(),
            'Product 2',
            BrickMoney::createFromBrl('200.0'),
            2
        ),
    ];

    $customerId = CustomerId::generate();
    $order = Order::place($customerId, $items);

    expect($order->id())->toBeInstanceOf(OrderId::class);
    expect($order->customerId())->toBe($customerId);
    expect($order->status()->value())->toBe('pending');
    expect($order->items())->toBe($items);
    expect($order->total()->amount())->toBe(BrickMoney::createFromBrl('500.0')->amount());
});

it('should pay an order', function () use ($faker) {
    $items = [
        OrderItem::create(
            ProductId::generate(),
            'Product 1',
            BrickMoney::createFromBrl('100.0'),
            1
        ),
        OrderItem::create(
            ProductId::generate(),
            'Product 2',
            BrickMoney::createFromBrl('200.0'),
            2
        ),
    ];

    $customerId = CustomerId::generate();
    $order = Order::place($customerId, $items);

    $invoice = Invoice::create(
        orderId: $order->id(),
        urlXml: 'https://www.w3schools.com/xml/note.xml',
        urlDanfe: 'https://www.w3schools.com/xml/note.xml'
    );

    $address = new ShippingAddress(
        street: $faker->streetName(),
        number: $faker->buildingNumber(),
        complement: 'Complement',
        district: $city = $faker->city(),
        city: $city,
        state: 'SP',
        country: $faker->countryCode(),
        zipCode: $faker->postcode(),
    );

    $delivery = Delivery::create(trackingCode: $faker->uuid(), shippingAddress: $address);

    $order->pay(invoice: $invoice, delivery: $delivery);

    expect($order->status()->value())->toBe('paid');
    expect($order->invoice())->toBe($invoice);
    expect($order->delivery())->toBe($delivery);
});

it('should cancel an order after be placed', function () use ($faker) {
    $items = [
        OrderItem::create(
            ProductId::generate(),
            'Product 1',
            BrickMoney::createFromBrl('100.0'),
            1
        ),
        OrderItem::create(
            ProductId::generate(),
            'Product 2',
            BrickMoney::createFromBrl('200.0'),
            2
        ),
    ];

    $customerId = CustomerId::generate();
    $order = Order::place($customerId, $items);

    $order->cancel();

    expect($order->status()->value())->toBe('canceled');
});
