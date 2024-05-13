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

                if (
                    $lastTryStmt === false
                ) {
                    continue;
                }
                $errors = array_merge($errors, $this->checkLastStatement($lastTryStmt, $scope));
            }
        }
        return $errors;
    }

    private function isFailMethodCall(\PhpParser\Node $node, Scope $scope): bool
    {
        if (!$node instanceof Expression) {
            return false;
        }
        if (!$node->expr instanceof MethodCall && !$node->expr instanceof Node\Expr\StaticCall) {
            return false;
        }

        $expression = $node->expr;

        if (!($expression->name->toString() === 'fail')) {
            return false;
        }
        return true;
    }

    private function hasIgnoreComment(\PhpParser\Node $node, Scope $scope): bool
    {
        $comments = $node->getComments();
        if (count($comments) === 0) {
            return false;
        }
        $lastComment = end($comments);
        $lastCommentText = $lastComment->getText();

        return preg_match('|@phpstan-ignore *tomasfejfar.phpstanPhpunit.missingFailInTryCatch|', $lastCommentText)
            === 1;
    }

    public function isFailOrIgnore($lastTryStmt, Scope $scope): array
    {
        $errors = [];
        if (
            !$this->isFailMethodCall($lastTryStmt, $scope)
            && !$this->hasIgnoreComment($lastTryStmt, $scope)
        ) {
            $errors[] = RuleErrorBuilder::message(
                'You should always add `$this->fail()` as a last statement in try/catch block.'
            )
                ->line($lastTryStmt->getLine())
                ->identifier('tomasfejfar.phpstanPhpunit.missingFailInTryCatch')
                ->build();
        }
        return $errors;
    }

    public function checkLastStatement($lastTryStmt, Scope $scope): array
    {
        $errors = [];
        if ($lastTryStmt instanceof Node\Stmt\If_) {
            $if = $lastTryStmt;
            $lastTryStmt = end($if->stmts);
            if ($lastTryStmt) {
                $errors = array_merge($errors, $this->checkLastStatement($lastTryStmt, $scope));
            }
            if ($if->else) {
                $lastTryStmt = end($if->else->stmts);
                if ($lastTryStmt) {
                    $errors = array_merge($errors, $this->checkLastStatement($lastTryStmt, $scope));
                }
            }
            if ($if->elseifs) {
                foreach ($if->elseifs as $elseif) {
                    $lastTryStmt = end($elseif->stmts);
                    if ($lastTryStmt) {
                        $errors = array_merge($errors, $this->checkLastStatement($lastTryStmt, $scope));
                    }
                }
            }
            return $errors;
        }

        if ($lastTryStmt instanceof Node\Stmt\For_ || $lastTryStmt instanceof Node\Stmt\Foreach_ || $lastTryStmt
            instanceof Node\Stmt\While_ || $lastTryStmt instanceof Node\Stmt\Do_) {
            $lastTryStmt = end($lastTryStmt->stmts);
            if ($lastTryStmt) {
                $errors = array_merge($errors, $this->checkLastStatement($lastTryStmt, $scope));
            }
            return $errors;
        }
        if ($lastTryStmt instanceof Node\Stmt\Switch_) {
            foreach ($lastTryStmt->cases as $case) {
                $lastTryStmt = end($case->stmts);
                if ($lastTryStmt) {
                    $errors = array_merge($errors, $this->checkLastStatement($lastTryStmt, $scope));
                }
            }
        }
        $errors = array_merge($errors, $this->isFailOrIgnore($lastTryStmt, $scope));
        return $errors;
    }
}
