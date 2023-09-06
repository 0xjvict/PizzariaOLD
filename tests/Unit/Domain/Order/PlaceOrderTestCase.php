<?php

namespace Tests\Unit\Domain\Order;

use Application\Order\UseCases\PayOrder\PayOrderCommand;
use Application\Order\UseCases\PlaceOrder\PlaceOrderCommand;
use Domain\Order\Entities\Order;
use Domain\Order\ValueObjects\OrderId;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;

abstract class PlaceOrderTestCase extends AggregateRootTestCase
{
    protected function newAggregateRootId(): AggregateRootId
    {
        return OrderId::generate();
    }

    protected function aggregateRootClassName(): string
    {
        return Order::class;
    }

    protected function handle(object $command): void
    {
        if ($command instanceof PlaceOrderCommand) {
            $aggregate = $this->repository->retrieve($command->orderId);
            $aggregate = $aggregate->place($command);
            $this->repository->persist($aggregate);
        } else if ($command instanceof PayOrderCommand) {
            $aggregate = $this->repository->retrieve($command->orderId);
            $aggregate = $aggregate->pay($command);
            $this->repository->persist($aggregate);
        }
    }
}
