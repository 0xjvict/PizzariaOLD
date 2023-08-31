<?php

namespace Domain\_Shared\Abstractions;

abstract class Address
{
    public function __construct(
        private readonly string $street,
        private readonly string $number,
        private readonly string $complement,
        private readonly string $district,
        private readonly string $city,
        private readonly string $state,
        private readonly string $country,
        private readonly string $zipCode,
    )
    {
    }

    public function street(): string
    {
        return $this->street;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function complement(): string
    {
        return $this->complement;
    }

    public function district(): string
    {
        return $this->district;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function zipCode(): string
    {
        return $this->zipCode;
    }
}
