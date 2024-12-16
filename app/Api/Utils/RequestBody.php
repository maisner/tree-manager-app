<?php

declare(strict_types=1);

namespace App\Api\Utils;

use Nette\Http\IRequest;
use Nette\Utils\Json;
use Webmozart\Assert\Assert;

readonly class RequestBody
{

    /**
     * @param array<mixed> $data
     */
    public function __construct(private array $data)
    {
    }

    public function isEmpty(): bool
    {
        return $this->data === [];
    }

    public function getNullableIntValue(string $key): ?int
    {
        $value = $this->data[$key] ?? null;

        if ($value === null) {
            return null;
        }

        Assert::numeric($value);

        return (int)$value;
    }

    public function getIntValue(string $key): int
    {
        $value = $this->data[$key] ?? null;
        Assert::notNull($value);
        Assert::numeric($value);

        return (int)$value;
    }

    public function getNullableStringValue(string $key): ?string
    {
        $value = $this->data[$key] ?? null;

        if ($value === null) {
            return null;
        }
        Assert::scalar($value);

        return (string)$value;
    }

    public function getStringValue(string $key): string
    {
        $value = $this->data[$key] ?? null;

        Assert::notNull($value);
        Assert::scalar($value);

        return (string)$value;
    }


    public static function fromHttpRequest(IRequest $request): self
    {
        $rawBody = $request->getRawBody();

        if ($rawBody === null) {
            return new self([]);
        }

        $body = Json::decode($rawBody, true);
        Assert::isArray($body);

        return new self($body);
    }

}