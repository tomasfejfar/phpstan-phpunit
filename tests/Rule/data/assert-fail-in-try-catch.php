<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\TestCase;
use Tomasfejfar\PhpstanPhpunit\Rule\FailInTryCatchRule;

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
    }
}
