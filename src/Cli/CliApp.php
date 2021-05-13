<?php /** @noinspection PhpUnused */

/**
 * District5 - Cli
 *
 * @copyright District5
 *
 * @author District5
 * @author Roger Thomas <roger.thomas@district5.co.uk>
 * @link https://www.district5.co.uk
 *
 * @license This software and associated documentation (the "Software") may not be
 * used, copied, modified, distributed, published or licensed to any 3rd party
 * without the written permission of District5 or its author.
 *
 * The above copyright notice and this permission notice shall be included in
 * all licensed copies of the Software.
 *
 */

namespace District5\Cli;

use District5\Cli\Exception\ArgumentNotSetException;
use District5\Cli\Exception\InvalidConsoleArgumentException;

/**
 * Class CliApp
 * @noinspection PhpUnused
 * @package District5\Cli
 */
class CliApp
{
    /**
     * Static variable, holding the instance of this Singleton.
     *
     * @var CliApp|null
     */
    protected static $_instance = null;

    /**
     * @var array
     */
    private $argv;

    /**
     * @var array
     */
    private $injectables;

    /**
     * @var string|null
     */
    private $nsPrefix = null;

    /**
     * @var string
     */
    private $clzAppend = 'Route';

    /**
     * Construct giving the argv variable
     *
     * @noinspection PhpUnused
     * @param array $argv
     * @param array $injectables (optional) default empty array
     */
    public function __construct(array $argv, array $injectables = [])
    {
        $this->argv = $argv;
        $this->injectables = $injectables;
    }

    /**
     * @return array
     */
    public function getCliArguments(): array
    {
        return $this->argv;
    }

    /**
     * @return array
     */
    public function getCliInjectables(): array
    {
        return $this->injectables;
    }

    /**
     * Run the console command.
     *
     * @noinspection PhpUnused
     * @throws ArgumentNotSetException
     * @throws InvalidConsoleArgumentException
     */
    public function run(): ?CliCommand
    {
        if (!array_key_exists(1, $this->argv)) {
            throw new ArgumentNotSetException('Argument not passed.');
        }

        if (null === $command = CliRouter::getClassForRoute($this->argv, $this->injectables, $this->clzAppend, $this->nsPrefix)) {
            throw new InvalidConsoleArgumentException(
                sprintf(
                    'Could not find appropriate command for: %s',
                    implode(' ', $this->argv)
                )
            );
        }

        $command->run();
        return $command;
    }

    /**
     * Retrieve an instance of the given class.
     *
     * @param array|null $argv
     * @param array|null $injectables
     * @return $this
     */
    public static function createApp(array $argv = null, array $injectables = null): CliApp
    {
        if (null !== $argv && null !== $injectables) {
            static::$_instance = new static($argv, $injectables);
        }
        return static::$_instance;
    }

    /**
     * @param string $prefix
     * @return $this
     */
    public function setPsrNamespacePrefix(string $prefix): CliApp
    {
        $this->nsPrefix = $prefix;
        return $this;
    }

    /**
     * @param string $append
     * @return $this
     */
    public function setRouteAppend(string $append): CliApp
    {
        $this->clzAppend = $append;
        return $this;
    }
}
