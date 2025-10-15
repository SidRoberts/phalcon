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
use Phalcon\Storage\Exception as StorageException;
use Phalcon\Storage\Serializer\SerializerInterface;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Storage\Fake\FakeStreamFileGetContents;
use Phalcon\Tests\Unit\Storage\Fake\FakeStreamFopen;
use PHPUnit\Framework\Attributes\DataProvider;
use stdClass;

use function getOptionsLibmemcached;
use function getOptionsRedis;
use function getOptionsRedisCluster;
use function outputDir;
use function uniqid;

final class HasTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: class-string<AdapterInterface>, 1: array<string, mixed>, 2: ?string}>
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
     * @param ?string                        $extension
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testStorageAdapterHas(
        string $class,
        array $options,
        ?string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key = uniqid();

        $this->assertFalse(
            $adapter->has($key)
        );

        $adapter->set($key, 'test');

        $this->assertTrue(
            $adapter->has($key)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterStreamHasCannotOpenFile(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new FakeStreamFopen(
            $serializer,
            [
                'storageDir' => outputDir(),
            ],
        );

        $key = uniqid();

        $this->assertTrue(
            $adapter->set($key, 'test')
        );

        $this->assertFalse(
            $adapter->has($key)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterStreamHasEmptyPayload(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new FakeStreamFileGetContents(
            $serializer,
            [
                'storageDir' => outputDir(),
            ],
        );

        $key = uniqid();

        $this->assertTrue(
            $adapter->set($key, 'test')
        );

        $this->assertFalse(
            $adapter->has($key)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-07-17
     */
    public function testStorageAdapterWeakHas(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Weak($serializer);

        $obj1 = new stdClass();

        $key1 = uniqid();

        $this->assertFalse(
            $adapter->has($key1)
        );

        $adapter->set($key1, $obj1);

        $this->assertTrue(
            $adapter->has($key1)
        );
    }
}
