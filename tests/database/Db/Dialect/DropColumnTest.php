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

namespace Phalcon\Tests\Database\Db\Dialect;

use Phalcon\Db\Dialect\Mysql;
use Phalcon\Db\Dialect\Postgresql;
use Phalcon\Db\Dialect\Sqlite;
use Phalcon\Tests\AbstractDatabaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

final class DropColumnTest extends AbstractDatabaseTestCase
{
    /**
     * @return array<array{0: class-string<DialectInterface>, 1: string}>
     */
    public static function getDialects(): array
    {
        return [
            [
                Mysql::class,
                'ALTER TABLE `schema`.`table` DROP COLUMN `column`',

            ],
            [
                Postgresql::class,
                'ALTER TABLE "schema"."table" DROP COLUMN "column"',
            ],
            [
                Sqlite::class,
                'ALTER TABLE "schema"."table" DROP COLUMN "column"',
            ],
        ];
    }

    /**
     * Tests Phalcon\Db\Dialect :: dropColumn()
     *
     * @param class-string<DialectInterface> $dialectClass
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-01-20
     */
    #[DataProvider('getDialects')]
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testDbDialectDropColumn(
        string $dialectClass,
        string $expected
    ): void {
        $dialect = new $dialectClass();

        $this->assertSame(
            $expected,
            $dialect->dropColumn('table', 'schema', 'column')
        );
    }
}
