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

namespace Phalcon\Tests\Unit\Di\Service;

use Phalcon\Di\Exception;
use Phalcon\Di\Service;
use Phalcon\Di\ServiceInterface;
use Phalcon\Html\Escaper;
use Phalcon\Tests\AbstractUnitTestCase;

final class SetParameterTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceSetParameter(): void
    {
        $service = new Service(
            [
                'className' => Escaper::class,
            ],
            true
        );

        $expected = [
            'className' => Escaper::class,
        ];

        $this->assertSame(
            $expected,
            $service->getDefinition()
        );

        $actual = $service->setParameter(1, ['one']);

        $this->assertInstanceOf(ServiceInterface::class, $actual);

        $this->assertInstanceOf(Service::class, $actual);

        $expected = [
            'className' => Escaper::class,
            'arguments' => [1 => ['one']],
        ];

        $this->assertSame(
            $expected,
            $service->getDefinition()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceSetParameterException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Definition must be an array to update its parameters'
        );

        $service = new Service(Escaper::class);

        $service->setParameter(1, [1]);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceSetParameterWithArguments(): void
    {
        $service = new Service(
            [
                'className' => Escaper::class,
                'arguments' => [
                    0 => ['one'],
                ],
            ],
            true
        );

        $expected = [
            'className' => Escaper::class,
            'arguments' => [
                0 => ['one'],
            ],
        ];

        $this->assertSame(
            $expected,
            $service->getDefinition()
        );

        $service->setParameter(0, ['seven']);

        $expected = [
            'className' => Escaper::class,
            'arguments' => [0 => ['seven']],
        ];

        $this->assertSame(
            $expected,
            $service->getDefinition()
        );
    }
}
