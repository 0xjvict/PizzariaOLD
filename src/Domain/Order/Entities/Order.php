<?php

namespace Domain\Order\Entities;

use Domain\Customer\ValueObjects\CustomerId;
use Domain\Order\ValueObjects\OrderId;
use Domain\Order\ValueObjects\OrderStatus;

final class Order
{
    /**
     * @param OrderId $id
     * @param CustomerId $customerId
     * @param OrderStatus $status
     * @param OrderItem[] $items
     * @param float $total
     */
    private function __construct(
        private readonly OrderId    $id,
        private readonly CustomerId $customerId,
        private OrderStatus         $status,
        private readonly array      $items,
        private readonly float      $total,
    )
    {
    }

    /**
     * @param CustomerId $customerId
     * @param OrderItem[] $items
     * @return self
     */
    public static function place(CustomerId $customerId, array $items): self
    {
        $total = 0.0;
        foreach ($items as $item) {
            $total += $item->subTotal();
        }

        return new self(
            id: OrderId::generate(),
            customerId: $customerId,
            status: OrderStatus::pending(),
            items: $items,
            total: $total,
        );
    }

    /**
     * @param OrderId $id
     * @param CustomerId $customerId
     * @param OrderStatus $status
     * @param OrderItem[] $items
     * @param float $total
     * @return self
     */
    public static function restore(
        OrderId     $id,
        CustomerId  $customerId,
        OrderStatus $status,
        array       $items,
        float       $total,
    ): self
    {
        return new self(
            id: $id,
            customerId: $customerId,
            status: $status,
            items: $items,
            total: $total,
        );
    }

    public function paid(): void
    {
        if ($this->status->isPaid()) {
            throw new \DomainException('Order is already paid.');
        } else if ($this->status->isCanceled()) {
            throw new \DomainException('Order is canceled.');
        }

        $this->status = OrderStatus::paid();
    }

    public function cancel(): void
    {
        if ($this->status->isCanceled()) {
            throw new \DomainException('Order is canceled.');
        }

        $this->status = OrderStatus::canceled();
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }

    /**
     * @return OrderItem[]
     */
    public function items(): array
    {
        return $this->items;
    }

    public function total(): float
    {
        return $this->total;
    }
}
