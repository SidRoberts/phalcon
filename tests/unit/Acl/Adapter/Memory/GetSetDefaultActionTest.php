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

final class GetSetDefaultActionTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryGetSetDefaultAction(): void
    {
        $acl = new Memory();

        $acl->setDefaultAction(Enum::ALLOW);

        $this->assertSame(
            Enum::ALLOW,
            $acl->getDefaultAction()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryGetSetDefaultActionDefault(): void
    {
        $acl = new Memory();

        $this->assertSame(
            Enum::DENY,
            $acl->getDefaultAction()
        );
    }
}
