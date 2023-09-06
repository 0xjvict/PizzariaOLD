<?php

namespace Domain\Order\Events;

use DateTimeImmutable;
use Domain\_Shared\Abstractions\Money;
use Domain\_Shared\ValueObjects\BrickMoney;
use Domain\Customer\ValueObjects\CustomerId;
use Domain\Order\Entities\OrderItem;
use Domain\Order\ValueObjects\OrderId;
use Domain\Order\ValueObjects\OrderStatus;
use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Exception;
use const DATE_ATOM;

final class OrderPlaced implements SerializablePayload
{
    /**
     * @param OrderId $orderId
     * @param CustomerId $customerId
     * @param OrderStatus $status
     * @param OrderItem[] $items
     * @param Money $total
     * @param DateTimeImmutable $placedAt
     */
    public function __construct(
        private readonly OrderId           $orderId,
        private readonly CustomerId        $customerId,
        private readonly OrderStatus       $status,
        private readonly array             $items,
        private readonly Money             $total,
        private readonly DateTimeImmutable $placedAt,
    )
    {
    }

    /**
     * @return array
     */
    public function toPayload(): array
    {
        return [
            'order_id' => $this->orderId->toString(),
            'customer_id' => $this->customerId->toString(),
            'status' => $this->status->toString(),
            'items' => array_map(fn(OrderItem $item) => $item->toArray(), $this->items),
            'total' => $this->total->amount(),
            'placed_at' => $this->placedAt->format(DATE_ATOM),
        ];
    }

    /**
     * @param array $payload
     * @return static
     * @throws Exception
     */
    public static function fromPayload(array $payload): static
    {
        return new self(
            orderId: OrderId::fromString($payload['order_id']),
            customerId: CustomerId::fromString($payload['customer_id']),
            status: OrderStatus::fromString($payload['status']),
            items: array_map(fn(array $item) => OrderItem::fromArray($item), $payload['items']),
            total: BrickMoney::createFromBrl($payload['total']),
            placedAt: DateTimeImmutable::createFromFormat(DATE_ATOM, $payload['placed_at']),
        );
    }

    /**
     * @return OrderId
     */
    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    /**
     * @return CustomerId
     */
    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    /**
     * @return OrderStatus
     */
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

    /**
     * @return Money
     */
    public function total(): Money
    {
        return $this->total;
    }

    /**
     * @return DateTimeImmutable
     */
    public function placedAt(): DateTimeImmutable
    {
        return $this->placedAt;
    }
}
