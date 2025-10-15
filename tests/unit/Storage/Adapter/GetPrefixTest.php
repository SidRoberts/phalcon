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

use function array_merge;
use function getOptionsRedis;
use function getOptionsRedisCluster;
use function outputDir;

final class GetPrefixTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: class-string<AdapterInterface>, 1: array<string, mixed>, 2: string, 3: string}>
     */
    public static function getExamples(): array
    {
        return [
            [
                Apcu::class,
                [
                ],
                'ph-apcu-',
                'apcu',
            ],
            [
                Apcu::class,
                [
                    'prefix' => '',
                ],
                '',
                'apcu',
            ],
            [
                Apcu::class,
                [
                    'prefix' => 'my-prefix',
                ],
                'my-prefix',
                'apcu',
            ],
            [
                Libmemcached::class,
                array_merge(
                    getOptionsLibmemcached(),
                    [
                    ]
                ),
                'ph-memc-',
                'memcached',
            ],
            [
                Libmemcached::class,
                array_merge(
                    getOptionsLibmemcached(),
                    [
                        'prefix' => '',
                    ]
                ),
                '',
                'memcached',
            ],
            [
                Libmemcached::class,
                array_merge(
                    getOptionsLibmemcached(),
                    [
                        'prefix' => 'my-prefix',
                    ]
                ),
                'my-prefix',
                'memcached',
            ],
            [
                Memory::class,
                [
                ],
                'ph-memo-',
                '',
            ],
            [
                Memory::class,
                [
                    'prefix' => '',
                ],
                '',
                '',
            ],
            [
                Memory::class,
                [
                    'prefix' => 'my-prefix',
                ],
                'my-prefix',
                '',
            ],
            [
                Redis::class,
                array_merge(
                    getOptionsRedis(),
                    [
                    ]
                ),
                'ph-reds-',
                'redis',
            ],
            [
                Redis::class,
                array_merge(
                    getOptionsRedis(),
                    [
                        'prefix' => '',
                    ]
                ),
                '',
                'redis',
            ],
            [
                Redis::class,
                array_merge(
                    getOptionsRedis(),
                    [
                        'prefix' => 'my-prefix',
                    ]
                ),
                'my-prefix',
                'redis',
            ],
            [
                RedisCluster::class,
                array_merge(
                    getOptionsRedisCluster(),
                    [
                    ]
                ),
                'ph-redc-',
                'redis',
            ],
            [
                RedisCluster::class,
                array_merge(
                    getOptionsRedisCluster(),
                    [
                        'prefix' => '',
                    ]
                ),
                '',
                'redis',
            ],
            [
                RedisCluster::class,
                array_merge(
                    getOptionsRedisCluster(),
                    [
                        'prefix' => 'my-prefix',
                    ]
                ),
                'my-prefix',
                'redis',
            ],
            [
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                'ph-strm',
                '',
            ],
            [
                Stream::class,
                [
                    'storageDir' => outputDir(),
                    'prefix'     => '',
                ],
                '',
                '',
            ],
            [
                Stream::class,
                [
                    'storageDir' => outputDir(),
                    'prefix'     => 'my-prefix',
                ],
                'my-prefix',
                '',
            ],
            [
                Weak::class,
                [
                ],
                '',
                '',
            ],
            [
                Weak::class,
                [
                    'prefix' => '',
                ],
                '',
                '',
            ],
            [
                Weak::class,
                [
                    'prefix' => 'my-prefix',
                ],
                '',
                '',
            ],
        ];
    }

    /**
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     * @param string                         $expected
     * @param string                         $extension
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testStorageAdapterGetSetPrefix(
        string $class,
        array $options,
        string $expected,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $this->assertSame(
            $expected,
            $adapter->getPrefix()
        );
    }
}
