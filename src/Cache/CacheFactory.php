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

namespace Phalcon\Cache;

use Exception as BaseException;
use Phalcon\Cache\Exception\Exception;
use Phalcon\Config\ConfigInterface;
use Phalcon\Support\Exception as SupportException;
use Phalcon\Support\Traits\ConfigTrait;
use Psr\SimpleCache\CacheInterface;
use Throwable;

/**
 * Creates a new Cache class
 *
 * @phpstan-type TConfig = array{
 *     adapter?: string,
 *     options?: array{
 *         servers?: list<
 *             array{
 *                 host: string,
 *                 port?: int,
 *                 weight?: int
 *             }
 *         >
 *     },
 *     host?: string,
 *     port?: int,
 *     index?: int,
 *     persistent?: bool,
 *     auth?: string,
 *     socket?: string,
 *     defaultSerializer?: string,
 *     lifetime?: int,
 *     serializer?: null,
 *     prefix?: string,
 *     storageDir?: string
 * }
 */
class CacheFactory
{
    use ConfigTrait;

    /**
     * Constructor
     *
     * @param AdapterFactory $adapterFactory
     */
    public function __construct(
        protected AdapterFactory $adapterFactory
    ) {
    }

    /**
     * Factory to create an instance from a Config object
     *
     * @param TConfig|ConfigInterface $config
     *
     * @return CacheInterface
     *
     * @throws BaseException
     * @throws SupportException
     */
    public function load(array | ConfigInterface $config): CacheInterface
    {
        $config = $this->checkConfig($config);
        $this->checkConfigElement($config, 'adapter');

        $name    = $config['adapter'];
        $options = $config['options'] ?? [];

        return $this->newInstance($name, $options);
    }

    /**
     * Constructs a new Cache instance.
     *
     * @param string  $name
     * @param TConfig $options
     *
     * @return CacheInterface
     *
     * @throws BaseException
     */
    public function newInstance(string $name, array $options = []): CacheInterface
    {
        $adapter = $this->adapterFactory->newInstance($name, $options);

        return new Cache($adapter);
    }

    /**
     * Returns the exception class for the factory
     *
     * @return class-string<Throwable>
     */
    protected function getExceptionClass(): string
    {
        return Exception::class;
    }
}
