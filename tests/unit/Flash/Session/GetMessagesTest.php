<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Flash\Session;

use Phalcon\Flash\Session;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;

use function uniqid;

final class GetMessagesTest extends AbstractUnitTestCase
{
    use DiTrait;

    public function setUp(): void
    {
        $this->setNewFactoryDefault();
        $this->setDiService('sessionStream');
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testFlashSessionGetMessages(): void
    {
        $session = $this->container->getShared('session');

        $session->start();

        $flash = new Session();

        $flash->setDI($this->container);

        $message1 = uniqid('m-');
        $message2 = uniqid('m-');
        $flash->success($message1);
        $flash->error($message2);

        $expected = [
            'success' => [$message1],
            'error'   => [$message2],
        ];
        $this->assertSame(
            $expected,
            $flash->getMessages()
        );

        $message1 = uniqid('m-');
        $message2 = uniqid('m-');
        $message3 = uniqid('m-');
        $flash->success($message1);
        $flash->error($message2);
        $flash->warning($message3);

        $expected = [$message1];
        $this->assertSame(
            $expected,
            $flash->getMessages('success', false)
        );

        $expected = [$message2];
        $this->assertSame(
            $expected,
            $flash->getMessages('error', false)
        );

        $expected = [$message3];
        $this->assertSame(
            $expected,
            $flash->getMessages('warning', true)
        );

        $expected = [
            'success' => [$message1],
            'error'   => [$message2],
        ];
        $this->assertSame(
            $expected,
            $flash->getMessages()
        );

        $session->destroy();
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2024-01-01
     */
    public function testFlashSessionGetMessagesNonExistentTypeReturnsEmpty(): void
    {
        $session = $this->container->getShared('session');

        $session->start();

        $flash = new Session();

        $flash->setDI($this->container);
        $flash->success('some message');

        // Request a type that has no messages
        $this->assertSame(
            [],
            $flash->getMessages('error')
        );

        $session->destroy();
    }
}
