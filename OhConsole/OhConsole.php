<?php
/**
 * OhConsole - a simple console command line tool
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @since       1.0.0
 * @package     OhConsole
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace OhConsole;

use OhConsole\Exception\ArgumentNotSetException;
use OhConsole\Exception\InvalidConsoleArgumentException;

/**
 * Class OhConsole
 * @noinspection PhpUnused
 * @package OhConsole
 */
class OhConsole
{
    /**
     * @var array
     */
    private $argv = array();

    /**
     * @var array
     */
    private $injectables = array();

    /**
     * Construct giving the argv variable
     *
     * @noinspection PhpUnused
     * @param array $argv
     * @param array $injectables (optional) default empty array
     */
    public function __construct(array $argv, array $injectables = array())
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
            if ($instance instanceof OhCommand) {
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
