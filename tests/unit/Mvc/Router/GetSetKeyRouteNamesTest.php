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

namespace Phalcon\Tests\Unit\Mvc\Router;

use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Mvc\Fake\RouterTrait;

final class GetSetKeyRouteNamesTest extends AbstractUnitTestCase
{
    use RouterTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testMvcRouterGetKeyRouteNames(): void
    {
        $router = $this->getRouter(false);

        $usersFind = $router
            ->add('/api/users/find')
            ->setHttpMethods('GET')
            ->setName('usersFind')
        ;
        $usersAdd  = $router
            ->add('/api/users/add')
            ->setHttpMethods('POST')
            ->setName('usersAdd')
        ;

        $this->assertSame(
            $usersAdd,
            $router->getRouteByName('usersAdd')
        );

        // second check when the same route goes from name lookup
        $expected = [
            'usersFind' => 0,
            'usersAdd'  => 1,
        ];

        $this->assertSame(
            $expected,
            $router->getKeyRouteNames()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-11-07
     */
    public function testMvcRouterGetSetKeyRouteNames(): void
    {
        $router = $this->getRouter(false);

        $usersFind = $router
            ->add('/api/users/find')
            ->setHttpMethods('GET')
            ->setName('usersFind')
        ;
        $usersAdd  = $router
            ->add('/api/users/add')
            ->setHttpMethods('POST')
            ->setName('usersAdd')
        ;

        $this->assertFalse(
            $router->getRouteByName('unknown')
        );

        $this->assertSame(
            $usersAdd,
            $router->getRouteByName('usersAdd')
        );

        // second check when the same route goes from name lookup
        $expected = [
            'usersFind' => 0,
            'usersAdd'  => 1,
        ];

        $this->assertSame(
            $expected,
            $router->getKeyRouteNames()
        );

        $names = [
            'usersAdd'  => 0,
            'usersFind' => 1,
        ];
        $router->setKeyRouteNames($names);

        $this->assertSame(
            $names,
            $router->getKeyRouteNames()
        );
    }
}
