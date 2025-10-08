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
            ['numbers', 'validateNumericLikeArray'],
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
     * Ensure all values are numeric-like (int, float, numeric string, signed numeric string).
     */
    public function validateNumericLikeArray(string $attribute): void
    {
        if (!is_array($this->$attribute)) {
            return;
        }

        foreach ($this->$attribute as $index => $value) {
            if (!is_numeric($value)) {
                $this->addError($attribute, "numbers[$index] must be numeric");
            }
        }
    }

    /**
     * After successful validation, cast all values to integers.
     */
    public function afterValidate(): void
    {
        parent::afterValidate();

        if ($this->hasErrors()) {
            return;
        }

        $this->numbers = array_map(static fn($n) => (int)$n, $this->numbers);
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
