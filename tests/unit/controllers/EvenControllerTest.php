<?php

namespace tests\unit\controllers;

use PHPUnit\Framework\TestCase;
use app\controllers\EvenController;
use yii\web\Application;
use yii\web\Request;
use yii\web\Response;
use yii\base\Module;

/**
 * @coversDefaultClass \app\controllers\EvenController
 */
final class EvenControllerTest extends TestCase
{
    private EvenController $controller;
    private Application $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = new Application([
            'id' => 'test-app',
            'basePath' => __DIR__ . '/../../..',
            'components' => [
                'request' => [
                    'class' => Request::class,
                ],
                'response' => [
                    'class' => Response::class,
                ],
            ],
        ]);

        \Yii::$app = $this->app;

        $module = new Module('test');
        $this->controller = new EvenController('even', $module);
    }

    protected function tearDown(): void
    {
        \Yii::$app = null;
        parent::tearDown();
    }

    /**
     * @covers ::actionSumEven
     */
    public function testActionSumEvenWithValidData(): void
    {
        $this->app->request->setBodyParams([
            'numbers' => [2, 4, 6, 8]
        ]);

        $result = $this->controller->actionSumEven();

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::FORMAT_JSON, $result->format);
    }

    /**
     * @covers ::actionSumEven
     */
    public function testActionSumEvenWithEmptyData(): void
    {
        $this->app->request->setBodyParams([]);

        $result = $this->controller->actionSumEven();

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::FORMAT_JSON, $result->format);
    }

    /**
     * @covers ::actionSumEven
     */
    public function testActionSumEvenWithInvalidData(): void
    {
        $this->app->request->setBodyParams([
            'numbers' => 'not an array'
        ]);

        $result = $this->controller->actionSumEven();

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::FORMAT_JSON, $result->format);
    }

    /**
     * @covers ::actionSumEven
     */
    public function testActionSumEvenWithNonIntegerData(): void
    {
        $this->app->request->setBodyParams([
            'numbers' => [2.5, 'abc', 4]
        ]);

        $result = $this->controller->actionSumEven();

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::FORMAT_JSON, $result->format);
    }

    /**
     * @covers ::actionSumEven
     */
    public function testActionSumEvenWithNullData(): void
    {
        $this->app->request->setBodyParams([
            'numbers' => null
        ]);

        $result = $this->controller->actionSumEven();

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::FORMAT_JSON, $result->format);
    }

    /**
     * @covers ::actionSumEven
     */
    public function testActionSumEvenWithOddNumbers(): void
    {
        $this->app->request->setBodyParams([
            'numbers' => [1, 3, 5, 7]
        ]);

        $result = $this->controller->actionSumEven();

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::FORMAT_JSON, $result->format);
    }

    /**
     * @covers ::actionSumEven
     */
    public function testActionSumEvenWithMixedNumbers(): void
    {
        $this->app->request->setBodyParams([
            'numbers' => [1, 2, 3, 4, 5, 6]
        ]);

        $result = $this->controller->actionSumEven();

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::FORMAT_JSON, $result->format);
    }

    /**
     * @covers ::actionSumEven
     */
    public function testActionSumEvenResponseFormat(): void
    {
        $this->app->request->setBodyParams([
            'numbers' => [2, 4]
        ]);

        $result = $this->controller->actionSumEven();

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::FORMAT_JSON, $result->format);
    }
}
