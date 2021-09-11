<?php

namespace Leighman\PsalmPluginTodoBy;

use DateTimeImmutable;
use PhpParser\{Node, NodeVisitorAbstract};
use Psalm\CodeLocation;
use Psalm\IssueBuffer;
use Psalm\StatementsSource;

class TodoByCheckerVisitor extends NodeVisitorAbstract
{
    /** @var StatementsSource */
    private $source;

    public function __construct(StatementsSource $source)
    {
        $this->source = $source;
    }

    public function enterNode(Node $node)
    {
        $docComment = $node->getDocComment();

        if ($docComment) {
            $todoBy = DocblockParser::extractTodoBy($docComment->getText());

            if ($todoBy === "not-found") {
                return null;
            }

            if ($todoBy === "invalid") {
                IssueBuffer::accepts(
                    new InvalidTodoBy(
                        "Failed to parse @todo-by docblock annotation",
                        new CodeLocation($this->source, $node)
                    ),
                );
                return null;
            }

            if ($todoBy['by'] < new DateTimeImmutable()) {
                IssueBuffer::accepts(
                    new TodoByExceeded(
                        "Todo time limit to \"{$todoBy['what']}\" has passed",
                        new CodeLocation($this->source, $node)
                    ),
                );
            }
        }
    }
}
