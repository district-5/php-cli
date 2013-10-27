<?php
namespace OhConsole\Examples;

use OhConsole\OhCommand;

class ExampleTwo extends OhCommand
{
    /**
     * @var string
     */
    protected $command = 'ohconsole-example-two';

    public function run()
    {
        $this->outputInfo('Running Example Two');
        $this->outputInfo('--------');
        $this->outputInfo('Single line');
        $this->outputInfo(array('This', 'is', 'an', 'array'));
        $this->outputError('Single error line!');
        $this->outputError(array('This', 'is', 'also', 'an', 'array'));
        $this->outputInfo('--------');
    }
}
