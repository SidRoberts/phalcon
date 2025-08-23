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

namespace Phalcon\Annotations;

use Exception as BaseException;
use Phalcon\Annotations\Adapter\AdapterInterface;
use Phalcon\Annotations\Adapter\Apcu;
use Phalcon\Annotations\Adapter\Libmemcached;
use Phalcon\Annotations\Adapter\Memory;
use Phalcon\Annotations\Adapter\Redis;
use Phalcon\Annotations\Adapter\Stream;
use Phalcon\Annotations\Adapter\Weak;
use Phalcon\Annotations\Parser\Exception;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Traits\Factory\FactoryTrait;
use Throwable;

/**
 * Factory to create Annotations adapters
 */
class AdapterFactory
{
    use FactoryTrait;

    /**
     * @var SerializerFactory
     */
    private SerializerFactory $serializerFactory;

    /**
     * AdapterFactory constructor.
     *
     * @param SerializerFactory                             $factory
     * @param array<string, class-string<AdapterInterface>> $services
     */
    public function __construct(
        SerializerFactory $factory,
        array $services = []
    ) {
        $this->serializerFactory = $factory;

        $this->init($services);
    }

    /**
     * Create a new instance of the adapter
     *
     * @param string               $name
     * @param array<string, mixed> $options = [
     *                                      'servers' => [
     *                                      [
     *                                      'host'   => 'localhost',
     *                                      'port'   => 11211,
     *                                      'weight' => 1,
     *                                      ]
     *                                      ],
     *                                      'host'              => '127.0.0.1',
     *                                      'port'              => 6379,
     *                                      'index'             => 0,
     *                                      'persistent'        => false,
     *                                      'auth'              => '',
     *                                      'socket'            => '',
     *                                      'defaultSerializer' => 'Php',
     *                                      'lifetime'          => 3600,
     *                                      'serializer'        => null,
     *                                      'prefix'            => 'phalcon',
     *                                      'storageDir'        => ''
     *                                      ]
     *
     * @return AdapterInterface
     *
     * @throws BaseException
     */
    public function newInstance(string $name, array $options = []): AdapterInterface
    {
        /** @var class-string<AdapterInterface> */
        $definition = $this->getService($name);

        return new $definition(
            $this->serializerFactory,
            $options
        );
    }

    /**
     * @return class-string<Throwable>
     */
    protected function getExceptionClass(): string
    {
        return Exception::class;
    }

    /**
     * Returns the available adapters
     *
     * @return array<string, class-string<AdapterInterface>>
     */
    protected function getServices(): array
    {
        return [
            "apcu"         => Apcu::class,
            "libmemcached" => Libmemcached::class,
            "memory"       => Memory::class,
            "redis"        => Redis::class,
            "stream"       => Stream::class,
            "weak"         => Weak::class,
        ];
    }
}
