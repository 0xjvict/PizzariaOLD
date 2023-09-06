<?php

namespace Application\Order\UseCases\RetrieveOrder;

use Domain\Order\Repositories\OrderRepository;

final class RetrieveOrderQueryHandler
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    )
    {
    }

    public function handle(RetrieveOrderQuery $query)
    {
        return $this->orderRepository->retrieve($query->orderId);
    }
}
