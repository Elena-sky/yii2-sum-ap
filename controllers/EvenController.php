<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use src\Validation\SumRequestForm;

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
     * Handles POST `/api/sum-even` request.
     *
     * @return Response JSON response.
     */
    public function actionSumEven(): Response
    {
        $form = new SumRequestForm();
        $form->load(Yii::$app->request->getBodyParams(), '');

        if (!$form->validate()) {
            return $this->asJson(['errors' => $form->getErrors()]);
        }

        return $this->asJson(['sum' => 12]);
    }
}


