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
     * @return array<array{0: class-string<AdapterInterface>, 1: array<string, mixed>, 2: string, 3: mixed}>
     */
    public static function getExamples(): array
    {
        return [
            [
                Apcu::class,
                [],
                'apcu',
                -1,
            ],
            [
                Libmemcached::class,
                getOptionsLibmemcached(),
                'memcached',
                false,
            ],
            [
                Memory::class,
                [],
                '',
                false,
            ],
            [
                Redis::class,
                getOptionsRedis(),
                'redis',
                -1
            ],
            [
                RedisCluster::class,
                getOptionsRedisCluster(),
                'redis',
                -1
            ],
            [
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
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     * @param string                         $extension
     * @param mixed                          $unknown
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testStorageAdapterDecrement(
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

        $this->assertEquals(
            99,
            $adapter->decrement($key)
        );

        $this->assertEquals(
            99,
            $adapter->get($key)
        );

        $this->assertEquals(
            90,
            $adapter->decrement($key, 9)
        );

        $this->assertEquals(
            90,
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

        if (Stream::class === $class) {
            $this->safeDeleteDirectory(outputDir('ph-strm'));
        }
    }
}
