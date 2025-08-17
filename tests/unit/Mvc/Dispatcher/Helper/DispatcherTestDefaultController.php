<?php

namespace Phalcon\Tests\Unit\Mvc\Dispatcher\Helper;

use Exception;
use Phalcon\Mvc\Controller;

/**
 * @author Andres Gutierrez <andres@phalcon.io>
 * @author Nikolaos Dimopoulos <nikos@phalcon.io>
 */
class DispatcherTestDefaultController extends Controller
{
    public const RETURN_VALUE_INT    = 5;
    public const RETURN_VALUE_STRING = 'string';

    public function afterExecuteRoute(): void
    {
        $this->trace('afterExecuteRoute-method');
    }

    public function beforeExecuteRoute(): void
    {
        $this->trace('beforeExecuteRoute-method');
    }

    public function exceptionAction(): void
    {
        $this->trace('exceptionAction');

        throw new Exception('An exception occurred.');
    }

    public function forwardExternalAction(): void
    {
        $this->trace('forwardExternalAction');

        $di = $this->getDI();

        $dispatcher = $di->getShared('dispatcher');

        $dispatcher->forward(
            [
                'controller' => 'dispatcher-test-default-two',
                'action'     => 'index',
            ]
        );
    }

    public function forwardLocalAction(): void
    {
        $this->trace('forwardLocalAction');

        $di = $this->getDI();

        $dispatcher = $di->getShared('dispatcher');

        $dispatcher->forward(
            [
                'action' => 'index2',
            ]
        );
    }

    public function index2Action(): void
    {
        $this->trace('index2Action');
    }

    public function indexAction(): void
    {
        $this->trace('indexAction');
    }

    public function initialize(): void
    {
        $this->trace('initialize-method');
    }

    public function multiplyAction($a, $b): int
    {
        $this->trace('multiplyAction');

        return (int)$a * (int)$b;
    }

    public function returnIntAction(): int
    {
        $this->trace('returnIntAction');

        return self::RETURN_VALUE_INT;
    }

    public function returnStringAction(): string
    {
        $this->trace('returnStringAction');

        return self::RETURN_VALUE_STRING;
    }

    /**
     * Add tracing information into the current dispatch tracer
     */
    protected function trace($text): void
    {
        $this->getDI()->getShared('dispatcherListener')->trace($text);
    }
}
