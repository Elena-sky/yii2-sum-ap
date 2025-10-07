<?php

namespace tests\unit\http;

use PHPUnit\Framework\TestCase;
use src\Http\ApiErrorAction;
use yii\base\InvalidConfigException;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\Application;
use yii\web\Request;
use yii\web\ErrorHandler;
use yii\base\Module;
use yii\web\Controller;

/**
 * @coversDefaultClass \src\Http\ApiErrorAction
 */
final class ApiErrorActionTest extends TestCase
{
    private ApiErrorAction $action;
    private Application $app;

    /**
     * @throws InvalidConfigException
     */
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
                'errorHandler' => [
                    'class' => ErrorHandler::class,
                ],
            ],
        ]);

        \Yii::$app = $this->app;

        $module = new Module('test');
        $controller = new Controller('dummy', $module);
        $this->action = new ApiErrorAction('error', $controller);
    }

    protected function tearDown(): void
    {
        \Yii::$app = null;
        parent::tearDown();
    }

    /**
     * @covers ::resolveStatusCode
     */
    public function testResolveStatusCodeWithHttpException(): void
    {
        $reflection = new \ReflectionClass($this->action);
        $method = $reflection->getMethod('resolveStatusCode');
        $method->setAccessible(true);

        $exception = new HttpException(404, 'Not Found');
        $statusCode = $method->invoke($this->action, $exception);
        $this->assertEquals(404, $statusCode);
    }

    /**
     * @covers ::resolveStatusCode
     */
    public function testResolveStatusCodeWithGenericException(): void
    {
        $reflection = new \ReflectionClass($this->action);
        $method = $reflection->getMethod('resolveStatusCode');
        $method->setAccessible(true);

        $exception = new \RuntimeException('Something broke');
        $statusCode = $method->invoke($this->action, $exception);
        $this->assertEquals(500, $statusCode);
    }

    /**
     * @covers ::resolveStatusCode
     */
    public function testResolveStatusCodeWithNull(): void
    {
        $reflection = new \ReflectionClass($this->action);
        $method = $reflection->getMethod('resolveStatusCode');
        $method->setAccessible(true);

        $statusCode = $method->invoke($this->action, null);
        $this->assertEquals(500, $statusCode);
    }

    /**
     * @covers ::resolveMessage
     */
    public function testResolveMessageDebugShowsException(): void
    {
        $reflection = new \ReflectionClass($this->action);
        $method = $reflection->getMethod('resolveMessage');
        $method->setAccessible(true);

        $exception = new \RuntimeException('Visible in debug');
        $msg = $method->invoke($this->action, $exception, 500, true);
        $this->assertSame('Visible in debug', $msg);
    }

    /**
     * @covers ::resolveMessage
     */
    public function testResolveMessageNonDebugUsesHttpStatuses(): void
    {
        $reflection = new \ReflectionClass($this->action);
        $method = $reflection->getMethod('resolveMessage');
        $method->setAccessible(true);

        $msg = $method->invoke($this->action, null, 404, false);
        $this->assertSame(Response::$httpStatuses[404], $msg);
    }

    /**
     * @covers ::resolveMessage
     */
    public function testResolveMessageFallbackText(): void
    {
        $reflection = new \ReflectionClass($this->action);
        $method = $reflection->getMethod('resolveMessage');
        $method->setAccessible(true);

        $msg = $method->invoke($this->action, null, 999, false);
        $this->assertSame('Unexpected error occurred.', $msg);
    }

    /**
     * @covers ::resolveMessage
     */
    public function testResolveMessageWithExceptionButNotDebug(): void
    {
        $reflection = new \ReflectionClass($this->action);
        $method = $reflection->getMethod('resolveMessage');
        $method->setAccessible(true);

        $exception = new \RuntimeException('Hidden in production');
        $msg = $method->invoke($this->action, $exception, 500, false);
        $this->assertSame(Response::$httpStatuses[500], $msg);
    }

    /**
     * @covers ::resolveMessage
     */
    public function testResolveMessageWithNullExceptionButDebug(): void
    {
        $reflection = new \ReflectionClass($this->action);
        $method = $reflection->getMethod('resolveMessage');
        $method->setAccessible(true);

        $msg = $method->invoke($this->action, null, 500, true);
        $this->assertSame(Response::$httpStatuses[500], $msg);
    }

    /**
     * @covers ::run
     */
    public function testRunWithJsonRequest(): void
    {
        $this->app->request->headers->set('Accept', 'application/json');
        
        $exception = new HttpException(404, 'Not Found');
        $this->app->errorHandler->exception = $exception;
        
        $result = $this->action->run();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('error', $result);
        $this->assertFalse($result['success']);
        $this->assertEquals(404, $result['error']['status']);
        $this->assertEquals('Not Found', $result['error']['message']);
        
        $this->assertEquals(Response::FORMAT_JSON, $this->app->response->format);
    }

    /**
     * @covers ::run
     */
    public function testRunWithAjaxRequest(): void
    {
        $this->app->request->headers->set('X-Requested-With', 'XMLHttpRequest');
        
        $exception = new \RuntimeException('Server Error');
        $this->app->errorHandler->exception = $exception;
        
        $result = $this->action->run();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('error', $result);
        $this->assertFalse($result['success']);
        $this->assertEquals(500, $result['error']['status']);
        
        $this->assertEquals(Response::FORMAT_JSON, $this->app->response->format);
    }

    /**
     * @covers ::run
     */
    public function testRunWithApiPath(): void
    {
        $this->app->request->setPathInfo('api/users');
        
        $exception = new HttpException(403, 'Forbidden');
        $this->app->errorHandler->exception = $exception;
        
        $result = $this->action->run();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('error', $result);
        $this->assertFalse($result['success']);
        $this->assertEquals(403, $result['error']['status']);
        $this->assertEquals('Forbidden', $result['error']['message']);
        
        $this->assertEquals(Response::FORMAT_JSON, $this->app->response->format);
    }


    /**
     * @covers ::run
     */
    public function testRunWithDebugMode(): void
    {
        $this->app->request->headers->set('Accept', 'application/json');
        
        $exception = new \RuntimeException('Debug message');
        $this->app->errorHandler->exception = $exception;
        
        $result = $this->action->run();
        
        $this->assertIsArray($result);
        if (defined('YII_DEBUG') && YII_DEBUG) {
            $this->assertEquals('Debug message', $result['error']['message']);
        } else {
            $this->assertEquals(Response::$httpStatuses[500], $result['error']['message']);
        }
    }

    /**
     * @covers ::run
     */
    public function testRunWithProductionMode(): void
    {
        $this->app->request->headers->set('Accept', 'application/json');
        
        $exception = new \RuntimeException('Hidden message');
        $this->app->errorHandler->exception = $exception;
        
        $result = $this->action->run();
        
        $this->assertIsArray($result);
        if (defined('YII_DEBUG') && YII_DEBUG) {
            $this->assertEquals('Hidden message', $result['error']['message']);
        } else {
            $this->assertEquals(Response::$httpStatuses[500], $result['error']['message']);
        }
    }

    /**
     * @covers ::run
     */
    public function testRunWithNonJsonRequest(): void
    {
        $this->app->request->headers->set('Accept', 'text/html');
        $this->app->request->setPathInfo('site/error'); // не API путь

        $exception = new HttpException(404, 'Not Found');
        $this->app->errorHandler->exception = $exception;
        
        $module = new Module('test');
        $controller = new Controller('site', $module);
        $controller->setViewPath(__DIR__ . '/../../../views/site');
        
        $action = new ApiErrorAction('error', $controller);
        
        $this->app->set('view', [
            'class' => \yii\web\View::class,
        ]);
        
        $result = $action->run();
        
        $this->assertIsString($result);
        $this->assertStringContainsString('404', $result); // Проверяем, что в результате есть код ошибки
    }
}