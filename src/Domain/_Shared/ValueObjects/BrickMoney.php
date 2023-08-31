<?php

namespace Domain\_Shared\ValueObjects;

use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Domain\_Shared\Abstractions\Money;

final class BrickMoney extends Money
{
    /**
     * @param string $amount
     * @return BrickMoney
     */
    public static function createFromBrl(string $amount): BrickMoney
    {
        try {
            $amount = \Brick\Money\Money::of($amount, 'BRL')->getAmount();
        } catch (NumberFormatException|RoundingNecessaryException|UnknownCurrencyException $e) {
            throw new \DomainException('Error creating money from BRL.', 0, $e);
        }

        return new self($amount, 'BRL');
    }

    /**
     * @param string $amount
     * @param string $currency
     * @return BrickMoney
     */
    public static function create(string $amount, string $currency): BrickMoney
    {
        try {
            $amount = \Brick\Money\Money::of($amount, $currency)->getAmount();
        } catch (NumberFormatException|RoundingNecessaryException|UnknownCurrencyException $e) {
            throw new \DomainException('Error creating money.', 0, $e);
        }

        return new self($amount, $currency);
    }

    /**
     * @param Money $other
     * @return bool
     */
    public function equals(Money $other): bool
    {
        try {
            $money = \Brick\Money\Money::of($this->amount(), $this->currency());
            $other = \Brick\Money\Money::of($other->amount(), $other->currency());

            return $money->isEqualTo($other);
        } catch (NumberFormatException|RoundingNecessaryException|UnknownCurrencyException|MathException|MoneyMismatchException $e) {
            throw new \DomainException('Error comparing money.', 0, $e);
        }
    }

    /**
     * @param Money $other
     * @return Money
     */
    public function add(Money $other): Money
    {
        try {
            $money = \Brick\Money\Money::of($this->amount(), $this->currency());

            return new self($money->plus($other->amount())->getAmount(), $this->currency());
        } catch (NumberFormatException|RoundingNecessaryException|UnknownCurrencyException|MathException|MoneyMismatchException $e) {
            throw new \DomainException('Error adding money.', 0, $e);
        }
    }

    /**
     * @param Money $other
     * @return Money
     */
    public function subtract(Money $other): Money
    {
        try {
            $money = \Brick\Money\Money::of($this->amount(), $this->currency());
            return new self($money->minus($other->amount()), $this->currency());
        } catch (NumberFormatException|RoundingNecessaryException|UnknownCurrencyException|MathException|MoneyMismatchException $e) {
            throw new \DomainException('Error subtracting money.', 0, $e);
        }
    }

    /**
     * @param int $multiplier
     * @return Money
     */
    public function multiply(int $multiplier): Money
    {
        try {
            $money = \Brick\Money\Money::of($this->amount(), $this->currency());

            return new self($money->multipliedBy($multiplier)->getAmount(), $this->currency());
        } catch (NumberFormatException|RoundingNecessaryException|UnknownCurrencyException|MathException|MoneyMismatchException $e) {
            throw new \DomainException('Error multiplying money', 0, $e);
        }
    }
}
