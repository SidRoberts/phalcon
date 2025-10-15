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
use Phalcon\Session\Adapter\Redis;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Exception;
use Phalcon\Tests\AbstractServicesTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;
use Phalcon\Tests\Unit\Session\Fake\FakeStreamGlob;

use function cacheDir;
use function file_put_contents;
use function getOptionsSessionStream;
use function sleep;
use function uniqid;

final class GcTest extends AbstractServicesTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterLibmemcachedGc(): void
    {
        /** @var Libmemcached */
        $adapter = $this->newService('sessionLibmemcached');

        /**
         * Add two session keys
         */
        $this->setMemcachedKey('sess-memc-gc_1', uniqid(), 1);
        $this->setMemcachedKey('sess-memc-gc_2', uniqid(), 1);

        /**
         * Sleep to make sure that the time expired
         */
        sleep(2);

        $this->assertNotFalse(
            $adapter->gc(1)
        );

        $this->doesNotHaveMemcachedKey('sess-memc-gc_1');
        $this->doesNotHaveRedisKey('sess-memc-gc_2');
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterNoopGc(): void
    {
        /** @var Noop */
        $adapter = $this->newService('sessionNoop');

        $this->assertNotFalse(
            $adapter->gc(1)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterRedisGc(): void
    {
        /** @var Redis */
        $adapter = $this->newService('sessionRedis');

        $this->assertNotFalse(
            $adapter->gc(1)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamGc(): void
    {
        /** @var Stream */
        $adapter = $this->newService('sessionStream');

        /**
         * Add two session files
         */
        $this->assertNotFalse(
            file_put_contents(cacheDir('sessions/gc_1'), uniqid())
        );

        $this->assertNotFalse(
            file_put_contents(cacheDir('sessions/gc_2'), uniqid())
        );

        /**
         * Sleep to make sure that the time expired
         */
        sleep(2);

        $this->assertNotFalse(
            $adapter->gc(1)
        );

        $this->assertFileDoesNotExist(cacheDir('sessions/gc_1'));
        $this->assertFileDoesNotExist(cacheDir('sessions/gc_2'));
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamGcGlobThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unexpected gc error');

        $adapter = new FakeStreamGlob(getOptionsSessionStream());

        $actual = $adapter->gc(1);
    }
}
