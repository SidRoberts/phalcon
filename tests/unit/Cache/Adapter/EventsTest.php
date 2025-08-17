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

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Cache\Adapter\AdapterInterface;
use Phalcon\Cache\Adapter\Apcu;
use Phalcon\Cache\Adapter\Libmemcached;
use Phalcon\Cache\Adapter\Memory;
use Phalcon\Cache\Adapter\Redis;
use Phalcon\Cache\Adapter\RedisCluster;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Cache\Adapter\Weak;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;

use function getOptionsLibmemcached;
use function getOptionsRedis;
use function getOptionsRedisCluster;
use function outputDir;

final class EventsTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: string, 1: class-string<AdapterInterface>, 2: array<string, mixed>}>
     */
    public static function getExamples(): array
    {
        return [
            [
                'apcu',
                Apcu::class,
                [],
            ],
            [
                'memcached',
                Libmemcached::class,
                getOptionsLibmemcached(),
            ],
            [
                '',
                Memory::class,
                [],
            ],
            [
                'redis',
                Redis::class,
                getOptionsRedis(),
            ],
            [
                'redis',
                RedisCluster::class,
                getOptionsRedisCluster(),
            ],
            [
                '',
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
            ],
            [
                '',
                Weak::class,
                [],
            ],
        ];
    }

    /**
     * @return array<array{0: class-string<AdapterInterface>, 1: array<string, mixed>, 2: string}>
     */
    public static function getAdapters(): array
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
                'memcached'
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
     * @param class-string<AdapterInterface> $adapterClass
     * @param array<string, mixed>           $options
     * @param string                         $extension
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getAdapters')]
    public function testCacheAdapterMemoryGetEventsManagerNotSet(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);

        $this->assertNull(
            $adapter->getEventsManager()
        );
    }

    /**
     * @param class-string<AdapterInterface> $adapterClass
     * @param array<string, mixed>           $options
     * @param string                         $extension
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getAdapters')]
    public function testCacheAdapterMemoryGetEventsManagerSet(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);

        $adapter->setEventsManager(new Manager());

        $this->assertInstanceOf(Manager::class, $adapter->getEventsManager());
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsAfterDecrement(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:afterDecrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->decrement('test');
        $adapter->decrement('test');

        $expected = 2;
        $this->assertEquals($expected, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsAfterDelete(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:afterDelete',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->delete('test');
        $adapter->delete('test');

        $expected = 2;
        $this->assertEquals($expected, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsAfterGet(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:afterGet',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->set('test', 'value');

        $adapter->get('test');
        $adapter->get('test');

        $this->assertEquals(2, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsAfterHas(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:afterHas',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->has('test');
        $adapter->has('test');

        $expected = 2;
        $this->assertEquals($expected, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsAfterIncrement(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:afterIncrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->increment('test');
        $adapter->increment('test');

        $expected = 2;
        $this->assertEquals($expected, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsAfterSet(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:afterSet',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->set('test', 'test');
        $adapter->set('test', 'test');

        $expected = 2;
        $this->assertEquals($expected, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsBeforeDecrement(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:beforeDecrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->decrement('test');
        $adapter->decrement('test');

        $expected = 2;
        $this->assertEquals($expected, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsBeforeDelete(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:beforeDelete',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->delete('test');
        $adapter->delete('test');

        $expected = 2;
        $this->assertEquals($expected, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsBeforeGet(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:beforeGet',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->set('test', 'value');

        $adapter->get('test');
        $adapter->get('test');

        $this->assertEquals(2, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsBeforeHas(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:beforeHas',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->has('test');
        $adapter->has('test');

        $this->assertEquals(2, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsBeforeIncrement(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:beforeIncrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->increment('test');
        $adapter->increment('test');

        $expected = 2;
        $this->assertEquals($expected, $counter);
    }

    /**
     * @param string                         $extension
     * @param class-string<AdapterInterface> $class
     * @param array<string, mixed>           $options
     *
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-06-07
     */
    #[DataProvider('getExamples')]
    public function testCacheAdapterEventsBeforeSet(
        string $extension,
        string $class,
        array $options
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'cache:beforeSet',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->set('test', 'test');
        $adapter->set('test', 'test');

        $expected = 2;
        $this->assertEquals($expected, $counter);
    }
}
