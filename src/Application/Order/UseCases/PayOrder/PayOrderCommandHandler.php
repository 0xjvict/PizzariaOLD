<?php

namespace Application\Order\UseCases\PayOrder;

use Domain\Order\Repositories\OrderRepository;

final class PayOrderCommandHandler
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    )
    {
    }

    public function handle(PayOrderCommand $command): void
    {
        $order = $this->orderRepository->retrieve($command->orderId);
        $order = $order->pay($command);
        $this->orderRepository->persist($order);
    }
}
