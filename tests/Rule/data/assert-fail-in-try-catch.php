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
            // @phpstan-ignore: tomasfejfar-phpstan-phpunit.missingFailInTryCatch
            $this->doSomething();
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }

        try {
            //     @phpstan-ignore: tomasfejfar-phpstan-phpunit.missingFailInTryCatch
            $this->doSomething();
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }

        try {
            //@phpstan-ignore: tomasfejfar-phpstan-phpunit.missingFailInTryCatch
            $this->doSomething();
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }
        try {
            // @phpstan-ignore: tomasfejfar-phpstan-phpunit.missingFailInTryCatch because of some reason
            $this->doSomething();
        } catch (\Exception $e) {
            $this->assertEquals('foo', $e->getMessage());
        }


        // exception should be thrown, as quantity has empty value '' and snflk will complain.
        try {
            $workspaces->loadWorkspaceData($workspace['id'], $options);
            if ($workspaceBackend === self::BACKEND_REDSHIFT && $sourceBackend === self::BACKEND_SNOWFLAKE) {
                // this will not throw
                $this->expectNotToPerformAssertions();
            } else {
                $this->fail('Should have thrown');
            }
        } catch (ClientException $e) {
            $this->assertEquals('workspace.tableLoad', $e->getStringCode());
        }
    }

    private function doSomething()
    {
    }
}
