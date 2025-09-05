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

namespace Phalcon\Tests\Unit\Application;

use Phalcon\Application\Exception;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Application\Fake\FakeApplication;

final class GetRegisterModulesTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Application\* :: registerModules()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testApplicationRegisterModules(): void
    {
        $application = new FakeApplication();

        $modules = [
            'admin'    => [1],
            'invoices' => [2],
        ];
        $application->registerModules($modules);

        $this->assertSame(
            $modules,
            $application->getModules()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testApplicationRegisterModulesMerge(): void
    {
        $application = new FakeApplication();

        $modules1 = [
            'admin'    => [1],
            'invoices' => [2],
        ];
        $application->registerModules($modules1);

        $modules2 = [
            'moderator' => [3],
            'posts'     => [4],
        ];
        $application->registerModules($modules2);

        $this->assertSame(
            $modules2,
            $application->getModules()
        );

        $application->registerModules($modules1, true);

        $expected = array_merge($modules2, $modules1);

        $this->assertSame(
            $expected,
            $application->getModules()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testApplicationGetModule(): void
    {
        $application = new FakeApplication();

        $modules = [
            'admin'    => [1],
            'invoices' => [2],
        ];
        $application->registerModules($modules);

        $expected = [1];

        $this->assertSame(
            $expected,
            $application->getModule('admin')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testApplicationGetModuleException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "Module 'no-module' is not registered in the application container"
        );

        $application = new FakeApplication();

        $application->getModule('no-module');
    }
}
