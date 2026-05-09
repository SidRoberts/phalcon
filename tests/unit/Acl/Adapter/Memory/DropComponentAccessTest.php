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
use Phalcon\Acl\Enum;
use Phalcon\Tests\AbstractUnitTestCase;

final class DropComponentAccessTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryDropComponentAccess(): void
    {
        $acl = new Memory();

        $acl->setDefaultAction(Enum::DENY);

        $acl->addRole('Guests');

        $acl->addComponent(
            'Post',
            [
                'index',
                'create',
                'delete',
                'update',
            ]
        );
        $acl->addComponent(
            'News',
            [
                'index',
                'create',
                'delete',
                'update',
            ]
        );

        $acl->allow(
            'Guests',
            'Post',
            [
                'index',
                'create',
                'delete',
                'update',
            ]
        );
        $acl->allow(
            'Guests',
            'News',
            [
                'index',
                'create',
                'delete',
                'update',
            ]
        );

        $this->assertTrue(
            $acl->isAllowed('Guests', 'Post', 'update')
        );

        $this->assertTrue(
            $acl->isAllowed('Guests', 'News', 'index')
        );

        $acl->dropComponentAccess('Post', 'index');

        $this->assertTrue(
            $acl->isAllowed('Guests', 'Post', 'index')
        );

        $acl->dropComponentAccess(
            'News',
            [
                'index',
                'create',
            ]
        );

        $this->assertTrue(
            $acl->isAllowed('Guests', 'News', 'index')
        );

        $this->assertTrue(
            $acl->isAllowed('Guests', 'News', 'create')
        );
    }
}
