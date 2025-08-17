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
use Phalcon\Logger\Logger;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetAdaptersTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerGetAdapters(): void
    {
        $fileName1  = $this->getNewFileName('log', 'log');
        $fileName2  = $this->getNewFileName('log', 'log');
        $outputPath1 = logsDir($fileName1);
        $outputPath2 = logsDir($fileName1);
        $adapter1   = new Stream($outputPath1);
        $adapter2   = new Stream($outputPath2);

        $logger = new Logger(
            'my-logger',
            [
                'one' => $adapter1,
                'two' => $adapter2,
            ]
        );

        $adapters = $logger->getAdapters();

        $this->assertCount(2, $adapters);

        $this->assertInstanceOf(Stream::class, $adapters['one']);
        $this->assertInstanceOf(Stream::class, $adapters['two']);

        $this->safeDeleteFile($outputPath1);
        $this->safeDeleteFile($outputPath2);
    }
}
