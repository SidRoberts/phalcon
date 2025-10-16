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

namespace Phalcon\Tests\Unit\Logger\Adapter\Syslog;

use Phalcon\Logger\Adapter\AdapterInterface;
use Phalcon\Logger\Adapter\Syslog;
use Phalcon\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Syslog :: __construct()
     *
     * @since 2025-10-13
     */
    public function testLoggerAdapterSyslogInstanceOfAdapterInterface(): void
    {
        $adapter = new Syslog('');

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    /**
     * @return array<array{0: array{option?: int, facility?: int}, 1: string, 2: int}>
     */
    public static function getExamples(): array
    {
        return [
            [
                [],
                'option',
                LOG_ODELAY,
            ],
            [
                ['option' => LOG_ALERT | LOG_INFO],
                'option',
                LOG_ALERT | LOG_INFO,
            ],
            [
                [],
                'facility',
                LOG_USER,
            ],
            [
                ['facility' => LOG_DAEMON],
                'facility',
                LOG_DAEMON,
            ],
        ];
    }

    /**
     * @param array{option?: int, facility?: int} $options
     * @param string                              $property
     * @param int                                 $expected
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testLoggerAdapterSyslogConstructOptionsCast(
        array $options,
        string $property,
        int $expected
    ): void {
        $streamName = $this->getNewFileName('log', 'log');

        $adapter = new Syslog($streamName, $options);

        $this->assertSame(
            $expected,
            $this->getProtectedProperty($adapter, $property)
        );
    }
}
