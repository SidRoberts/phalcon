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
use Phalcon\Tests\AbstractServicesTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;

use function cacheDir;
use function file_put_contents;
use function serialize;
use function uniqid;

final class DestroyTest extends AbstractServicesTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterLibmemcachedDestroy(): void
    {
        /** @var Libmemcached */
        $adapter = $this->newService('sessionLibmemcached');

        $value  = uniqid();
        $key    = 'sess-memc-test1';
        $actual = serialize($value);

        $this->setMemcachedKey($key, $actual, 0);

        $this->hasMemcachedKey($key);

        $this->assertTrue(
            $adapter->destroy('test1')
        );

        $this->doesNotHaveMemcachedKey($key);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterNoopDestroy(): void
    {
        /** @var Noop */
        $adapter = $this->newService('sessionNoop');

        $this->assertTrue(
            $adapter->destroy('test1')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterRedisDestroy(): void
    {
        /** @var Redis */
        $adapter = $this->newService('sessionRedis');

        $value = uniqid();

        $this->setRedisKey(
            'sess-reds-test1',
            serialize($value)
        );

        $this->assertTrue(
            $adapter->destroy('test1')
        );

        $this->doesNotHaveRedisKey('sess-reds-test1');
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testSessionAdapterStreamDestroy(): void
    {
        /** @var Stream */
        $adapter = $this->newService('sessionStream');

        /**
         * Create a file in the session folder
         */
        file_put_contents(
            cacheDir('sessions/test1'),
            uniqid()
        );

        $this->assertTrue(
            $adapter->destroy('test1')
        );

        $this->assertFileDoesNotExist(
            cacheDir('sessions/test1')
        );
    }
}
