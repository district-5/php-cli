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
namespace OhConsole\Tests;

use OhConsole\OhCommand;

class CommandForTests extends OhCommand
{
    /**
     * @var string
     */
    protected $command = 'ohconsole-command-for-tests';

    public function run()
    {
        $args = $this->getArguments();
        $inj = $this->getInjectables();
        $invalidInjectable = $this->getInjectable('age');
        $invalidArgument = $this->getArgument(2);
        $this->outputInfo('Arguments: ' . count($args));
        $this->outputInfo('Argument 1: ' . $this->getArgument(1));
        $this->outputInfo('Argument invalid: ' . strlen($invalidArgument));
        $this->outputInfo('Injectables: ' . count($inj));
        $this->outputInfo('Injectable name: ' . $this->getInjectable('name'));
        $this->outputInfo('Injectable invalid: ' . strlen($invalidInjectable));
        $this->outputInfo($this->getCommand());
    }
}
