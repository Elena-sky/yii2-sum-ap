<?php

namespace src\Validation;

use yii\base\Model;

/**
 * Class SumRequestForm
 *
 * Handles validation of the input JSON.
 *
 * @package src\Validation
 */
class SumRequestForm extends Model
{
    /**
     * @var int[]|mixed List of numbers.
     */
    public mixed $numbers = [];

    /**
     * {@inheritdoc}
     *
     * @return array[] Validation rules.
     */
    public function rules(): array
    {
        return [
            [['numbers'], 'required'],
            ['numbers', 'validateIsArray'],
            ['numbers', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * Custom validator that ensures the value is an array.
     *
     * @param string $attribute Attribute name.
     */
    public function validateIsArray(string $attribute): void
    {
        if (!is_array($this->$attribute)) {
            $this->addError($attribute, 'numbers must be an array');
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string,string> Attribute labels.
     */
    public function attributeLabels(): array
    {
        return [
            'numbers' => 'Numbers',
        ];
    }
}
