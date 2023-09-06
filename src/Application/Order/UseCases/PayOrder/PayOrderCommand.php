<?php

namespace Application\Order\UseCases\PayOrder;

use DateTimeImmutable;
use Domain\Order\Entities\Invoice;
use Domain\Order\ValueObjects\OrderId;

final class PayOrderCommand
{
    public function __construct(
        public readonly OrderId           $orderId,
        public readonly Invoice           $invoice,
        public readonly DateTimeImmutable $paidAt,
    )
    {
    }
}
