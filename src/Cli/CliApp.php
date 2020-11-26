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

use District5\Cli\Exception\ArgumentNotSetException;
use District5\Cli\Exception\InvalidConsoleArgumentException;

/**
 * Class Cli
 * @noinspection PhpUnused
 * @package District5\Cli
 */
class CliApp
{
    /**
     * @var array
     */
    private $argv;

    /**
     * @var array
     */
    private $injectables;

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
     * Run the console command.
     *
     * @noinspection PhpUnused
     * @throws ArgumentNotSetException
     * @throws InvalidConsoleArgumentException
     */
    public function run()
    {
        if (!array_key_exists(1, $this->argv)) {
            throw new ArgumentNotSetException('Argument not passed.');
        }

        $newArray = array_merge([], $this->argv);
        unset($newArray[0]);
        $proposedClassNames = [];
        $rolling = '\\';
        $tryLength = 0;
        foreach ($newArray as $_ => $segment) {
            $tryLength++;
            $rolling .= $this->treatSegment($segment);
            if ($tryLength > 1) {
                if (class_exists($rolling . 'Route')) {
                    $proposedClassNames[] = $rolling . 'Route';
                }
            }
            $rolling .= '\\';
        }

        $triedClasses = [];
        $found = false;
        foreach (array_reverse($proposedClassNames) as $class) {
            if (!class_exists($class)) {
                $triedClasses[] = $class;
                continue;
            }

            $instance = new $class();
            if ($instance instanceof CliCommand) {
                $found = true;
                $instance->setArguments($this->argv);
                $instance->setInjectables($this->injectables);
                $instance->run();
                break;
            }
        }

        if (!$found) {
            throw new InvalidConsoleArgumentException(sprintf('Tried classes: %s', implode(', ', $triedClasses)));
        }
    }

    /**
     * @param string $segment
     * @return string
     */
    private function treatSegment($segment)
    {
        $segmentPiece = explode('-', $segment);
        $s = '';
        foreach ($segmentPiece as $seg) {
            if (strlen($seg) === 0) {
                continue;
            }
            $s .= ucfirst($seg);
        }
        return $s;
    }
}
