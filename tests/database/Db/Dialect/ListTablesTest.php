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
use Phalcon\Db\DialectInterface;
use Phalcon\Tests\AbstractDatabaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class ListTablesTest extends AbstractDatabaseTestCase
{
    /**
     * @return array<array{0: class-string<DialectInterface>, 1: string}>
     */
    public static function getDialects(): array
    {
        return [
            [
                Mysql::class,
                'SHOW TABLES FROM `schema`',
            ],
            [
                Postgresql::class,
                "SELECT table_name "
                . "FROM information_schema.tables "
                . "WHERE table_schema = 'schema' "
                . "ORDER BY table_name",
            ],
            [
                Sqlite::class,
                "SELECT tbl_name "
                . "FROM sqlite_master "
                . "WHERE type = 'table' "
                . "ORDER BY tbl_name",
            ],
        ];
    }

    /**
     * @return array<array{0: class-string<DialectInterface>, 1: string}>
     */
    public static function getDialectsNoSchema(): array
    {
        return [
            [
                Mysql::class,
                'SHOW TABLES',
            ],
            [
                Postgresql::class,
                "SELECT table_name "
                . "FROM information_schema.tables "
                . "WHERE table_schema = 'public' "
                . "ORDER BY table_name",
            ],
            [
                Sqlite::class,
                "SELECT tbl_name "
                . "FROM sqlite_master "
                . "WHERE type = 'table' "
                . "ORDER BY tbl_name",
            ],
        ];
    }

    /**
     * Tests Phalcon\Db\Dialect :: listTables
     *
     * @param class-string<DialectInterface> $dialectClass
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-01-20
     *
     * @group mysql
     */
    #[DataProvider('getDialects')]
    public function testDbDialectListTables(
        string $dialectClass,
        string $expected
    ): void {
        $dialect = new $dialectClass();

        $actual = $dialect->listTables('schema');

        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Db\Dialect :: listTables
     *
     * @param class-string<DialectInterface> $dialectClass
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-01-20
     *
     * @group mysql
     */
    #[DataProvider('getDialectsNoSchema')]
    public function testDbDialectListTablesNoSchema(
        string $dialectClass,
        string $expected
    ): void {
        $dialect = new $dialectClass();

        $actual = $dialect->listTables();

        $this->assertSame($expected, $actual);
    }
}
