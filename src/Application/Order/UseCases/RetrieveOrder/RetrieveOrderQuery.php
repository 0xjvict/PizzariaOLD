<?php

namespace Application\Order\UseCases\RetrieveOrder;

use Domain\Order\ValueObjects\OrderId;

final class RetrieveOrderQuery
{
    public function __construct(
        public readonly OrderId $orderId,
    )
    {
    }
}
