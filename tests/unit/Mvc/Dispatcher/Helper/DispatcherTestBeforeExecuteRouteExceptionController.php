<?php

namespace Phalcon\Tests\Unit\Mvc\Dispatcher\Helper;

use Exception;
use Phalcon\Mvc\Controller;

/**
 * @author Andres Gutierrez <andres@phalcon.io>
 * @author Nikolaos Dimopoulos <nikos@phalcon.io>
 */
class DispatcherTestBeforeExecuteRouteExceptionController extends Controller
{
    public function beforeExecuteRoute(): void
    {
        $this->trace('beforeExecuteRoute-method');

        throw new Exception('beforeExecuteRoute exception occurred');
    }

    public function indexAction(): void
    {
        $this->trace('indexAction');
    }

    public function initialize(): void
    {
        $this->trace('initialize-method');
    }

    /**
     * Add tracing information into the current dispatch tracer
     */
    protected function trace($text): void
    {
        $this->getDI()->getShared('dispatcherListener')->trace($text);
    }
}
