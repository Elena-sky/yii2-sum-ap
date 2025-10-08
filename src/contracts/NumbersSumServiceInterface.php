<?php

namespace src\Contracts;

use src\DTO\SumRequestDTO;
use src\DTO\SumResponseDTO;

/**
 * Interface for numbers sum service
 */
interface NumbersSumServiceInterface
{
    /**
     * Calculate sum of even numbers
     */
    public function sumEven(SumRequestDTO $dto): SumResponseDTO;
}
