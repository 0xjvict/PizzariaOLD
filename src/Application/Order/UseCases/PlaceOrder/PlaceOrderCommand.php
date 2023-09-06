<?php

namespace Application\Order\UseCases\PlaceOrder;

use DateTimeImmutable;
use Domain\Customer\ValueObjects\CustomerId;
use Domain\Order\Entities\Delivery;
use Domain\Order\Entities\OrderItem;
use Domain\Order\ValueObjects\OrderId;

final class PlaceOrderCommand
{
    /**
     * @param OrderId $orderId
     * @param CustomerId $customerId
     * @param OrderItem[] $items
     * @param DateTimeImmutable $placedAt
     */
    public function __construct(
        public readonly OrderId           $orderId,
        public readonly CustomerId        $customerId,
        public readonly array             $items,
        public readonly DateTimeImmutable $placedAt,
    )
    {
    }
}
