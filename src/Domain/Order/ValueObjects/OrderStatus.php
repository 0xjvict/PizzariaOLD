<?php

namespace Domain\Order\ValueObjects;

final class OrderStatus
{
    private readonly string $value;

    public const PENDING = 'pending';
    public const PAID = 'paid';
    public const CANCELED = 'canceled';

    private function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
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

    public static function fromString(string $status): self
    {
        return new self($status);
    }

    public function isPending(): bool
    {
        return $this->value === self::PENDING;
    }

    public function isPaid(): bool
    {
        return $this->value === self::PAID;
    }

    public function isCanceled(): bool
    {
        return $this->value === self::CANCELED;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        if (!in_array($value, [self::PENDING, self::PAID, self::CANCELED])) {
            throw new \InvalidArgumentException('Invalid order status.');
        }
    }
}
