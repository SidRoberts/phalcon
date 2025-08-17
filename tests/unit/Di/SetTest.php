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

namespace Phalcon\Tests\Unit\Di;

use Phalcon\Di\Di;
use Phalcon\Di\Exception;
use Phalcon\Html\Escaper;
use Phalcon\Support\Collection;
use Phalcon\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class SetTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: string, 1: mixed, 2: class-string}>
     */
    public static function getExamples(): array
    {
        return [
            [
                'escaper',
                Escaper::class,
                Escaper::class,
            ],
            [
                'escaper',
                function () {
                    return new Escaper();
                },
                Escaper::class,
            ],
            [
                'escaper',
                [
                    'className' => Escaper::class,
                ],
                Escaper::class,
            ],
        ];
    }

    /**
     * @param class-string $class
     *
     * @throws Exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    #[DataProvider('getExamples')]
    public function testDiSet(
        string $name,
        mixed $service,
        string $class
    ): void {
        $container = new Di();

        // set non shared service
        $container->set($name, $service);

        $actual = $container->get($name);
        $this->assertInstanceOf($class, $actual);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiSetAlias(): void
    {
        $container = new Di();
        $escaper   = new Escaper();

        $container->set('alias', Escaper::class);
        $container->set(Escaper::class, $escaper);

        $this->assertInstanceOf(
            Escaper::class,
            $container->get('alias')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiSetShared(): void
    {
        $container = new Di();

        // set non shared service
        $container->set('escaper', Escaper::class);

        $this->assertInstanceOf(
            Escaper::class,
            $container->get('escaper')
        );

        $escaper = $container->getService('escaper');

        $this->assertFalse(
            $escaper->isShared()
        );

        // set shared service
        $container->set('collection', Collection::class, true);

        $this->assertInstanceOf(
            Collection::class,
            $container->get('collection')
        );

        $collection = $container->getService('collection');

        $this->assertTrue(
            $collection->isShared()
        );
    }
}
