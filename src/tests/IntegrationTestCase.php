<?php

declare(strict_types=1);

namespace Tests;

use Ramsey\Uuid\Uuid;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class IntegrationTestCase extends BaseTestCase
{
    use Asserts;
    use CreatesApplication;
    use RefreshDatabase;
    use WithFaker;

    public function createUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
