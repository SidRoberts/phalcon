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
use Phalcon\Acl\Role;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetInheritedRolesTest extends AbstractUnitTestCase
{
    /**
     * @issue https://github.com/phalcon/cphalcon/issues/15154
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-10-02
     */
    public function testAclAdapterMemoryGetInheritedRoles(): void
    {
        $acl = new Memory();

        $acl->addRole(new Role('administrator'));
        $acl->addRole(new Role('memberOne'));
        $acl->addRole(new Role('memberTwo'));
        $acl->addRole(new Role('guestOne'));
        $acl->addRole(new Role('guestTwo'));

        $this->assertTrue(
            $acl->addInherit('administrator', 'memberOne')
        );

        $this->assertTrue(
            $acl->addInherit('administrator', 'memberTwo')
        );

        $this->assertTrue(
            $acl->addInherit('memberTwo', 'guestOne')
        );

        $this->assertTrue(
            $acl->addInherit('memberTwo', 'guestTwo')
        );

        $expected = [];

        $this->assertSame(
            $expected,
            $acl->getInheritedRoles('unknown')
        );

        $expected = [
            'memberOne',
            'memberTwo',
        ];

        $this->assertSame(
            $expected,
            $acl->getInheritedRoles('administrator')
        );

        $expected = [
            'administrator' => [
                'memberOne',
                'memberTwo',
            ],
            'memberTwo'     => [
                'guestOne',
                'guestTwo',
            ],
        ];

        $this->assertSame(
            $expected,
            $acl->getInheritedRoles()
        );
    }
}
