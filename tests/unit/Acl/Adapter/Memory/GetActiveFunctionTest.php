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

use Closure;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Component;
use Phalcon\Acl\Role;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetActiveFunctionTest extends AbstractUnitTestCase
{
    /**
     * @author Wojciech Slawski <jurigag@gmail.com>
     * @since  2017-01-13
     */
    public function testAclAdapterMemoryGetActiveFunction(): void
    {
        $function = function ($a) {
            return $a;
        };

        $acl = new Memory();

        $acl->addRole(new Role('Guests'));

        $acl->addComponent(
            new Component('Post'),
            ['index', 'update', 'create']
        );

        $acl->allow('Guests', 'Post', 'create', $function);

        $this->assertTrue(
            $acl->isAllowed(
                'Guests',
                'Post',
                'create',
                [
                    'a' => 1,
                ]
            )
        );

        $returnedFunction = $acl->getActiveFunction();

        $this->assertInstanceOf(Closure::class, $returnedFunction);

        $this->assertSame(
            1,
            $acl->getActiveFunctionCustomArgumentsCount()
        );
    }
}
