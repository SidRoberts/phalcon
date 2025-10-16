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

namespace Phalcon\Tests\Unit\Mvc\Micro\Collection;

use Phalcon\Mvc\Micro\Collection;
use Phalcon\Mvc\Micro\CollectionInterface;
use Phalcon\Tests\AbstractUnitTestCase;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Mvc\Micro\Collection :: __construct()
     *
     * @author Sid Roberts <https://github.com/SidRoberts>
     * @since  2025-10-13
     */
    public function testMvcMicroCollectionInstanceOfCollectionInterface(): void
    {
        $collection = new Collection();

        $this->assertInstanceOf(CollectionInterface::class, $collection);
    }
}
