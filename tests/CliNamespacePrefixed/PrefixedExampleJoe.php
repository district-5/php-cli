<?php
namespace FooBar;

use District5\Cli\CliCommand;

/**
 * Class PrefixedExampleJoe
 * @package FooBar
 */
class PrefixedExampleJoe extends CliCommand
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
        $this->result = 'This is Joe!';
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
