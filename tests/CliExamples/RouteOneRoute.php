<?php
namespace CliExamples;

use District5\Cli\CliCommand;

/**
 * Class RouteOneRoute
 * @package CliExamples
 */
class RouteOneRoute extends CliCommand
{
    /**
     * @return string
     */
    public function run(): string
    {
        return 'RouteOneRoute';
    }
}
