<?php

namespace Domain\Order\Entities;

use Domain\_Shared\Abstractions\Money;
use Domain\_Shared\ValueObjects\BrickMoney;
use Domain\Order\ValueObjects\OrderItemId;
use Domain\Product\ValueObjects\ProductId;

final class OrderItem
{
    private function __construct(
        private readonly OrderItemId $id,
        private readonly ProductId   $productId,
        private readonly string      $name,
        private readonly Money       $price,
        private readonly int         $quantity,
        private readonly Money       $subTotal,
    )
    {
    }

    public static function create(
        ProductId $productId,
        string    $name,
        Money     $price,
        int       $quantity
    ): self
    {
        return new self(
            id: OrderItemId::generate(),
            productId: $productId,
            name: $name,
            price: $price,
            quantity: $quantity,
            subTotal: $price->multiply($quantity),
        );
    }

    public static function restore(
        OrderItemId $id,
        ProductId   $productId,
        string      $name,
        Money       $price,
        int         $quantity
    ): self
    {
        return new self(
            id: $id,
            productId: $productId,
            name: $name,
            price: $price,
            quantity: $quantity,
            subTotal: $price->multiply($quantity),
        );
    }

    public static function fromArray(array $item): self
    {
        return new self(
            OrderItemId::fromString($item['id']),
            ProductId::fromString($item['product_id']),
            $item['name'],
            BrickMoney::createFromBrl($item['price']),
            $item['quantity'],
            BrickMoney::createFromBrl($item['sub_total']),
        );
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

    public function price(): Money
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function subTotal(): Money
    {
        return $this->subTotal;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(),
            'product_id' => $this->productId->toString(),
            'name' => $this->name,
            'price' => $this->price->amount(),
            'quantity' => $this->quantity,
            'sub_total' => $this->subTotal->amount(),
        ];
    }
}
