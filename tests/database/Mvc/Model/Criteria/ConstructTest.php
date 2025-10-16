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

namespace Phalcon\Tests\Database\Mvc\Model\Criteria;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Model\CriteriaInterface;
use Phalcon\Tests\AbstractDatabaseTestCase;

final class ConstructTest extends AbstractDatabaseTestCase
{
    /**
     * Tests Phalcon\Mvc\Model\Criteria :: __construct()
     *
     * @since 2025-10-13
     *
     * @group mysql
     */
    public function testMvcModelCriteriaInstanceOfCriteriaInterface(): void
    {
        $criteria = new Criteria();

        $this->assertInstanceOf(CriteriaInterface::class, $criteria);
    }
}
