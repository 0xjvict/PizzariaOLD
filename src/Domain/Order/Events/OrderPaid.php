<?php

namespace Domain\Order\Events;

use DateTimeImmutable;
use Domain\Order\Entities\Invoice;
use Domain\Order\ValueObjects\OrderId;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class OrderPaid implements SerializablePayload
{
    public function __construct(
        private readonly OrderId           $orderId,
        private readonly Invoice           $invoice,
        private readonly DateTimeImmutable $paidAt,
    )
    {
    }

    public function toPayload(): array
    {
        return [
            'order_id' => $this->orderId->toString(),
            'invoice' => $this->invoice->toArray(),
            'paid_at' => $this->paidAt->format(DATE_ATOM),
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            orderId: OrderId::fromString($payload['order_id']),
            invoice: Invoice::fromArray($payload['invoice']),
            paidAt: DateTimeImmutable::createFromFormat(DATE_ATOM, $payload['paid_at']),
        );
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function invoice(): Invoice
    {
        return $this->invoice;
    }

    public function paidAt(): DateTimeImmutable
    {
        return $this->paidAt;
    }
}
