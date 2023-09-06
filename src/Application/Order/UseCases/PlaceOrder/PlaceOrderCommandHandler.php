<?php

namespace Application\Order\UseCases\PlaceOrder;

use Domain\Order\Repositories\OrderRepository;

final class PlaceOrderCommandHandler
{
    public function __construct(
        private readonly OrderRepository $orderRepository
    )
    {
    }

    public function handle(PlaceOrderCommand $command): void
    {
        $order = $this->orderRepository->retrieve($command->orderId);
        $order = $order->place($command);
        $this->orderRepository->persist($order);
    }
}
