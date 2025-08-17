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

use Phalcon\Di\Service;
use Phalcon\Html\Escaper;
use Phalcon\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class IsSharedTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: Service, 1: bool}>
     */
    public static function getExamples(): array
    {
        return [
            [
                new Service(Escaper::class),
                false,
            ],
            [
                new Service(Escaper::class, true),
                true,
            ],
            [
                new Service(Escaper::class, false),
                false,
            ],
        ];
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    #[DataProvider('getExamples')]
    public function testDiServiceIsShared(
        Service $service,
        bool $expected
    ): void {
        $this->assertSame(
            $expected,
            $service->isShared()
        );
    }
}
