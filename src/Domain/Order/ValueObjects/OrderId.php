<?php

namespace Domain\Order\ValueObjects;

use Ramsey\Uuid\Uuid;

final class OrderId
{
    private function __construct(private readonly string $id)
    {
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function value(): string
    {
        return $this->id;
    }

    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
