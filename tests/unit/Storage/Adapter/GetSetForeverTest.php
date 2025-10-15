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
use PHPUnit\Framework\Attributes\DataProvider;
use stdClass;

use function getOptionsLibmemcached;
use function getOptionsRedis;
use function getOptionsRedisCluster;
use function outputDir;
use function uniqid;

final class GetSetForeverTest extends AbstractUnitTestCase
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
    public function testStorageAdapterGetSetForever(
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

        $this->assertTrue(
            $adapter->setForever($key, "test")
        );

        sleep(2);

        $this->assertTrue(
            $adapter->has($key)
        );

        /**
         * Delete it
         */
        $this->assertTrue(
            $adapter->delete($key)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-07-17
     */
    public function testStorageAdapterWeakGetSetForever(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Weak($serializer);

        $key = uniqid();
        $obj = new stdClass();

        $this->assertFalse(
            $adapter->setForever($key, "test")
        );

        $this->assertTrue(
            $adapter->setForever($key, $obj)
        );

        sleep(2);

        $this->assertTrue(
            $adapter->has($key)
        );

        /**
         * Delete it
         */
        $this->assertTrue(
            $adapter->delete($key)
        );
    }
}
