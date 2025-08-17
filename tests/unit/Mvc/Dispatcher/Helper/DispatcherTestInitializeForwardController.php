<?php

namespace Phalcon\Tests\Unit\Mvc\Dispatcher\Helper;

use Phalcon\Mvc\Controller;

/**
 * @author Andres Gutierrez <andres@phalcon.io>
 * @author Nikolaos Dimopoulos <nikos@phalcon.io>
 */
class DispatcherTestInitializeForwardController extends Controller
{
    public function afterExecuteRoute(): void
    {
        $this->trace('afterExecuteRoute-method');
    }

    public function beforeExecuteRoute(): void
    {
        $this->trace('beforeExecuteRoute-method');
    }

    public function indexAction(): void
    {
        $this->trace('indexAction');
    }

    public function initialize(): void
    {
        $this->trace('initialize-method');

        $di = $this->getDI();

        $dispatcher = $di->getShared('dispatcher');

        $dispatcher->forward(
            [
                'controller' => 'dispatcher-test-default',
                'action'     => 'index',
            ]
        );
    }

    /**
     * Add tracing information into the current dispatch tracer
     */
    protected function trace($text): void
    {
        $this->getDI()->getShared('dispatcherListener')->trace($text);
    }
}
