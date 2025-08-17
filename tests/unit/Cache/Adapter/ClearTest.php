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

namespace Phalcon\Tests\Unit\Cache\Adapter;

use Phalcon\Cache\Adapter\AdapterInterface;
use Phalcon\Cache\Adapter\Apcu;
use Phalcon\Cache\Adapter\Libmemcached;
use Phalcon\Cache\Adapter\Memory;
use Phalcon\Cache\Adapter\Redis;
use Phalcon\Cache\Adapter\RedisCluster;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Cache\Adapter\Weak;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Cache\Fake\Adapter\FakeApcuApcuDelete;
use Phalcon\Tests\Unit\Cache\Fake\Adapter\FakeStreamUnlink;
use PHPUnit\Framework\Attributes\DataProvider;
use stdClass;

use function getOptionsLibmemcached;
use function getOptionsRedis;
use function getOptionsRedisCluster;
use function outputDir;
use function uniqid;

final class ClearTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: class-string<AdapterInterface>, 1: array<string, mixed>, 2: string}>
     */
    public static function getExamples(): array
    {
        return [
            [
                Apcu::class,
                [],
                'apcu',
            ],
            [
                Libmemcached::class,
                getOptionsLibmemcached(),
                'memcached',
            ],
            [
                Memory::class,
                [],
                '',
            ],
            [
                Redis::class,
                getOptionsRedis(),
                'redis',
            ],
            [
                RedisCluster::class,
                getOptionsRedisCluster(),
                'redis',
            ],
            [
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                '',
            ],
        ];
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterApcuClearDeleteError(): void
    {
        $this->checkExtensionIsLoaded('apcu');

        $serializer = new SerializerFactory();
        $adapter    = new FakeApcuApcuDelete($serializer);

        $key1 = uniqid();
        $key2 = uniqid();

        $adapter->set($key1, 'test');

        $this->assertTrue(
            $adapter->has($key1)
        );

        $adapter->set($key2, 'test');

        $this->assertTrue(
            $adapter->has($key2)
        );

        $this->assertFalse(
            $adapter->clear()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterApcuClearIteratorError(): void
    {
        $this->checkExtensionIsLoaded('apcu');

        $serializer = new SerializerFactory();
        $adapter    = new FakeApcuApcuDelete($serializer);

        $key1 = uniqid();
        $key2 = uniqid();

        $adapter->set($key1, 'test');

        $this->assertTrue(
            $adapter->has($key1)
        );

        $adapter->set($key2, 'test');

        $this->assertTrue(
            $adapter->has($key2)
        );

        $this->assertFalse(
            $adapter->clear()
        );
    }

    /**
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     * @param string                         $extension
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterClear(
        string $class,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key1 = uniqid();
        $key2 = uniqid();

        $adapter->set($key1, 'test');

        $this->assertTrue(
            $adapter->has($key1)
        );

        $adapter->set($key2, 'test');

        $this->assertTrue(
            $adapter->has($key2)
        );

        $this->assertTrue(
            $adapter->clear()
        );

        $this->assertFalse(
            $adapter->has($key1)
        );

        $this->assertFalse(
            $adapter->has($key2)
        );

        /**
         * Call clear twice to ensure it returns true
         */
        $this->assertTrue(
            $adapter->clear()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterStreamClearCannotDeleteFile(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new FakeStreamUnlink(
            $serializer,
            [
                'storageDir' => outputDir(),
            ],
        );

        $key1 = uniqid();
        $key2 = uniqid();

        $adapter->set($key1, 'test');

        $this->assertTrue(
            $adapter->has($key1)
        );

        $adapter->set($key2, 'test');

        $this->assertTrue(
            $adapter->has($key2)
        );

        $this->assertFalse(
            $adapter->clear()
        );

        $this->safeDeleteDirectory(outputDir('ph-strm'));
    }

    /**
     * @throws HelperException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-07-17
     */
    public function testCacheAdapterWealClear(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Weak($serializer);

        $obj1     = new stdClass();
        $obj1->id = 1;
        $obj2     = new stdClass();
        $obj2->id = 2;

        $key1     = uniqid();
        $key2     = uniqid();

        $adapter->set($key1, $obj1);
        $adapter->set($key2, $obj2);

        $temp = $adapter->get($key1);
        $this->assertEquals($temp, $adapter->get($key1));
        $this->assertEquals($temp, $obj1);

        $temp = $adapter->get($key2);
        $this->assertEquals($temp, $adapter->get($key2));
        $this->assertEquals($temp, $obj2);

        $this->assertTrue(
            $adapter->clear()
        );

        $this->assertFalse(
            $adapter->has($key1)
        );

        $this->assertFalse(
            $adapter->has($key2)
        );

        $this->assertTrue(
            $adapter->clear()
        );
    }
}
