<?php

namespace Leighman\PsalmPluginTodoBy;

use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

class Plugin implements PluginEntryPointInterface
{
    public function __invoke(
        RegistrationInterface $psalm,
        ?SimpleXMLElement $config = null
    ): void {
        if (class_exists(TodoByChecker::class)) {
            $psalm->registerHooksFromClass(TodoByChecker::class);
        }
    }
}
