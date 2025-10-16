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

namespace Phalcon\Tests\Database\Paginator\Repository;

use Phalcon\Paginator\Repository;
use Phalcon\Paginator\RepositoryInterface;
use Phalcon\Tests\AbstractDatabaseTestCase;

final class ConstructTest extends AbstractDatabaseTestCase
{
    /**
     * Tests Phalcon\Paginator\Repository :: __construct()
     *
     * @since 2025-10-13
     */
    public function testPaginatorRepositoryInstanceOfRepositoryInterface(): void
    {
        $repository = new Repository();

        $this->assertInstanceOf(RepositoryInterface::class, $repository);
    }
}
