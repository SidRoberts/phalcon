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
use PHPUnit\Framework\Attributes\Group;

final class CreateViewTest extends AbstractDatabaseTestCase
{
    /**
     * @return array<array{0: class-string<DialectInterface>, 1: string}>
     */
    public static function getDialects(): array
    {
        return [
            [
                Mysql::class,
                'CREATE VIEW `schema`.`view` AS DEFINITION-VIEW',
            ],
            [
                Postgresql::class,
                'CREATE VIEW "schema"."view" AS DEFINITION-VIEW',
            ],
            [
                Sqlite::class,
                'CREATE VIEW "schema"."view" AS DEFINITION-VIEW',
            ],
        ];
    }

    /**
     * @return array<array{0: class-string<DialectInterface>}>
     */
    public static function getDialectsException(): array
    {
        return [
            [
                Mysql::class,
            ],
            [
                Postgresql::class,
            ],
            [
                Sqlite::class,
            ],
        ];
    }

    /**
     * Tests Phalcon\Db\Dialect :: createView()
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
    public function testDbDialectCreateView(
        string $dialectClass,
        string $expected
    ): void {
        $dialect = new $dialectClass();

        $definition = [
            'sql' => 'DEFINITION-VIEW',
        ];

        $actual = $dialect->createView('view', $definition, 'schema');

        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Db\Dialect :: createView() - exception on missing sql definition
     *
     * @param class-string<DialectInterface> $dialectClass
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-01-20
     */
    #[DataProvider('getDialectsException')]
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testDbDialectCreateViewException(
        string $dialectClass
    ): void {
        $dialect = new $dialectClass();

        $this->expectException(\Phalcon\Db\Exception::class);
        $this->expectExceptionMessage(
            "The index 'sql' is required in the definition array"
        );

        $definition = [];
        $dialect->createView('view', $definition, 'schema');
    }
}
