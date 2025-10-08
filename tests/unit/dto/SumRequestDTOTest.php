<?php

namespace tests\unit\dto;

use PHPUnit\Framework\TestCase;
use src\DTO\SumRequestDTO;

/**
 * @coversDefaultClass \src\DTO\SumRequestDTO
 */
final class SumRequestDTOTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstructor(): void
    {
        $numbers = [1, 2, 3, 4];
        $dto = new SumRequestDTO($numbers);
        
        $this->assertEquals($numbers, $dto->numbers);
    }

    /**
     * @covers ::fromArray
     */
    public function testFromArray(): void
    {
        $data = ['numbers' => [1, 2, 3, 4]];
        $dto = SumRequestDTO::fromArray($data);
        
        $this->assertEquals([1, 2, 3, 4], $dto->numbers);
    }

    /**
     * @covers ::fromArray
     */
    public function testFromArrayWithEmptyNumbers(): void
    {
        $data = [];
        $dto = SumRequestDTO::fromArray($data);
        
        $this->assertEquals([], $dto->numbers);
    }

    /**
     * @covers ::toArray
     */
    public function testToArray(): void
    {
        $numbers = [1, 2, 3, 4];
        $dto = new SumRequestDTO($numbers);
        
        $expected = ['numbers' => $numbers];
        $this->assertEquals($expected, $dto->toArray());
    }
}
