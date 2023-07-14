<?php
/**
 * @noinspection SpellCheckingInspection
 * @noinspection PhpUnused
 */

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

use DateTime;
use Exception;

/**
 * Class CliCommand
 * @package District5\Cli
 */
abstract class CliCommand
{
    /**
     * @var array
     */
    protected array $argv = [];

    /**
     * @var array
     */
    protected array $injectables = [];

    /**
     * @var CliArgvs|null
     */
    private ?CliArgvs $cliArgvs = null;

    /**
     * Set the argv array
     * @param array $argv
     */
    final public function setArguments(array $argv): void
    {
        $this->argv = $argv;
    }

    /**
     * Get the argv array
     * @return array
     */
    final public function getArguments(): array
    {
        return $this->argv;
    }

    /**
     * Get an argv value, based on key
     *
     * @noinspection PhpUnused
     * @param int|string $key
     * @return string|array|null
     */
    final public function getArgument(int|string $key): array|string|null
    {
        if (is_int($key) && array_key_exists($key, $this->getArguments())) {
            return $this->argv[$key];
        }
        if (is_string($key)) {
            return $this->cliArgvs->getArg($key);
        }

        return null;
    }

    /**
     * Set the injectables array
     * @param array $injectables
     * @return void
     */
    final public function setInjectables(array $injectables): void
    {
        $this->injectables = $injectables;
    }

    /**
     * Get the injectables array
     * @return array
     */
    final public function getInjectables(): array
    {
        return $this->injectables;
    }

    /**
     * Get an injectable based on key name
     * @noinspection PhpUnused
     * @param int|string $key
     * @return mixed|null
     */
    final public function getInjectable(int|string $key): mixed
    {
        if (array_key_exists($key, $this->getInjectables())) {
            return $this->injectables[$key];
        }

        return null;
    }

    /**
     * Output information to the terminal, via a string, or optionally an array
     * @param array|string|int $content
     * @param bool $newLine (optional) default true
     * @param bool $prependDate (optional) default false
     */
    public function outputInfo(array|string|int $content, bool $newLine = true, bool $prependDate = false): void
    {
        if (is_array($content)) {
            foreach ($content as $string) {
                $this->echoLine($string, $newLine, $prependDate);
            }
        } else {
            $this->echoLine($content, $newLine, $prependDate);
        }
    }

    /**
     * Output errors to the terminal, via a string, or optionally an array
     * @param array|string|int $content
     * @param bool $newLine (optional) default true
     * @param bool $prependDate (optional) default false
     */
    public function outputError(array|string|int $content, bool $newLine = true, bool $prependDate = false): void
    {
        $tpl = "\033[0;31m%s\033[0m";
        if (is_array($content)) {
            foreach ($content as $string) {
                $this->echoLine(sprintf($tpl, $string), $newLine, $prependDate);
            }
        } else {
            $this->echoLine(sprintf($tpl, $content), $newLine, $prependDate);
        }
    }

    /**
     * @param string|int $content
     * @param bool $newLine
     * @param bool $prependDate
     */
    protected function echoLine(string|int $content, bool $newLine = true, bool $prependDate = false)
    {
        if ($prependDate === true) {
            try {
                $dt = new DateTime();
                $content = sprintf(
                    '%s - %s',
                    $dt->format('Y-m-d H:i:s u'),
                    $content
                );
            } catch (Exception) {
            }
        }
        echo $content;
        if ($newLine) {
            echo PHP_EOL;
        }
    }

    abstract function run();

    /**
     * @param CliArgvs|null $cliArgvs
     * @return $this
     */
    public function setCliArgvs(?CliArgvs $cliArgvs): CliCommand
    {
        $this->cliArgvs = $cliArgvs;
        return $this;
    }

    /**
     * @return CliArgvs|null
     */
    public function getCliArgvs(): ?CliArgvs
    {
        return $this->cliArgvs;
    }
}
