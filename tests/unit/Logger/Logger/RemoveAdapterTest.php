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

namespace Phalcon\Tests\Unit\Logger\Logger;

use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Exception;
use Phalcon\Logger\Logger;
use Phalcon\Tests\AbstractUnitTestCase;

final class RemoveAdapterTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerRemoveAdapter(): void
    {
        $fileName1  = $this->getNewFileName('log', 'log');
        $fileName2  = $this->getNewFileName('log', 'log');
        $outputPath = logsDir();
        $adapter1   = new Stream($outputPath . $fileName1);
        $adapter2   = new Stream($outputPath . $fileName2);

        $logger = new Logger(
            'my-logger',
            [
                'one' => $adapter1,
                'two' => $adapter2,
            ]
        );

        $this->assertCount(
            2,
            $logger->getAdapters()
        );

        $logger->removeAdapter('one');

        $this->assertCount(
            1,
            $logger->getAdapters()
        );

        $this->safeDeleteFile($outputPath . $fileName1);
        $this->safeDeleteFile($outputPath . $fileName2);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerRemoveAdapterUnknown(): void
    {
        $fileName1  = $this->getNewFileName('log', 'log');
        $outputPath = logsDir();

        try {
            $adapter1 = new Stream($outputPath . $fileName1);

            $logger = new Logger(
                'my-logger',
                [
                    'one' => $adapter1,
                ]
            );

            $this->assertCount(
                1,
                $logger->getAdapters()
            );

            $logger->removeAdapter('unknown');
        } catch (Exception $ex) {
            $this->assertSame(
                'Adapter does not exist for this logger: unknown',
                $ex->getMessage()
            );
        }

        $this->safeDeleteFile($outputPath . $fileName1);
    }
}
