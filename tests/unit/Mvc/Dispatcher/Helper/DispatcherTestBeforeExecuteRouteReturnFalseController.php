<?php

namespace Phalcon\Tests\Unit\Mvc\Dispatcher\Helper;

use Phalcon\Mvc\Controller;

/**
 * @author Andres Gutierrez <andres@phalcon.io>
 * @author Nikolaos Dimopoulos <nikos@phalcon.io>
 */
class DispatcherTestBeforeExecuteRouteReturnFalseController extends Controller
{
    public function beforeExecuteRoute(): bool
    {
        $this->trace('beforeExecuteRoute-method');

        return false;
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
