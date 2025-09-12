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
use PHPUnit\Framework\Attributes\DataProvider;

final class RemoveExtraSlashesTest extends AbstractUnitTestCase
{
    use RouterTrait;

    /**
     * @return array<array{0: string, 1: array{controller: string, action: string}}>
     */
    public static function getMatchingWithExtraSlashes(): array
    {
        return [
            [
                '/index/',
                [
                    'controller' => 'index',
                    'action'     => '',
                ],
            ],

            [
                '/session/start/',
                [
                    'controller' => 'session',
                    'action'     => 'start',
                ],
            ],

            [
                '/users/edit/100/',
                [
                    'controller' => 'users',
                    'action'     => 'edit',
                ],
            ],
        ];
    }

    /**
     * @param string                                    $route
     * @param array{controller: string, action: string} $params
     *
     * @author Andy Gutierrez <andres.gutierrez@phalcon.io>
     * @since  2012-12-16
     */
    #[DataProvider('getMatchingWithExtraSlashes')]
    public function testRemovingExtraSlashes(
        string $route,
        array $params
    ): void {
        $router = $this->getRouter();

        $router->removeExtraSlashes(true);

        $router->handle($route);

        $this->assertTrue(
            $router->wasMatched()
        );

        $this->assertSame(
            $params['controller'],
            $router->getControllerName()
        );

        $this->assertSame(
            $params['action'],
            $router->getActionName()
        );
    }
}
