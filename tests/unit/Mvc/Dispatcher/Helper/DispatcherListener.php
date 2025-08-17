<?php

namespace Phalcon\Tests\Unit\Mvc\Dispatcher\Helper;

use Exception;
use Phalcon\Dispatcher\DispatcherInterface;
use Phalcon\Events\Event;

/**
 * @author Andres Gutierrez <andres@phalcon.io>
 * @author Nikolaos Dimopoulos <nikos@phalcon.io>
 */
class DispatcherListener
{
    /**
     * @var list<string>
     */
    protected array $trace = [];

    public function afterDispatch(Event $event, DispatcherInterface $dispatcher): void
    {
        $this->trace('afterDispatch');
    }

    public function afterDispatchLoop(Event $event, DispatcherInterface $dispatcher): void
    {
        $this->trace('afterDispatchLoop');
    }

    public function afterExecuteRoute(Event $event, DispatcherInterface $dispatcher): void
    {
        $this->trace('afterExecuteRoute');
    }

    public function afterInitialize(Event $event, DispatcherInterface $dispatcher): void
    {
        $this->trace('afterInitialize');
    }

    public function beforeDispatch(Event $event, DispatcherInterface $dispatcher): void
    {
        $this->trace('beforeDispatch');
    }

    public function beforeDispatchLoop(Event $event, DispatcherInterface $dispatcher): void
    {
        $this->trace('beforeDispatchLoop');
    }

    public function beforeException(Event $event, DispatcherInterface $dispatcher, Exception $exception): void
    {
        $this->trace(
            'beforeException: ' . $exception->getMessage()
        );
    }

    public function beforeExecuteRoute(Event $event, DispatcherInterface $dispatcher): void
    {
        $this->trace('beforeExecuteRoute');
    }

    public function beforeNotFoundAction(Event $event, DispatcherInterface $dispatcher): void
    {
        $this->trace('beforeNotFoundAction');
    }

    public function clearTrace(): void
    {
        $this->trace = [];
    }

    public function compare(array $eventTraces): bool
    {
        return $this->trace === $eventTraces;
    }

    /**
     * @return list<string>
     */
    public function getTrace(): array
    {
        return $this->trace;
    }

    public function trace($text): void
    {
        $this->trace[] = $text;
    }
}
