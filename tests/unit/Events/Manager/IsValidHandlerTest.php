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

namespace Phalcon\Tests\Unit\Events\Manager;

use Phalcon\Events\Manager;
use Phalcon\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class IsValidHandlerTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: bool, 1: mixed}>
     */
    public static function getExamples(): array
    {
        $objectHandler  = new Manager();

        $closureHandler = function (): bool {
            return true;
        };

        return [
            [
                false,
                'handler',
            ],
            [
                false,
                134,
            ],
            [
                true,
                $objectHandler,
            ],
            [
                true,
                [$objectHandler, 'hasListeners'],
            ],
            [
                true,
                $closureHandler,
            ],
        ];
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testEventsManagerIsValidHandler(
        bool $expected,
        mixed $handler
    ): void {
        $manager = new Manager();

        $this->assertSame(
            $expected,
            $manager->isValidHandler($handler)
        );
    }
}
