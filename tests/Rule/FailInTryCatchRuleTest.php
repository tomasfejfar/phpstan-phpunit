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
                'You should always add `$this->fail()` as a last statement in try/catch block (ignore by `tomasfejfar/phpstan-phpunit:ignore-missing-fail` comment).',
                14,
            ],
        ]);
    }

    protected function getRule(): Rule
    {
        return new FailInTryCatchRule();
    }

}
