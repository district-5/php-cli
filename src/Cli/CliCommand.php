<?php
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
    protected $argv = array();

    /**
     * @var array
     */
    protected $injectables = array();

    /**
     * Set the argv array
     * @param array $argv
     */
    final public function setArguments(array $argv)
    {
        $this->argv = $argv;
    }

    /**
     * Get the argv array
     * @return array
     */
    final public function getArguments()
    {
        return $this->argv;
    }

    /**
     * Get an argv value, based on key
     * @noinspection PhpUnused
     * @param integer|string $key
     * @return string|null
     */
    final public function getArgument($key)
    {
        if (array_key_exists($key, $this->getArguments())) {
            return $this->argv[$key];
        }

        return null;
    }

    /**
     * Set the injectables array
     * @param array $injectables
     */
    final public function setInjectables(array $injectables)
    {
        $this->injectables = $injectables;
    }

    /**
     * Get the injectables array
     * @return array
     */
    final public function getInjectables()
    {
        return $this->injectables;
    }

    /**
     * Get an injectable based on key name
     * @noinspection PhpUnused
     * @param integer|string $key
     * @return mixed|null
     */
    final public function getInjectable($key)
    {
        if (array_key_exists($key, $this->getInjectables())) {
            return $this->injectables[$key];
        }

        return null;
    }

    /**
     * Output information to the terminal, via a string, or optionally an array
     * @param string|array $content
     * @param bool $newLine (optional) default true
     * @param bool $prependDate (optional) default false
     */
    public function outputInfo($content, $newLine = true, $prependDate = false)
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
     * @param string|array $content
     * @param bool $newLine (optional) default true
     * @param bool $prependDate (optional) default false
     */
    public function outputError($content, $newLine = true, $prependDate = false)
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
     * @param string $content
     * @param bool $newLine
     * @param bool $prependDate
     */
    protected function echoLine($content, $newLine = true, $prependDate = false)
    {
        if ($prependDate === true) {
            try {
                $dt = new DateTime();
                $content = sprintf(
                    '%s - %s',
                    $dt->format('Y-m-d H:i:s u'),
                    $content
                );
            } catch (Exception $e) {
            }
        }
        echo $content;
        if ($newLine) {
            echo PHP_EOL;
        }
    }

    abstract function run();
}
