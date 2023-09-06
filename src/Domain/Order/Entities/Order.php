<?php

namespace Domain\Order\Entities;

use DomainException;
use DateTimeImmutable;
use Application\Order\UseCases\PayOrder\PayOrderCommand;
use Application\Order\UseCases\PlaceOrder\PlaceOrderCommand;
use Domain\_Shared\Abstractions\Money;
use Domain\_Shared\ValueObjects\BrickMoney;
use Domain\Customer\ValueObjects\CustomerId;
use Domain\Order\Events\OrderPaid;
use Domain\Order\Events\OrderPlaced;
use Domain\Order\ValueObjects\OrderId;
use Domain\Order\ValueObjects\OrderStatus;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

final class Order implements AggregateRoot
{
    use AggregateRootBehaviour;

    private ?CustomerId $customerId = null;
    private ?OrderStatus $status = null;
    /* @var OrderItem[] */
    private ?array $items = null;
    private ?Money $total = null;
    private ?Invoice $invoice = null;
    private ?Delivery $delivery = null;
    private ?DateTimeImmutable $placedAt = null;
    private ?DateTimeImmutable $paidAt = null;

    public static function place(PlaceOrderCommand $command): self
    {
        $order = new self($command->orderId);

        $total = BrickMoney::createFromBrl('0.0');
        foreach ($command->items as $item) {
            $total = $total->add($item->subTotal());
        }

        $orderPlaced = new OrderPlaced(
            orderId: $command->orderId,
            customerId: $command->customerId,
            status: OrderStatus::pending(),
            items: $command->items,
            total: $total,
            placedAt: $command->placedAt
        );
        $order->recordThat($orderPlaced);

        return $order;
    }

    public function pay(PayOrderCommand $command): self
    {
        // TODO: talvez seja melhor disparar um evento
        if ($this->status->isPaid()) {
            throw new DomainException('Order is already paid!');
        } elseif ($this->status->isCanceled()) {
            throw new DomainException('Order is canceled!');
        }

        $order = new self($command->orderId);

        $orderPaid = new OrderPaid(
            orderId: $command->orderId,
            invoice: $command->invoice,
            paidAt: $command->paidAt
        );
        $order->recordThat($orderPaid);

        return $order;
    }

    public function applyOrderPlaced(OrderPlaced $event): void
    {
        $this->customerId = $event->customerId();
        $this->status = $event->status();
        $this->items = $event->items();
        $this->total = $event->total();
        $this->placedAt = $event->placedAt();
    }

    public function applyOrderPaid(OrderPaid $event): void
    {
        $this->status = OrderStatus::paid();
        $this->invoice = $event->invoice();
        $this->paidAt = $event->paidAt();
    }

    public function orderId(): ?OrderId
    {
        return $this->aggregateRootId;
    }

    public function customerId(): ?CustomerId
    {
        return $this->customerId;
    }

    public function status(): ?OrderStatus
    {
        return $this->status;
    }

    public function items(): ?array
    {
        return $this->items;
    }

    public function total(): ?Money
    {
        return $this->total;
    }

    public function invoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function delivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function placedAt(): ?DateTimeImmutable
    {
        return $this->placedAt;
    }

    public function paidAt(): ?DateTimeImmutable
    {
        return $this->paidAt;
    }
}
