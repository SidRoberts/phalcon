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
use Phalcon\Acl\Component;
use Phalcon\Tests\AbstractUnitTestCase;

final class AddComponentTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryAddComponentNumericKey(): void
    {
        $acl       = new Memory();
        $component = new Component('11', 'Customer component');

        $this->assertTrue(
            $acl->addComponent($component, ['index'])
        );

        $this->assertTrue(
            $acl->isComponent('11')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryAddComponentObject(): void
    {
        $acl       = new Memory();
        $component = new Component('Customer', 'Customer component');

        $this->assertTrue(
            $acl->addComponent($component, ['index'])
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryAddComponentString(): void
    {
        $acl = new Memory();

        $this->assertTrue(
            $acl->addComponent('Customer', ['index'])
        );
    }
}
