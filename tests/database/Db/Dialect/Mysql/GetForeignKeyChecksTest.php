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

namespace Phalcon\Tests\Database\Db\Dialect\Mysql;

use Phalcon\Db\Dialect\Mysql;
use Phalcon\Tests\AbstractDatabaseTestCase;
use PHPUnit\Framework\Attributes\Group;

final class GetForeignKeyChecksTest extends AbstractDatabaseTestCase
{
    /**
     * Tests Phalcon\Db\Dialect\Mysql :: getForeignKeyChecks()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-01-20
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testDbDialectMysqlGetForeignKeyChecks(): void
    {
        $dialect = new Mysql();

        $this->assertSame(
            'SELECT @@foreign_key_checks',
            $dialect->getForeignKeyChecks()
        );
    }
}
