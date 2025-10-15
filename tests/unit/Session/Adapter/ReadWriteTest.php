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

namespace Phalcon\Tests\Unit\Session\Adapter;

use Phalcon\Session\Adapter\Libmemcached;
use Phalcon\Session\Adapter\Noop;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Adapter\Redis;
use Phalcon\Tests\AbstractServicesTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;
use Phalcon\Tests\Unit\Session\Fake\Adapter\FakeStreamFileGetContents;

use function cacheDir;
use function getOptionsSessionStream;
use function uniqid;

final class ReadWriteTest extends AbstractServicesTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterLibmemcachedReadWrite(): void
    {
        /** @var Libmemcached */
        $adapter = $this->newService('sessionLibmemcached');

        $value = uniqid();

        $adapter->write('test1', $value);

        $this->assertEquals(
            $value,
            $adapter->read('test1')
        );

        $this->clearMemcached();

        $this->assertNotNull(
            $adapter->read('test1')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterNoopWrite(): void
    {
        $adapter = $this->newService('sessionNoop');

        $this->assertTrue(
            $adapter->write('test1', uniqid())
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterNoopReadWrite(): void
    {
        /** @var Noop */
        $adapter = $this->newService('sessionNoop');
        $value   = uniqid();

        $adapter->write('test1', $value);

        $this->assertEquals(
            '',
            $adapter->read('test1')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterRedisReadWrite(): void
    {
        /** @var Redis */
        $adapter = $this->newService('sessionRedis');
        $value   = uniqid();

        $adapter->write('test1', $value);

        $this->assertEquals(
            $value,
            $adapter->read('test1')
        );

        $this->sendRedisCommand('del', 'sess-reds-test1');

        $this->assertNotNull(
            $adapter->read('test1')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamRead(): void
    {
        /** @var Stream */
        $adapter = $this->newService('sessionStream');

        $value = uniqid();

        $adapter->write('test1', $value);

        $this->assertEquals(
            $value,
            $adapter->read('test1')
        );

        $this->safeDeleteFile(
            cacheDir('sessions/test1')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamReadNoData(): void
    {
        $adapter = new FakeStreamFileGetContents(getOptionsSessionStream());
        $value   = uniqid();

        $adapter->write('test1', $value);

        $this->assertEmpty(
            $adapter->read('test1')
        );

        $this->safeDeleteFile(
            cacheDir('sessions/test1')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamWrite(): void
    {
        /** @var Stream */
        $adapter = $this->newService('sessionStream');

        $value = uniqid();

        $adapter->write('test1', $value);

        $file = cacheDir('sessions/test1');

        $this->assertFileExists($file);

        $this->assertFileContentsContains($file, $value);

        $this->safeDeleteFile($file);
    }
}
