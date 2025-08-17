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

namespace Phalcon\Tests\Unit\Di\FactoryDefault\Cli;

use Phalcon\Di\Exception;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Di\Fake\CliTrait;
use PHPUnit\Framework\Attributes\DataProvider;

final class ConstructTest extends AbstractUnitTestCase
{
    use CliTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiFactoryDefaultCliConstruct(): void
    {
        $container = new Cli();
        $services  = $this->getServices();

        $expected = count($services);
        $actual   = count($container->getServices());
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param class-string $class
     *
     * @throws Exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    #[DataProvider('getServices')]
    public function testDiFactoryDefaultCliConstructServices(
        string $service,
        string $class
    ): void {
        $container = new Cli();

        if ('sessionBag' === $service) {
            $params = ['someName'];
        } else {
            $params = null;
        }

        $this->assertInstanceOf(
            $class,
            $container->get($service, $params)
        );
    }
}
