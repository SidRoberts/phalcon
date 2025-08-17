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

namespace Phalcon\Tests\Unit\Http\Response;

use Phalcon\Di\Di;
use Phalcon\Http\Response;
use Phalcon\Http\Response\Exception;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetSetDITest extends AbstractUnitTestCase
{
    /**
     * @author Jeremy PASTOURET <https://github.com/jenovateurs>
     * @since  2019-12-07
     */
    public function testHttpResponseGetSetDI(): void
    {
        $container = new Di();
        $response  = new Response();

        $response->setDI($container);

        $this->assertSame(
            $container,
            $response->getDI()
        );

        $this->assertInstanceOf(
            Di::class,
            $response->getDI()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2024-01-01
     */
    public function testHttpResponseGetDIFromDefault(): void
    {
        $container = new Di();

        Di::setDefault($container);

        $response = new Response();

        $this->assertSame(
            $container,
            $response->getDI()
        );

        Di::reset();
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2024-01-01
     */
    public function testHttpResponseGetDINoContainerThrows(): void
    {
        Di::reset();

        $response = new Response();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("A dependency injection container is required");

        $response->getDI();
    }

    /**
     * Tests Phalcon\Http\Response :: getDI() / setDI()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-12
     */
    public function testHttpResponseGetDIWithoutDI(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("A dependency injection container is required to access the 'url' service");

        Di::reset();

        $response = new Response();

        $di = $response->getDI();
    }
}
