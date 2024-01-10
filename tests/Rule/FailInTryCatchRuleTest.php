<?php declare(strict_types = 1);

namespace Tomasfejfar\PhpstanPhpunit\Test\Rule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Tomasfejfar\PhpstanPhpunit\Rule\FailInTryCatchRule;

/**
 * @extends RuleTestCase<FailInTryCatchRule>
 */
class FailInTryCatchRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/data/assert-fail-in-try-catch.php'], [
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                14,
            ],
        ]);
    }
    public function testRuleCondition(): void
    {
        $this->analyse([__DIR__ . '/data/assert-fail-in-try-catch-if.php'], [
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                17,
            ],
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                31,
            ],
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                46,
            ],
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                56,
            ],
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                67,
            ],
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                70,
            ],
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                73,
            ],
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                73,
            ],
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                90,
            ],
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                90,
            ],
            [
                'You should always add `$this->fail()` as a last statement in try/catch block.',
                115,
            ],
        ]);
    }

    protected function getRule(): Rule
    {
        return new FailInTryCatchRule();
    }

}
