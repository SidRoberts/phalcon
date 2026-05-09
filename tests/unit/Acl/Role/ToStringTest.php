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

namespace Phalcon\Tests\Unit\Acl\Role;

use Phalcon\Acl\Role;
use Phalcon\Tests\AbstractUnitTestCase;

final class ToStringTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclRoleToString(): void
    {
        $role = new Role('Administrator');

        $this->assertSame(
            'Administrator',
            $role->__toString()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclRoleToStringCast(): void
    {
        $role = new Role('Administrator');

        $this->assertSame(
            'Administrator',
            (string) $role
        );
    }
}
