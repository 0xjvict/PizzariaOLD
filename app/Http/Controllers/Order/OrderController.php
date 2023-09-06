<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Application\Order\UseCases\PayOrder\PayOrderCommand;
use Application\Order\UseCases\PayOrder\PayOrderCommandHandler;
use Application\Order\UseCases\PlaceOrder\PlaceOrderCommand;
use Application\Order\UseCases\PlaceOrder\PlaceOrderCommandHandler;
use Application\Order\UseCases\RetrieveOrder\RetrieveOrderQuery;
use Application\Order\UseCases\RetrieveOrder\RetrieveOrderQueryHandler;
use DateTimeImmutable;
use Domain\_Shared\ValueObjects\BrickMoney;
use Domain\Customer\ValueObjects\CustomerId;
use Domain\Order\Entities\Invoice;
use Domain\Order\Entities\OrderItem;
use Domain\Order\Repositories\OrderRepository;
use Domain\Order\ValueObjects\OrderId;
use Domain\Product\ValueObjects\ProductId;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepository $orderRepository
    )
    {
    }

    public function placeOrder(): void
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
            orderId: OrderId::generate(),
            customerId: CustomerId::generate(),
            items: $items,
            placedAt: $placedAt
        );
        (new PlaceOrderCommandHandler($this->orderRepository))->handle($command);
    }

    public function payOrder(string $orderId): void
    {
        $invoice = Invoice::create(
            orderId: OrderId::fromString($orderId),
            urlXml: 'https://www.w3schools.com/xml/note.xml',
            urlDanfe: 'https://www.w3schools.com/xml/note.xml'
        );

        $now = (new DateTimeImmutable())->format(DATE_ATOM);
        $paidAt = DateTimeImmutable::createFromFormat(DATE_ATOM, $now);

        $command = new PayOrderCommand(
            orderId: OrderId::fromString($orderId),
            invoice: $invoice,
            paidAt: $paidAt
        );
        (new PayOrderCommandHandler($this->orderRepository))->handle($command);
    }

    public function retrieveOrder(string $orderId)
    {
        $query = new RetrieveOrderQuery(
            orderId: OrderId::fromString($orderId)
        );
        $order = (new RetrieveOrderQueryHandler($this->orderRepository))->handle($query);

        dd($order);
    }
}
