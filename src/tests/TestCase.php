<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use Asserts;
    use CreatesApplication;

    protected array $stubStorage = [];

    protected function createSingleStub(string $className, string $property, string $method, mixed $returnValue): void
    {
        $this->{$property} = parent::createStub($className);
        $this->{$property}->method($method)->willReturn($returnValue);
        $this->stubStorage[$property] = $className;
    }

    protected function overrideStub(string $property, string $method, mixed $returnValue): void
    {
        $className = $this->stubStorage[$property];
        $this->{$property} = $this->createStub($className);
        $this->{$property}->method($method)->willReturn($returnValue);
    }
}
