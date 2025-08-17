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
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function getOptionsLibmemcached;
use function getOptionsRedis;
use function getOptionsRedisCluster;
use function outputDir;
use function uniqid;

final class DecrementTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: string, 1: class-string<AdapterInterface>, 2: array<string, mixed>, 3: string, 4: mixed}>
     */
    public static function getExamples(): array
    {
        return [
            [
                'Apcu',
                Apcu::class,
                [],
                'apcu',
                -1,
            ],
            [
                'Libmemcached',
                Libmemcached::class,
                getOptionsLibmemcached(),
                'memcached',
                false,
            ],
            [
                'Memory',
                Memory::class,
                [],
                '',
                false,
            ],
            [
                'Redis',
                Redis::class,
                getOptionsRedis(),
                'redis',
                -1
            ],
            [
                'RedisCluster',
                RedisCluster::class,
                getOptionsRedisCluster(),
                'redis',
                -1
            ],
            [
                'Stream',
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                '',
                false,
            ],
        ];
    }

    /**
     * @param string                         $className
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     * @param string                         $extension
     * @param mixed                          $unknown
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterDecrement(
        string $className,
        string $class,
        array $options,
        string $extension,
        mixed $unknown
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key = uniqid();

        $this->assertTrue(
            $adapter->set($key, 100)
        );

        $expected = 99;

        $this->assertEquals(
            $expected,
            $adapter->decrement($key)
        );

        $this->assertEquals(
            $expected,
            $adapter->get($key)
        );

        $expected = 90;

        $this->assertEquals(
            $expected,
            $adapter->decrement($key, 9)
        );

        $this->assertEquals(
            $expected,
            $adapter->get($key)
        );

        /**
         * unknown key
         */
        $key = uniqid();

        $this->assertEquals(
            $unknown,
            $adapter->decrement($key)
        );

        if ('Stream' === $className) {
            $this->safeDeleteDirectory(outputDir('ph-strm'));
        }
    }
}
