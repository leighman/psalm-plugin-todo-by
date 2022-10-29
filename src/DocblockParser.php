<?php

namespace Leighman\PsalmPluginTodoBy;

use Exception;
use DateTimeImmutable;

/**
 * @psalm-type ParseResult = array{
 *   by: DateTimeImmutable,
 *   what: string,
 * }
 */
class DocblockParser
{
    /**
     * @psalm-return ParseResult|"invalid"|"not-found"
     */
    public static function extractTodoBy(string $docblock)
    {
        preg_match(
            '/@todo-by (.+)/',
            $docblock,
            $matches,
        );

        if ($matches) {
            try {
                preg_match(
                    '/(.+) - (.+?)(?:$| \*\/$)/m',
                    $matches[1],
                    $matches,
                );

                if (count($matches) !== 3) {
                    return "invalid";
                }

                return [
                    'by' => new DateTimeImmutable($matches[1]),
                    'what' => $matches[2],
                ];
            } catch (Exception $e) {
                return "invalid";
            }
        }

        return "not-found";
    }
}
