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

use DateInterval;
use Phalcon\Cache\Adapter\AdapterInterface;
use Phalcon\Cache\Adapter\Apcu;
use Phalcon\Cache\Adapter\Libmemcached;
use Phalcon\Cache\Adapter\Memory;
use Phalcon\Cache\Adapter\Redis;
use Phalcon\Cache\Adapter\RedisCluster;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Cache\Adapter\Weak;
use Phalcon\Storage\Exception as StorageException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Cache\Fake\Adapter\Libmemcached as LibmemcachedFixture;
use PHPUnit\Framework\Attributes\DataProvider;

use function getOptionsLibmemcached;
use function getOptionsRedis;
use function getOptionsRedisCluster;
use function outputDir;

final class ConstructTest extends AbstractUnitTestCase
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
            [
                Weak::class,
                [],
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
    public function testCacheAdapterConstruct(
        string $class,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $this->assertInstanceOf($class, $adapter);
        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterLibmemcachedConstructEmptyOptions(): void
    {
        $this->checkExtensionIsLoaded('memcached');

        $serializer = new SerializerFactory();
        $adapter    = new LibmemcachedFixture($serializer);

        $expected = [
            'servers' => [
                0 => [
                    'host'   => '127.0.0.1',
                    'port'   => 11211,
                    'weight' => 1,
                ],
            ],
        ];

        $this->assertSame(
            $expected,
            $adapter->getOptions()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterLibmemcachedConstructGetTtl(): void
    {
        $this->checkExtensionIsLoaded('memcached');

        $serializer = new SerializerFactory();
        $adapter    = new LibmemcachedFixture(
            $serializer,
            getOptionsLibmemcached()
        );

        $this->assertSame(
            3600,
            $adapter->getTtl(null)
        );

        $this->assertSame(
            20,
            $adapter->getTtl(20)
        );

        $time = new DateInterval('PT5S');

        $this->assertSame(
            5,
            $adapter->getTtl($time)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterStreamConstructException(): void
    {
        $this->expectException(StorageException::class);
        $this->expectExceptionMessage(
            "The 'storageDir' must be specified in the options"
        );

        $serializer = new SerializerFactory();
        (new Stream($serializer));
    }
}
