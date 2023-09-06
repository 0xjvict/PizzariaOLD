<?php

namespace Tests\Unit\Domain\Order;

use Application\Order\UseCases\PayOrder\PayOrderCommand;
use Application\Order\UseCases\PlaceOrder\PlaceOrderCommand;
use DateTimeImmutable;
use Domain\_Shared\ValueObjects\BrickMoney;
use Domain\Customer\ValueObjects\CustomerId;
use Domain\Order\Entities\Invoice;
use Domain\Order\Entities\Order;
use Domain\Order\Entities\OrderItem;
use Domain\Order\Events\OrderPaid;
use Domain\Order\Events\OrderPlaced;
use Domain\Order\ValueObjects\OrderStatus;
use Domain\Product\ValueObjects\ProductId;

final class PlaceOrderTest extends PlaceOrderTestCase
{
    public function test_place_an_order(): void
    {
        $orderId = $this->aggregateRootId();
        $customerId = CustomerId::generate();

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

        $now = (new DateTimeImmutable())->format(DATE_ATOM);
        $placedAt = DateTimeImmutable::createFromFormat(DATE_ATOM, $now);

        $this->when(
            new PlaceOrderCommand(
                orderId: $orderId,
                customerId: $customerId,
                items: $items,
                placedAt: $placedAt
            )
        )->then(
            new OrderPlaced(
                orderId: $orderId,
                customerId: $customerId,
                status: OrderStatus::pending(),
                items: $items,
                total: BrickMoney::createFromBrl('500.0'),
                placedAt: $placedAt
            )
        );
    }

    public function test_pay_an_order(): void
    {
        $order = $this->placeAnOrder();
        $invoice = Invoice::create(
            orderId: $order->orderId(),
            urlXml: 'https://www.w3schools.com/xml/note.xml',
            urlDanfe: 'https://www.w3schools.com/xml/note.xml'
        );

        $this->when(
            new PayOrderCommand(
                orderId: $order->orderId(),
                invoice: $invoice,
                paidAt: $order->placedAt()
            )
        )->then(
            new OrderPaid(
                orderId: $order->orderId(),
                invoice: $invoice,
                paidAt: $order->placedAt()
            )
        );
    }

    private function placeAnOrder(): Order
    {
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

        $now = (new DateTimeImmutable())->format(DATE_ATOM);
        $placedAt = DateTimeImmutable::createFromFormat(DATE_ATOM, $now);

        $command = new PlaceOrderCommand(
            orderId: $this->aggregateRootId(),
            customerId: CustomerId::generate(),
            items: $items,
            placedAt: $placedAt
        );
        $order = $this->repository->retrieve($command->orderId);
        $order = $order->place($command);
        $this->repository->persist($order);

        return $this->repository->retrieve($command->orderId);
    }
}
