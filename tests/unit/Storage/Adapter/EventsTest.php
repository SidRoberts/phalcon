<?php

/**
 * This file is part of the Phalcon Framework.
 * (c) Phalcon Team <team@phalcon.io>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Storage\Adapter;

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
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

        $this->assertInstanceOf(
            Manager::class,
            $adapter->getEventsManager()
        );
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
    public function testStorageAdapterEventsAfterDecrement(
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
            'storage:afterDecrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->decrement('test');
        $adapter->decrement('test');

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
    public function testStorageAdapterEventsAfterDelete(
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
            'storage:afterDelete',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->delete('test');
        $adapter->delete('test');

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
    public function testStorageAdapterEventsAfterGet(
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
            'storage:afterGet',
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
    public function testStorageAdapterEventsAfterHas(
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
            'storage:afterHas',
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
    public function testStorageAdapterEventsAfterIncrement(
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
            'storage:afterIncrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->increment('test');
        $adapter->increment('test');

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
    public function testStorageAdapterEventsAfterSet(
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
            'storage:afterSet',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->set('test', 'test');
        $adapter->set('test', 'test');

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
    public function testStorageAdapterEventsBeforeDecrement(
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
            'storage:beforeDecrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->decrement('test');
        $adapter->decrement('test');

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
    public function testStorageAdapterEventsBeforeDelete(
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
            'storage:beforeDelete',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->delete('test');
        $adapter->delete('test');

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
    public function testStorageAdapterEventsBeforeGet(
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
            'storage:beforeGet',
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
    public function testStorageAdapterEventsBeforeHas(
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
            'storage:beforeHas',
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
    public function testStorageAdapterEventsBeforeIncrement(
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
            'storage:beforeIncrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->increment('test');
        $adapter->increment('test');

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
    public function testStorageAdapterEventsBeforeSet(
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
            'storage:beforeSet',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        $adapter->set('test', 'test');
        $adapter->set('test', 'test');

        $this->assertEquals(2, $counter);
    }
}
