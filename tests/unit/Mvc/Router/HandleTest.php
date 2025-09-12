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

use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Request;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group;
use Phalcon\Mvc\Router\Route;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;
use Phalcon\Tests\Unit\Mvc\Fake\RouterTrait;
use PHPUnit\Framework\Attributes\DataProvider;

final class HandleTest extends AbstractUnitTestCase
{
    use DiTrait;
    use RouterTrait;

    /**
     * @return array<array{0: string}>
     */
    public static function getUrlsWithColons(): array
    {
        return [
            ['/1:1/test'],
            ['/a:1/test'],
            ['/1:a/test'],
            ['/a:a/test'],
        ];
    }

    /**
     * @return array<array{0: string, 1: string, 2: string, 3: string}>
     */
    public static function groupsProvider(): array
    {
        return [
            [
                '/blog/save',
                'blog',
                'index',
                'save',
            ],
            [
                '/blog/edit/1',
                'blog',
                'index',
                'edit',
            ],
            [
                '/blog/about',
                'blog',
                'about',
                'index',
            ],
        ];
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-10-20
     */
    public function testMvcRouterHandle(): void
    {
        $router = $this->getRouter();

        $router->add(
            '/admin/invoices/list',
            [
                'controller' => 'invoices',
                'action'     => 'list',
            ]
        );

        $router->handle('/admin/invoices/list');

        $this->assertSame(
            'invoices',
            $router->getControllerName()
        );

        $this->assertSame(
            'list',
            $router->getActionName()
        );

        $this->assertSame(
            [],
            $router->getParams()
        );
    }

    #[DataProvider('groupsProvider')]
    public function testMvcRouterHandleGroups(
        string $route,
        string $module,
        string $controller,
        string $action
    ): void {
        Route::reset();

        $router = $this->getRouter(false);

        $blog = new Group(
            [
                'module'     => 'blog',
                'controller' => 'index',
            ]
        );

        $blog->setPrefix('/blog');

        $blog->add(
            '/save',
            [
                'action' => 'save',
            ]
        );

        $blog->add(
            '/edit/{id}',
            [
                'action' => 'edit',
            ]
        );

        $blog->add(
            '/about',
            [
                'controller' => 'about',
                'action'     => 'index',
            ]
        );

        $router->mount($blog);
        $router->handle($route);

        $this->assertTrue(
            $router->wasMatched()
        );

        $this->assertSame(
            $module,
            $router->getModuleName()
        );

        $this->assertSame(
            $controller,
            $router->getControllerName()
        );

        $this->assertSame(
            $action,
            $router->getActionName()
        );

        $this->assertSame(
            $blog,
            $router->getMatchedRoute()->getGroup()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-10-17
     */
    public function testMvcRouterHandleNumeric(): void
    {
        $router = $this->getRouter();

        $router->handle('/12/34/56');

        $this->assertSame(
            '',
            $router->getModuleName()
        );

        $this->assertSame(
            '',
            $router->getNamespaceName()
        );

        $this->assertSame(
            '12',
            $router->getControllerName()
        );

        $this->assertSame(
            '34',
            $router->getActionName()
        );

        $this->assertSame(
            ['56'],
            $router->getParams()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-10-20
     */
    public function testMvcRouterHandleShortSyntax(): void
    {
        $router = $this->getRouter(false);

        $router->add("/about", "About::content");

        $router->handle('/about');

        $this->assertSame(
            '',
            $router->getModuleName()
        );

        $this->assertSame(
            '',
            $router->getNamespaceName()
        );

        $this->assertSame(
            'About',
            $router->getControllerName()
        );

        $this->assertSame(
            'content',
            $router->getActionName()
        );

        $this->assertSame(
            [],
            $router->getParams()
        );

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $container = new FactoryDefault();

        $container->set('request', new Request());

        $router = new Router(false);

        $router->setDI($container);

        $router->add(
            "/about",
            "About::content",
            ["GET"]
        );

        $router->handle('/about');

        $this->assertNull(
            $router->getMatchedRoute()
        );

        $this->assertSame(
            '',
            $router->getControllerName()
        );

        $this->assertSame(
            '',
            $router->getActionName()
        );

        $this->assertEmpty(
            $router->getParams()
        );

        $router->add(
            "/about",
            "About::content",
            ["POST"],
            Router::POSITION_FIRST
        );

        $router->handle('/about');

        $this->assertSame(
            '',
            $router->getModuleName()
        );

        $this->assertSame(
            '',
            $router->getNamespaceName()
        );

        $this->assertSame(
            'About',
            $router->getControllerName()
        );

        $this->assertSame(
            'content',
            $router->getActionName()
        );

        $this->assertSame(
            [],
            $router->getParams()
        );
    }

    /**
     * @issue 16741
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-04-04
     */
    #[DataProvider('getUrlsWithColons')]
    public function testMvcRouterHandleWithColons(string $url): void
    {
        $this->setNewFactoryDefault();

        $router = new Router(false);

        $router->setDI($this->container);

        // Simple catch-all route
        $router->add(
            '/{param:.+}',
            [
                'controller' => 'index',
                'action'     => 'test',
            ]
        );

        // Explicitly set request method (for CLI testing)
        $_SERVER["REQUEST_METHOD"] = "GET";

        $router->handle($url);

        $route = $router->getMatchedRoute();

        $this->assertInstanceOf(Route::class, $route);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-10-20
     */
    public function testMvcRouterHandleWithPlaceholders(): void
    {
        /**
         * Regular placeholders
         */
        $router = $this->getRouter(false);

        $router->add(
            '/:module/:namespace/:controller/:action/:params/:int',
            [
                'module'     => 1,
                'namespace'  => 2,
                'controller' => 3,
                'action'     => 4,
                'params'     => 5,
                'my-number'  => 6,
            ]
        );

        $router->handle('/admin/private/businesses/list/my/123');

        $this->assertSame(
            'admin',
            $router->getModuleName()
        );

        $this->assertSame(
            'private',
            $router->getNamespaceName()
        );

        $this->assertSame(
            'businesses',
            $router->getControllerName()
        );

        $this->assertSame(
            'list',
            $router->getActionName()
        );

        $expected = [
            'my',
            'my-number' => '123',
        ];

        $this->assertSame(
            $expected,
            $router->getParams()
        );

        /**
         * Parameters
         */
        $router->add(
            '/admin/{year}/{month}/{day}/{invoiceNo:[0-9]+}',
            [
                'controller' => 'invoices',
                'action'     => 'view',
            ]
        );

        $router->handle('/admin/2020/october/21/456');

        $this->assertSame(
            '',
            $router->getModuleName()
        );

        $this->assertSame(
            '',
            $router->getNamespaceName()
        );

        $this->assertSame(
            'invoices',
            $router->getControllerName()
        );

        $this->assertSame(
            'view',
            $router->getActionName()
        );

        $expected = [
            'year'      => '2020',
            'month'     => 'october',
            'day'       => '21',
            'invoiceNo' => '456',
        ];

        $this->assertSame(
            $expected,
            $router->getParams()
        );

        /**
         * Named parameters
         */
        $router->add(
            '/admin/([0-9]{4})/([0-9]{2})/([0-9]{2})/:params',
            [
                'controller' => 'history',
                'action'     => 'search',
                'year'       => 1, // ([0-9]{4})
                'month'      => 2, // ([0-9]{2})
                'day'        => 3, // ([0-9]{2})
                'params'     => 4, // :params
            ]
        );

        $router->handle('/admin/2020/10/21/456');

        $this->assertSame(
            '',
            $router->getModuleName()
        );

        $this->assertSame(
            '',
            $router->getNamespaceName()
        );

        $this->assertSame(
            'history',
            $router->getControllerName()
        );

        $this->assertSame(
            'search',
            $router->getActionName()
        );

        $expected = [
            '456',
            'year'  => '2020',
            'month' => '10',
            'day'   => '21',
        ];

        $this->assertSame(
            $expected,
            $router->getParams()
        );
    }

    /**
     * Tests that a route registered for POST is not matched on a GET request.
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-05-13
     */
    public function testMvcRouterHandleDoesNotMatchWrongMethod(): void
    {
        Route::reset();

        $router = $this->getRouter(false);
        $router->addPost('/submit', ['controller' => 'form', 'action' => 'submit']);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router->handle('/submit');

        $this->assertFalse($router->wasMatched());
        $this->assertSame('', $router->getControllerName());
        $this->assertSame('', $router->getActionName());
    }

    /**
     * Tests that unconstrained routes match any HTTP method.
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-05-13
     */
    public function testMvcRouterHandleMatchesUnconstrainedRouteOnAnyMethod(): void
    {
        foreach (['GET', 'POST', 'PUT', 'DELETE', 'PATCH'] as $method) {
            Route::reset();

            $router = $this->getRouter(false);
            $router->add('/info', ['controller' => 'info', 'action' => 'index']);

            $_SERVER['REQUEST_METHOD'] = $method;
            $router->handle('/info');

            $this->assertTrue(
                $router->wasMatched(),
                "Expected unconstrained route to match on {$method}"
            );
            $this->assertSame('info', $router->getControllerName());
        }
    }

    /**
     * Tests that the last-registered matching route wins (reverse iteration preserved).
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-05-13
     */
    public function testMvcRouterHandleLastRouteWinsForSamePattern(): void
    {
        Route::reset();

        $router = $this->getRouter(false);
        $router->addGet('/page', ['controller' => 'first',  'action' => 'index']);
        $router->addGet('/page', ['controller' => 'second', 'action' => 'index']);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router->handle('/page');

        $this->assertTrue($router->wasMatched());
        $this->assertSame('second', $router->getControllerName());
    }
}
