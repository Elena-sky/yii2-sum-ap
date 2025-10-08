<?php

namespace tests\unit\services;

use PHPUnit\Framework\TestCase;
use src\Services\NumbersSumService;
use src\DTO\SumRequestDTO;

/**
 * @coversDefaultClass \src\Services\NumbersSumService
 */
final class NumbersSumServiceTest extends TestCase
{
    private NumbersSumService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NumbersSumService();
    }

    /**
     * @covers ::sumEven
     */
    public function testSumEvenWithEvenNumbers(): void
    {
        $dto = new SumRequestDTO([2, 4, 6, 8]);
        $result = $this->service->sumEven($dto);
        
        $this->assertEquals(20, $result->sum);
    }

    /**
     * @covers ::sumEven
     */
    public function testSumEvenWithOddNumbers(): void
    {
        $dto = new SumRequestDTO([1, 3, 5, 7]);
        $result = $this->service->sumEven($dto);
        
        $this->assertEquals(0, $result->sum);
    }

    /**
     * @covers ::sumEven
     */
    public function testSumEvenWithMixedNumbers(): void
    {
        $dto = new SumRequestDTO([1, 2, 3, 4, 5, 6]);
        $result = $this->service->sumEven($dto);
        
        $this->assertEquals(12, $result->sum);
    }

    /**
     * @covers ::sumEven
     */
    public function testSumEvenWithEmptyArray(): void
    {
        $dto = new SumRequestDTO([]);
        $result = $this->service->sumEven($dto);
        
        $this->assertEquals(0, $result->sum);
    }

    /**
     * @covers ::sumEven
     */
    public function testSumEvenWithStringNumbers(): void
    {
        $dto = new SumRequestDTO(['2', '4', '6']);
        $result = $this->service->sumEven($dto);
        
        $this->assertEquals(12, $result->sum);
    }

    /**
     * @covers ::sumEven
     */
    public function testSumEvenWithNonNumericValues(): void
    {
        $dto = new SumRequestDTO([2, 'abc', 4, null, 6]);
        $result = $this->service->sumEven($dto);
        
        $this->assertEquals(12, $result->sum);
    }

    /**
     * @covers ::sumEven
     */
    public function testSumEvenWithNegativeNumbers(): void
    {
        $dto = new SumRequestDTO([-2, -4, 3, 6]);
        $result = $this->service->sumEven($dto);
        
        $this->assertEquals(0, $result->sum);
    }

    /**
     * @covers ::sumEven
     * @covers ::isEven
     */
    public function testSumEvenWithFloatNumbers(): void
    {
        $dto = new SumRequestDTO([2.0, 4.5, 6.0, 7.8]);
        $result = $this->service->sumEven($dto);
        
        $this->assertEquals(12, $result->sum);
    }

    /**
     * @covers ::sumEven
     * @covers ::isEven
     */
    public function testSumEvenWithBooleanValues(): void
    {
        $dto = new SumRequestDTO([true, false, 2, 3]);
        $result = $this->service->sumEven($dto);
        
        $this->assertEquals(2, $result->sum);
    }

    /**
     * @covers ::sumEven
     * @covers ::isEven
     */
    public function testSumEvenWithZeroValues(): void
    {
        $dto = new SumRequestDTO([0, 1, 2, 3]);
        $result = $this->service->sumEven($dto);
        
        $this->assertEquals(2, $result->sum);
    }
}
