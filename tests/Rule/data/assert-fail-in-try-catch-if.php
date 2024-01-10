<?php declare(strict_types = 1);

namespace PHPStan\Rules\PHPUnit;

use PHPUnit\Framework\TestCase;

class DummyTest extends TestCase
{

    public function testSomething(): void
    {
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

        // exception should be thrown, as quantity has empty value '' and snflk will complain.
        try {
            if ($some) {
                if ($some) {
                    $this->expectNotToPerformAssertions();
                    foreach ($some as $someOther) {
                        $this->doSomething();
                    }
                } else {
                    $this->fail('Should have thrown');
                }
            } else {
                $this->fail('Should have thrown');
            }
        } catch (ClientException $e) {
            $this->assertEquals('workspace.tableLoad', $e->getStringCode());
        }

        try {
            if ($some) {
                do {
                    $this->doSomething();
                } while ($some);
            }
        } catch (ClientException $e) {

            $this->assertEquals('workspace.tableLoad', $e->getStringCode());
        }
        try {
            if ($some) {
                while ($someOther) {
                    $this->doSomething();
                }
            }
        } catch (ClientException $e) {
            $this->assertEquals('workspace.tableLoad', $e->getStringCode());
        }
        try {
            if ($some) {
                switch ($someOther) {
                    case 1:
                        $this->doSomething1();
                        break;
                    case 2:
                        $this->doSomething2();
                        break;
                    default:
                        $this->doSomething3();
                        break;
                }
            }
        } catch (ClientException $e) {
            $this->assertEquals('workspace.tableLoad', $e->getStringCode());
        }
        try {
            if ($some) {
                switch ($someOther) {
                    case 1:
                        $this->doSomething1();
                        $this->fail();
                    case 2:
                        $this->doSomething2();
                        $this->fail();
                    default:
                        $this->doSomething3();
                        break;
                }
            }
        } catch (ClientException $e) {
            $this->assertEquals('workspace.tableLoad', $e->getStringCode());
        }
        try {
            if ($some) {
                switch ($someOther) {
                    case 1:
                        $this->doSomething1();
                        $this->fail();
                    case 2:
                        $this->doSomething2();
                        $this->fail();
                    default:
                        $this->doSomething3();
                        $this->fail();
                }
            }
        } catch (ClientException $e) {
            $this->assertEquals('workspace.tableLoad', $e->getStringCode());
        }
        try {
            if ($some) {
                match ($someOther) {
                    1 => $this->doSomething1(),
                    2 => $this->doSomething2(),
                    default => $this->doSomething3(),
                };
            }
        } catch (ClientException $e) {
            $this->assertEquals('workspace.tableLoad', $e->getStringCode());
        }
    }

    private function doSomething()
    {
    }
}
