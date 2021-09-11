<?php

namespace Leighman\PsalmPluginTodoBy;

use PhpParser\NodeTraverser;
use Psalm\Plugin\EventHandler\AfterFileAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterFileAnalysisEvent;

class TodoByChecker implements AfterFileAnalysisInterface
{
    public static function afterAnalyzeFile(AfterFileAnalysisEvent $event): void
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new TodoByCheckerVisitor($event->getStatementsSource()));

        $traverser->traverse($event->getStmts());
    }
}
