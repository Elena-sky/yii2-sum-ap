<?php

namespace tests\unit\dto;

use PHPUnit\Framework\TestCase;
use src\DTO\SumResponseDTO;

/**
 * @coversDefaultClass \src\DTO\SumResponseDTO
 */
final class SumResponseDTOTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstructor(): void
    {
        $sum = 42;
        $dto = new SumResponseDTO($sum);
        
        $this->assertEquals($sum, $dto->sum);
    }

    /**
     * @covers ::toArray
     */
    public function testToArray(): void
    {
        $sum = 42;
        $dto = new SumResponseDTO($sum);
        
        $expected = ['sum' => $sum];
        $this->assertEquals($expected, $dto->toArray());
    }

    /**
     * @covers ::zero
     */
    public function testZero(): void
    {
        $dto = SumResponseDTO::zero();
        
        $this->assertEquals(0, $dto->sum);
    }
}
