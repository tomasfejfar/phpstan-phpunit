<?php

declare(strict_types = 1);

namespace Tomasfejfar\PhpstanPhpunit\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\TryCatch;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use function PHPUnit\Framework\stringStartsWith;

class FailInTryCatchRule implements Rule
{

    public function getNodeType(): string
    {
        return Node\Stmt\ClassMethod::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node instanceof Node\Stmt\ClassMethod) {
            return [];
        }
        // Check if the method name starts with "test"
        // You can customize this check based on your naming conventions
        if (!str_starts_with($node->name->toLowerString(), 'test')) {
            return [];
        }

        return $this->checkTryCatchBlocks($node, $scope);
    }

    /**
     * @return RuleError[]
     */
    private function checkTryCatchBlocks(ClassMethod $methodNode, Scope $scope): array
    {
        $errors = [];
        foreach ($methodNode->stmts as $stmt) {
            if ($stmt instanceof TryCatch) {
                $lastTryStmt = end($stmt->stmts);

                // Ensure that the last statement in the "try" block is a call to $this->fail()
                if ($lastTryStmt !== false && !$this->isFailMethodCall($lastTryStmt, $scope)) {
                    $errors[] =	RuleErrorBuilder::message(
                        'You should always add `$this->fail()` as a last statement in try/catch block.'
                    )
                        ->line($lastTryStmt->getLine())
                            ->identifier('phpunit.tryCatch.fail')
                            ->build();
                }
            }
        }
        return $errors;
    }

    private function isFailMethodCall(\PhpParser\Node $node, Scope $scope): bool
    {
        if (!$node instanceof Expression) {
            return false;
        }
        if (!$node->expr instanceof MethodCall) {
            return false;
        }

        $expression = $node->expr;

        if (!$expression->name === 'fail') {
            return false;
        }
        return true;
    }
}
