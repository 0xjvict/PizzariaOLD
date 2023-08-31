<?php

namespace Domain\_Shared\Abstractions;

abstract class Money
{
    protected function __construct(
        private readonly string $amount,
        private readonly string $currency
    )
    {
    }

    abstract public static function createFromBrl(string $amount): Money;

    abstract public static function create(string $amount, string $currency): Money;

    abstract public function equals(Money $other): bool;

    abstract public function add(Money $other): Money;

    abstract public function subtract(Money $other): Money;

    abstract public function multiply(int $multiplier): Money;

    public function amount(): string
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}
