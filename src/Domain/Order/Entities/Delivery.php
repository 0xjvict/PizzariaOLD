<?php

namespace Domain\Order\Entities;

use Domain\Order\ValueObjects\DeliveryId;
use Domain\Order\ValueObjects\DeliveryStatus;
use Domain\Order\ValueObjects\ShippingAddress;

final class Delivery
{
    public function __construct(
        private readonly DeliveryId      $id,
        private readonly DeliveryStatus  $status,
        private readonly string          $trackingCode,
        private readonly ShippingAddress $shippingAddress
    )
    {
    }

    public static function create(string $trackingCode, ShippingAddress $shippingAddress): self
    {
        return new self(
            id: DeliveryId::generate(),
            status: DeliveryStatus::pending(),
            trackingCode: $trackingCode,
            shippingAddress: $shippingAddress
        );
    }

    public static function restore(
        DeliveryId      $id,
        DeliveryStatus  $status,
        string          $trackingCode,
        ShippingAddress $shippingAddress
    ): self
    {
        return new self(
            id: $id,
            status: $status,
            trackingCode: $trackingCode,
            shippingAddress: $shippingAddress
        );
    }

    public function id(): DeliveryId
    {
        return $this->id;
    }

    public function status(): DeliveryStatus
    {
        return $this->status;
    }

    public function trackingCode(): string
    {
        return $this->trackingCode;
    }

    public function shippingAddress(): ShippingAddress
    {
        return $this->shippingAddress;
    }
}
