<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Filter\Filter;

use Closure;
use Phalcon\Filter\Filter;
use Phalcon\Tests\Support\Service\HelloService;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetSetHasTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testFilterFilterGetSetHasGetSame(): void
    {
        $locator = new Filter(['helloFilter' => HelloService::class]);

        $this->assertTrue(
            $locator->has('helloFilter')
        );

        /** @var object $service */
        $service = $locator->get('helloFilter');

        $this->assertSame(
            'Hello Phalcon [count: 1]',
            $service('Phalcon')
        );

        $this->assertSame(
            'Hello Phalcon [count: 2]',
            $service('Phalcon')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testFilterFilterGetSetHasHas(): void
    {
        $services = [
            'helloFilter' => function (): HelloService {
                return new HelloService();
            },
        ];

        $locator = new Filter($services);

        $this->assertTrue(
            $locator->has('helloFilter')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testFilterFilterGetSetHasSet(): void
    {
        $locator = new Filter();

        $this->assertFalse(
            $locator->has('helloFilter')
        );

        $locator->set(
            'helloFilter',
            function (): HelloService {
                return new HelloService();
            }
        );

        $this->assertTrue(
            $locator->has('helloFilter')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testFilterFilterGetSetHasSetClosure(): void
    {
        $locator = new Filter();

        $this->assertFalse(
            $locator->has('testappend')
        );

        $locator->set(
            'testappend',
            function (string $input): string {
                return $input . 'test';
            }
        );

        $value    = 'cheese';
        $expected = $value . 'test';

        $this->assertSame(
            $expected,
            $locator->sanitize($value, 'testappend')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testFilterFilterLocatorGetSetHasGet(): void
    {
        $services = [
            'helloFilter' => function (): HelloService {
                return new HelloService();
            },
        ];

        $locator = new Filter($services);

        $this->assertTrue(
            $locator->has('helloFilter')
        );

        $this->assertInstanceOf(
            Closure::class,
            $locator->get('helloFilter')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testFilterFilterCall(): void
    {
        $locator = new Filter(
            [
                'trim' => \Phalcon\Filter\Sanitize\Trim::class,
            ]
        );

        $this->assertSame(
            'hello world',
            $locator->trim('  hello world  ')
        );
    }
}
