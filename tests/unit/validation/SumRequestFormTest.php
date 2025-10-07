<?php

namespace tests\unit\validation;

use Codeception\Test\Unit;
use src\Validation\SumRequestForm;

class SumRequestFormTest extends Unit
{
    public function testRequired(): void
    {
        $form = new SumRequestForm();
        $form->load([], '');
        $this->assertFalse($form->validate());
        $this->assertArrayHasKey('numbers', $form->getErrors());
    }

    public function testNotArray(): void
    {
        $form = new SumRequestForm();
        $form->load(['numbers' => 'not an array'], '');
        $this->assertFalse($form->validate());
        $this->assertArrayHasKey('numbers', $form->getErrors());
        $this->assertContains('numbers must be an array', $form->getErrors()['numbers']);
    }

    public function testEachIntegerValidation(): void
    {
        $form = new SumRequestForm();
        $form->load(['numbers' => [1, '2', null, 3.5]], '');
        $this->assertFalse($form->validate());
        $this->assertArrayHasKey('numbers', $form->getErrors());
    }

    public function testValid(): void
    {
        $form = new SumRequestForm();
        $form->load(['numbers' => [1, 2, 3, 4]], '');
        $this->assertTrue($form->validate());
        $this->assertEmpty($form->getErrors());
    }

    public function testNumbersNull(): void
    {
        $form = new SumRequestForm();
        $form->load(['numbers' => null], '');
        $this->assertFalse($form->validate());
        $this->assertArrayHasKey('numbers', $form->getErrors());
    }

    public function testAttributeLabels(): void
    {
        $form = new SumRequestForm();
        $labels = $form->attributeLabels();
        $this->assertIsArray($labels);
        $this->assertArrayHasKey('numbers', $labels);
        $this->assertSame('Numbers', $labels['numbers']);
    }
}


