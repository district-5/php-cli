<?php
namespace FooBar;

use District5\Cli\CliCommand;

/**
 * Class PrefixedExampleRoute
 * @package FooBar
 */
class PrefixedExampleRoute extends CliCommand
{
    /**
     * @var string
     */
    private $result;

    /**
     * @return string
     */
    public function run(): string
    {
        $this->result = 'The prefixed namespace example works.';
        return $this->result;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }
}
