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

namespace Phalcon\Tests\Unit\Acl\Adapter\Memory;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Exception;
use Phalcon\Acl\Role;
use Phalcon\Tests\AbstractUnitTestCase;

final class AddRoleTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryAddRoleException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Role must be either a string or implement RoleInterface'
        );

        $acl = new Memory();

        $acl->addRole(true);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryAddRoleNumericKey(): void
    {
        $acl = new Memory();

        $this->assertTrue(
            $acl->addRole('11')
        );

        $this->assertTrue(
            $acl->isRole('11')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryAddRoleObject(): void
    {
        $acl  = new Memory();
        $role = new Role('Administrators', 'Super User access');

        $this->assertTrue(
            $acl->addRole($role)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryAddRoleString(): void
    {
        $acl = new Memory();

        $this->assertTrue(
            $acl->addRole('Administrators')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryAddRoleTwiceObject(): void
    {
        $acl  = new Memory();
        $role = new Role('Administrators', 'Super User access');

        $this->assertTrue(
            $acl->addRole($role)
        );

        $this->assertFalse(
            $acl->addRole($role)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryAddRoleTwiceString(): void
    {
        $acl = new Memory();

        $this->assertTrue(
            $acl->addRole('Administrators')
        );

        $this->assertFalse(
            $acl->addRole('Administrators')
        );
    }
}
