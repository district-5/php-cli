<?php
namespace CliExamples;

use District5\Cli\CliCommand;

/**
 * Class TwoFoo
 * @package CliExamples
 */
class TwoFoo extends CliCommand
{
    /**
     * @return string
     */
    public function run(): string
    {
        return 'TwoFoo';
    }
}
