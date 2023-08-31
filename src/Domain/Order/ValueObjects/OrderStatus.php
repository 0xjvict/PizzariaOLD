<?php

namespace Domain\Order\ValueObjects;

final class OrderStatus
{
    public const PENDING = 'pending';
    public const PAID = 'paid';
    public const CANCELED = 'canceled';

    private function __construct(private readonly string $status)
    {
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function paid(): self
    {
        return new self(self::PAID);
    }

    public static function canceled(): self
    {
        return new self(self::CANCELED);
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::PAID;
    }

    public function isCanceled(): bool
    {
        return $this->status === self::CANCELED;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function __toString(): string
    {
        return $this->status;
    }
}
