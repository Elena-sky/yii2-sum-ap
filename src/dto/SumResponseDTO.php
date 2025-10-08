<?php

namespace src\DTO;

/**
 * Data Transfer Object for sum response
 */
final readonly class SumResponseDTO
{
    public function __construct(
        public int $sum
    ) {
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'sum' => $this->sum
        ];
    }

    /**
     * Create DTO with zero sum
     */
    public static function zero(): self
    {
        return new self(0);
    }
}
