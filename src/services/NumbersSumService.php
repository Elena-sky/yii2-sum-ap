<?php

namespace src\Services;

use src\Contracts\NumbersSumServiceInterface;
use src\DTO\SumRequestDTO;
use src\DTO\SumResponseDTO;

/**
 * Service for calculating sum of even numbers
 */
final class NumbersSumService implements NumbersSumServiceInterface
{
    /**
     * Calculate sum of even numbers from the provided array
     */
    public function sumEven(SumRequestDTO $dto): SumResponseDTO
    {
        $sum = array_reduce(
            $dto->numbers,
            fn(int $total, mixed $value) => $this->isEven($value)
                ? $total + (int)$value
                : $total,
            0
        );

        return new SumResponseDTO($sum);
    }

    /**
     * Check if number is even
     */
    private function isEven(mixed $number): bool
    {
        if (!is_numeric($number)) {
            return false;
        }

        return ((int)$number) % 2 === 0;
    }
}
