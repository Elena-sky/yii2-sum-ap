<?php

namespace src\Http;

use Yii;
use yii\web\ErrorAction;
use yii\web\Response;
use yii\web\HttpException;
use Throwable;
use yii\base\InvalidConfigException;

/**
 * Class ApiErrorAction
 */
final class ApiErrorAction extends ErrorAction
{
    /** @var int Internal Server Error Code */
    public const int HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * Executes the error handler.
     *
     * @return Response|array|string
     * @throws InvalidConfigException
     */
    public function run(): Response|array|string
    {
        $request = Yii::$app->request;

        $isJsonRequest =
            stripos((string)$request->headers->get('Accept', ''), 'application/json') !== false ||
            $request->isAjax ||
            str_starts_with($request->getPathInfo(), 'api/');

        if ($isJsonRequest) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $exception = Yii::$app->getErrorHandler()->exception;
            $statusCode = $this->resolveStatusCode($exception);
            $isDebug = defined('YII_DEBUG') && YII_DEBUG;

            return [
                'success' => false,
                'error' => [
                    'status' => $statusCode,
                    'message' => $this->resolveMessage($exception, $statusCode, $isDebug),
                ],
            ];
        }

        return parent::run();
    }

    /**
     * Determines HTTP status code from exception.
     */
    private function resolveStatusCode(?Throwable $exception): int
    {
        return $exception instanceof HttpException
            ? $exception->statusCode
            : self::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Returns safe, user-friendly error message.
     * In debug mode shows the exception message,
     * otherwise uses Yii’s built-in HTTP status texts.
     */
    private function resolveMessage(?Throwable $exception, int $statusCode, bool $debug): string
    {
        if ($debug && $exception) {
            return $exception->getMessage();
        }

        return Response::$httpStatuses[$statusCode] ?? 'Unexpected error occurred.';
    }
}
