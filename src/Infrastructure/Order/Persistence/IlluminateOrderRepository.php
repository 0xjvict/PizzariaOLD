<?php

namespace Infrastructure\Order\Persistence;

use Domain\Order\Entities\Order;
use Domain\Order\Repositories\OrderRepository;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\IdEncoding\StringIdEncoder;
use EventSauce\MessageOutbox\IlluminateOutbox\IlluminateOutboxRepository;
use EventSauce\MessageOutbox\IlluminateOutbox\IlluminateTransactionalMessageRepository;
use EventSauce\MessageOutbox\OutboxRepository;
use EventSauce\MessageRepository\IlluminateMessageRepository\IlluminateMessageRepository;
use EventSauce\MessageRepository\TableSchema\DefaultTableSchema;
use Illuminate\Support\Facades\DB;

final class IlluminateOrderRepository extends EventSourcedAggregateRootRepository implements OrderRepository
{
    public function __construct()
    {
        parent::__construct(
            aggregateRootClassName: Order::class,
            messageRepository: $this->IlluminateTransactionalMessageRepository()
        );
    }

    private function IlluminateTransactionalMessageRepository(): MessageRepository
    {
        return new IlluminateTransactionalMessageRepository(
            connection: DB::connection(),
            messageRepository: $this->IlluminateMessageRepository(),
            outboxRepository: $this->IlluminateOutboxRepository(),
        );
    }

    private function IlluminateMessageRepository(): MessageRepository
    {
        return new IlluminateMessageRepository(
            connection: DB::connection(),
            tableName: 'orders',
            serializer: new ConstructingMessageSerializer(),
            aggregateRootIdEncoder: new StringIdEncoder(),
            eventIdEncoder: new StringIdEncoder(),
        );
    }

    private function IlluminateOutboxRepository(): OutboxRepository
    {
        return new IlluminateOutboxRepository(
            connection: DB::connection(),
            tableName: 'outbox_messages',
            serializer: new ConstructingMessageSerializer(),
        );
    }
}
