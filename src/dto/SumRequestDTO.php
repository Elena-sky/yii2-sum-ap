<?php

namespace src\DTO;

/**
 * Data Transfer Object for sum request
 */
final readonly class SumRequestDTO
{
    public function __construct(
        public array $numbers
    ) {
    }

    /**
     * Create DTO from array data
     */
    public static function fromArray(array $data): self
    {
        return new self($data['numbers'] ?? []);
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'numbers' => $this->numbers
        ];
    }
}
