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

namespace Phalcon\Tests\Unit\Storage\Adapter;

use Phalcon\Storage\Adapter\AdapterInterface;
use Phalcon\Storage\Adapter\Apcu;
use Phalcon\Storage\Adapter\Libmemcached;
use Phalcon\Storage\Adapter\Memory;
use Phalcon\Storage\Adapter\Redis;
use Phalcon\Storage\Adapter\RedisCluster;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\Adapter\Weak;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Storage\Fake\FakeWeakFetching;
use PHPUnit\Framework\Attributes\DataProvider;
use stdClass;

use function getOptionsLibmemcached;
use function getOptionsRedis;
use function getOptionsRedisCluster;
use function outputDir;
use function uniqid;

final class DeleteTest extends AbstractUnitTestCase
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
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     * @param string                         $extension
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testStorageAdapterDelete(
        string $class,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key = uniqid();
        $adapter->set($key, 'test');

        $this->assertTrue(
            $adapter->has($key)
        );

        $this->assertTrue(
            $adapter->delete($key)
        );


        $this->assertFalse(
            $adapter->has($key)
        );

        /**
         * Call clear twice to ensure it returns false
         */
        $this->assertFalse(
            $adapter->delete($key)
        );

        /**
         * Delete unknown
         */
        $key = uniqid();

        $this->assertFalse(
            $adapter->delete($key)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-07-17
     */
    public function testStorageAdapterWeakDelete(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Weak($serializer);

        $obj1     = new stdClass();
        $obj1->id = 1;
        $obj2     = new stdClass();
        $obj2->id = 2;

        $key1 = uniqid();
        $key2 = uniqid();

        $adapter->set($key1, $obj1);
        $adapter->set($key2, $obj2);

        $this->assertTrue(
            $adapter->has($key1)
        );

        $this->assertTrue(
            $adapter->has($key2)
        );

        unset($obj1);
        gc_collect_cycles();

        $this->assertNull(
            $adapter->get($key1)
        );

        $temp = $adapter->get($key2);

        unset($obj2);
        gc_collect_cycles();

        $this->assertEquals(
            $temp,
            $adapter->get($key2)
        );

        unset($temp);

        $this->assertTrue(
            $adapter->delete($key2)
        );

        $this->assertFalse(
            $adapter->delete($key2)
        );

        $key = uniqid();

        $this->assertFalse(
            $adapter->delete($key)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-14
     */
    public function testStorageAdapterWeakDeleteWhileFetching(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new FakeWeakFetching($serializer);

        $key = uniqid();
        $obj = new stdClass();

        $adapter->set($key, $obj);

        $this->assertTrue(
            $adapter->has($key)
        );

        // Simulate an in-flight get by freezing the fetching state
        $adapter->setFetching($key);

        // Delete must be blocked while the key is being fetched
        $this->assertFalse(
            $adapter->delete($key)
        );

        // Key is still present
        $this->assertTrue(
            $adapter->has($key)
        );

        // Release the fetch lock
        $adapter->setFetching(null);

        // Delete should now succeed
        $this->assertTrue(
            $adapter->delete($key)
        );

        $this->assertFalse(
            $adapter->has($key)
        );
    }
}
