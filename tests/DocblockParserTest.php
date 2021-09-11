<?php

namespace Leighman\PsalmPluginTodoBy\Tests;

use DateTimeImmutable;
use Leighman\PsalmPluginTodoBy\DocblockParser;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-import-type ParseResult from DocblockParser
 */
class DocblockParserTest extends TestCase
{
    /**
     * @return list<array{
     *   0: string,
     *   1: ParseResult|"invalid"|"not-found",
     * }>
     */
    public function todos(): array
    {
        return [
            [
                "/** @todo-by 3020-01-01T13:00:00Z - do this thing also */",
                [
                    'by' => new DateTimeImmutable('3020-01-01T13:00:00Z'),
                    'what' => 'do this thing also',
                ],
            ],
            [
                "/**
                 * @todo-by 3020-01-01 - and this
                 * @return 1
                 */",
                 [
                     'by' => new DateTimeImmutable("3020-01-01"),
                     'what' => 'and this',
                 ],
            ],
            [
                "/** @var string */",
                "not-found",
            ],
            [
                "/** @todo-by invalid-date - some description */",
                "invalid",
            ],
            [
                "/** @todo-by 2020-01 */", // no description
                "invalid",
            ],
        ];
    }

    /**
     * @test
     * @dataProvider todos
     * @param ParseResult|"invalid"|"not-found" $expected
     */
    public function findsSingleLineTodoBy(string $docblock, $expected): void
    {
        $this->assertEquals(
            $expected,
            DocblockParser::extractTodoBy($docblock),
        );
    }
}
