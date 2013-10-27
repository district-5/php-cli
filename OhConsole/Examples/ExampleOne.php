<?php
namespace OhConsole\Examples;

use OhConsole\OhCommand;

class ExampleOne extends OhCommand
{
    /**
     * @var string
     */
    protected $command = 'ohconsole-example-one';

    public function run()
    {
        $this->outputInfo('Running Example One');
        $this->outputInfo('--------');
        $this->outputInfo('Single line');
        $this->outputInfo(array('This', 'is', 'an', 'array'));
        $this->outputError('Single error line!');
        $this->outputError(array('This', 'is', 'also', 'an', 'array'));
        $this->outputInfo('--------');
    }
}
