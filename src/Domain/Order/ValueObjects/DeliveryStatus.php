<?php

namespace Domain\Order\ValueObjects;

final class DeliveryStatus
{
    public const PENDING = 'pending';
    public const SHIPPED = 'shipped';
    public const DELIVERED = 'delivered';

    private function __construct(private readonly string $value)
    {
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function shipped(): self
    {
        return new self(self::SHIPPED);
    }

    public static function delivered(): self
    {
        return new self(self::DELIVERED);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
