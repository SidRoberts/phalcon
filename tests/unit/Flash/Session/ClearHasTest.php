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

final class ClearHasTest extends AbstractUnitTestCase
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
    public function testFlashSessionClearHas(): void
    {
        $session = $this->container->getShared('session');

        $session->start();

        $flash = new Session();

        $flash->setDI($this->container);

        $message1 = uniqid('m-');
        $message2 = uniqid('m-');

        $flash->success($message1);
        $flash->error($message2);

        $this->assertTrue(
            $flash->has()
        );

        $this->assertTrue(
            $flash->has('success')
        );

        $this->assertTrue(
            $flash->has('error')
        );

        $this->assertFalse(
            $flash->has('warning')
        );

        $this->assertFalse(
            $flash->has('notice')
        );

        $this->assertSame(
            [$message1],
            $flash->getMessages('success', false)
        );

        $this->assertSame(
            [$message2],
            $flash->getMessages('error', false)
        );

        $flash->clear();

        $this->assertEmpty(
            $flash->getMessages()
        );

        $session->destroy();
    }
}
