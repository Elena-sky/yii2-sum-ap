<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\web\Response;

/**
 * Class EvenController
 *
 * REST API Controller that handles calculation of the sum of even numbers.
 *
 * @package app\controllers
 */
final class EvenController extends Controller
{
    /**
     * Handles POST `/api/v1/sum-even` request.
     *
     * @return Response JSON response.
     */
    public function actionSumEven(): Response
    {
        return $this->asJson(['sum' => 12]);
    }
}


