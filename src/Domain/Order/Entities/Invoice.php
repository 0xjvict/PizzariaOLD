<?php

namespace Domain\Order\Entities;

use Domain\Order\ValueObjects\InvoiceId;
use Domain\Order\ValueObjects\OrderId;

final class Invoice
{
    private function __construct(
        private readonly InvoiceId $id,
        private readonly OrderId   $orderId,
        private readonly string    $urlXml,
        private readonly string    $urlDanfe,
    )
    {
    }

    /**
     * @param OrderId $orderId id of the order
     * @param string $urlXml URL of the XML file
     * @param string $urlDanfe URL of the DANFE file
     * @return self
     */
    public static function create(OrderId $orderId, string $urlXml, string $urlDanfe): self
    {
        self::validateUrlXml($urlXml);

        return new self(
            id: InvoiceId::generate(),
            orderId: $orderId,
            urlXml: $urlXml,
            urlDanfe: $urlDanfe,
        );
    }

    public static function restore(
        InvoiceId $id,
        OrderId   $orderId,
        string    $urlXml,
        string    $urlDanfe
    ): self
    {
        self::validateUrlXml($urlXml);

        return new self(
            id: $id,
            orderId: $orderId,
            urlXml: $urlXml,
            urlDanfe: $urlDanfe,
        );
    }

    public static function fromArray(array $invoice): Invoice
    {
        return new self(
            id: InvoiceId::fromString($invoice['id']),
            orderId: OrderId::fromString($invoice['order_id']),
            urlXml: $invoice['url_xml'],
            urlDanfe: $invoice['url_danfe'],
        );
    }

    private static function validateUrlXml(string $urlXml): void
    {
        if (filter_var($urlXml, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('Invalid URL');
        } else if (empty(file_get_contents($urlXml))) {
            throw new \InvalidArgumentException('XML file is empty');
        }
    }

    private static function validateUrlDanfe(string $urlDanfe): void
    {
        if (filter_var($urlDanfe, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('Invalid URL');
        } else if (empty(file_get_contents($urlDanfe))) {
            throw new \InvalidArgumentException('XML file is empty');
        }
    }

    public function id(): InvoiceId
    {
        return $this->id;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function urlXml(): string
    {
        return $this->urlXml;
    }

    public function urlDanfe(): string
    {
        return $this->urlDanfe;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(),
            'order_id' => $this->orderId->toString(),
            'url_xml' => $this->urlXml,
            'url_danfe' => $this->urlDanfe,
        ];
    }
}
