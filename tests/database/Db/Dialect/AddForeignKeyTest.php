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
use Phalcon\Db\Exception;
use Phalcon\Db\Reference;
use Phalcon\Tests\AbstractDatabaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

final class AddForeignKeyTest extends AbstractDatabaseTestCase
{
    /**
     * @return array<array{0: class-string<DialectInterface>, 1: string}>
     */
    public static function getDialects(): array
    {
        return [
            [
                Mysql::class,
                'ALTER TABLE `schema`.`table` '
                . 'ADD CONSTRAINT `fk1` FOREIGN KEY (`field_primary`) '
                . 'REFERENCES `ref_schema`.`ref_table`(`field_referenced`)',

            ],
            [
                Postgresql::class,
                'ALTER TABLE "schema"."table" '
                . 'ADD CONSTRAINT "fk1" '
                . 'FOREIGN KEY ("field_primary") '
                . 'REFERENCES "ref_table" ("field_referenced")',
            ],
        ];
    }

    /**
     * @return array<array{0: class-string<DialectInterface>, 1: string}>
     */
    public static function getDialectsOnDelete(): array
    {
        return [
            [
                Mysql::class,
                'ALTER TABLE `schema`.`table` '
                . 'ADD CONSTRAINT `fk1` FOREIGN KEY (`field_primary`) '
                . 'REFERENCES `ref_schema`.`ref_table`(`field_referenced`) '
                . 'ON DELETE delete command',

            ],
            [
                Postgresql::class,
                'ALTER TABLE "schema"."table" '
                . 'ADD CONSTRAINT "fk1" '
                . 'FOREIGN KEY ("field_primary") '
                . 'REFERENCES "ref_table" ("field_referenced") '
                . 'ON DELETE delete command',
            ],
        ];
    }

    /**
     * @return array<array{0: class-string<DialectInterface>, 1: string}>
     */
    public static function getDialectsOnUpdate(): array
    {
        return [
            [
                Mysql::class,
                'ALTER TABLE `schema`.`table` '
                . 'ADD CONSTRAINT `fk1` FOREIGN KEY (`field_primary`) '
                . 'REFERENCES `ref_schema`.`ref_table`(`field_referenced`) '
                . 'ON UPDATE update command',

            ],
            [
                Postgresql::class,
                'ALTER TABLE "schema"."table" '
                . 'ADD CONSTRAINT "fk1" '
                . 'FOREIGN KEY ("field_primary") '
                . 'REFERENCES "ref_table" ("field_referenced") '
                . 'ON UPDATE update command',
            ],
        ];
    }

    /**
     * Tests Phalcon\Db\Dialect :: addForeignKey() - sqlite throws exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-01-20
     */
    #[Group('sqlite')]
    public function testDbDialectAddForeignKeySqlite(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Adding a foreign key constraint to an existing table '
            . 'is not supported by SQLite'
        );

        $dialect = new Sqlite();

        $reference = new Reference('fk1', [
            'referencedSchema'  => 'ref_schema',
            'referencedTable'   => 'ref_table',
            'columns'           => ['field_primary'],
            'referencedColumns' => ['field_referenced'],
        ]);
        $dialect->addForeignKey('table', 'schema', $reference);
    }

    /**
     * @param class-string<DialectInterface> $dialectClass
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-01-20
     */
    #[DataProvider('getDialects')]
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testDbDialectAddForeignKey(
        string $dialectClass,
        string $expected
    ): void {
        $dialect = new $dialectClass();

        $reference = new Reference('fk1', [
            'referencedSchema'  => 'ref_schema',
            'referencedTable'   => 'ref_table',
            'columns'           => ['field_primary'],
            'referencedColumns' => ['field_referenced'],
        ]);

        $this->assertSame(
            $expected,
            $dialect->addForeignKey('table', 'schema', $reference)
        );
    }

    /**
     * @param class-string<DialectInterface> $dialectClass
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-01-20
     */
    #[DataProvider('getDialectsOnDelete')]
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testDbDialectAddForeignKeyOnDelete(
        string $dialectClass,
        string $expected
    ): void {
        $dialect = new $dialectClass();

        $reference = new Reference('fk1', [
            'referencedSchema'  => 'ref_schema',
            'referencedTable'   => 'ref_table',
            'columns'           => ['field_primary'],
            'referencedColumns' => ['field_referenced'],
            'onDelete'          => 'delete command',
        ]);

        $this->assertSame(
            $expected,
            $dialect->addForeignKey('table', 'schema', $reference)
        );
    }

    /**
     * @param class-string<DialectInterface> $dialectClass
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-01-20
     */
    #[DataProvider('getDialectsOnUpdate')]
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testDbDialectAddForeignKeyOnUpdate(
        string $dialectClass,
        string $expected
    ): void {
        $dialect = new $dialectClass();

        $reference = new Reference(
            'fk1',
            [
                'referencedSchema'  => 'ref_schema',
                'referencedTable'   => 'ref_table',
                'columns'           => ['field_primary'],
                'referencedColumns' => ['field_referenced'],
                'onUpdate'          => 'update command',
            ]
        );

        $this->assertSame(
            $expected,
            $dialect->addForeignKey('table', 'schema', $reference)
        );
    }
}
