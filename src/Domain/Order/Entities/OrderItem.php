<?php

namespace Domain\Order\Entities;

use Domain\Order\ValueObjects\OrderItemId;
use Domain\Product\ValueObjects\ProductId;

final class OrderItem
{
    private function __construct(
        private readonly OrderItemId $id,
        private readonly ProductId   $productId,
        private readonly string      $name,
        private readonly float       $price,
        private readonly int         $quantity,
        private readonly float       $subTotal,
    )
    {
    }

    public static function create(ProductId $productId, string $name, float $price, int $quantity): self
    {
        return new self(OrderItemId::generate(), $productId, $name, $price, $quantity, $price * $quantity);
    }

    public static function restore(OrderItemId $id, ProductId $productId, string $name, float $price, int $quantity): self
    {
        return new self($id, $productId, $name, $price, $quantity, $price * $quantity);
    }

    public function id(): OrderItemId
    {
        return $this->id;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function subTotal(): float
    {
        return $this->subTotal;
    }
}
