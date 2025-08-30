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

namespace Phalcon\Di\Traits;

use Phalcon\Di\DiInterface;
use Throwable;

/**
 * This abstract class offers common access to the DI in a class
 *
 * Class AbstractInjectionAware
 *
 * @property object $container
 */
trait InjectionAwareTrait
{
    /**
     * Dependency Injector
     *
     * @var object|null
     */
    protected object | null $container = null;

    /**
     * Returns the internal dependency injector
     *
     * @return DiInterface|null
     */
    public function getDI(): object | null
    {
        return $this->container;
    }

    /**
     * Sets the dependency injector
     *
     * @param DiInterface $container
     *
     * @return void
     */
    public function setDI(object $container): void
    {
        $this->container = $container;
    }

    /**
     * @param class-string<Throwable> $exceptionClass
     * @param string                  $message
     * @param int                     $code
     *
     * @return void
     */
    protected function checkContainer(
        string $exceptionClass,
        string $message,
        int $code = 0
    ): void {
        if (null === $this->container) {
            throw new $exceptionClass(
                'A dependency injection container is required to access '
                . $message,
                $code
            );
        }
    }
}
