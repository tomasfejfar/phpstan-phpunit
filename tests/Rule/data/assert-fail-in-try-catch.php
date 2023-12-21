<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPUnit\Framework\TestCase;

class DummyTest extends TestCase
{

    public function testSomething(): void
    {
        try {
            $this->doSomething();
            // will fail here
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }

        try {
            $this->doSomething();
            // will not fail here
            $this->fail();
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }

        try {
            $this->doSomething();
            // will not fail here
            self::fail();
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }

        try {
            $this->doSomething();
            // tomasfejfar/phpstan-phpunit:ignore-missing-fail
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }

        try {
            $this->doSomething();
            //     tomasfejfar/phpstan-phpunit:ignore-missing-fail
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }

        try {
            $this->doSomething();
            //tomasfejfar/phpstan-phpunit:ignore-missing-fail
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }
        try {
            $this->doSomething();
            // tomasfejfar/phpstan-phpunit:ignore-missing-fail because of some reason
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }


    }

    private function doSomething()
    {
    }
}
