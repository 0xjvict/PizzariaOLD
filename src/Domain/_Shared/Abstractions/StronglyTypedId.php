<?php

namespace Domain\_Shared\Abstractions;

use Ramsey\Uuid\Uuid;

abstract class StronglyTypedId
{
    private function __construct(private readonly string $value)
    {
    }

    public static function generate(): static
    {
        return new static(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): static
    {
        return new static($id);
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
