<?php

namespace Domain\Order\Entities;

use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Domain\_Shared\Abstractions\Money;
use Domain\_Shared\ValueObjects\BrickMoney;
use Domain\Customer\ValueObjects\CustomerId;
use Domain\Order\ValueObjects\OrderId;
use Domain\Order\ValueObjects\OrderStatus;

final class Order
{

    private readonly OrderId $id;
    private readonly CustomerId $customerId;
    private OrderStatus $status;
    /**
     * @var OrderItem[]
     */
    private readonly array $items;
    private readonly Money $total;
    private ?Invoice $invoice = null;
    private ?Delivery $delivery = null;

    /**
     * @param OrderId $id
     * @param CustomerId $customerId
     * @param OrderStatus $status
     * @param OrderItem[] $items
     * @param Money $total
     */
    public function __construct(
        OrderId     $id,
        CustomerId  $customerId,
        OrderStatus $status,
        array       $items,
        Money       $total,
    )
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->status = $status;
        $this->items = $items;
        $this->total = $total;
    }

    /**
     * @param CustomerId $customerId
     * @param OrderItem[] $items
     * @return self
     */
    public static function place(CustomerId $customerId, array $items): self
    {
        $total = BrickMoney::createFromBrl('0.0');
        foreach ($items as $item) {
            $total = $total->add($item->subTotal());
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
     * @param Money $total
     * @return self
     */
    public static function restore(
        OrderId     $id,
        CustomerId  $customerId,
        OrderStatus $status,
        array       $items,
        Money       $total,
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

    public function pay(Invoice $invoice, Delivery $delivery): void
    {
        if ($this->status->isPaid()) {
            throw new \DomainException('Order is already paid.');
        } else if ($this->status->isCanceled()) {
            throw new \DomainException('Order is canceled.');
        }

        $this->invoice = $invoice;
        $this->delivery = $delivery;
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

    public function total(): Money
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
}
