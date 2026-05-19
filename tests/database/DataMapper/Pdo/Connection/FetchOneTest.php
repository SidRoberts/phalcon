<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Database\DataMapper\Pdo\Connection;

use PDO;
use Phalcon\Tests\AbstractDatabaseTestCase;
use Phalcon\Tests\Support\Migrations\InvoicesMigration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

final class FetchOneTest extends AbstractDatabaseTestCase
{
    /**
     * @return array<0: string, 1: array>
     */
    public static function getBindTypes(): array
    {
        return [
            [
                'inv_id = ?',
                [0 => 1],
            ],
            [
                'inv_id = :id',
                ['id' => 1],
            ],
            [
                'inv_status_flag = :status',
                [
                    'status' => true,
                ],
            ],
            [
                'inv_status_flag = :status',
                [
                    'status' => [true, PDO::PARAM_BOOL],
                ],
            ],
            [
                'inv_id = :id AND inv_status_flag IS NOT :status',
                [
                    'id'     => 1,
                    'status' => null,
                ],
            ],
            [
                'inv_id = :id AND inv_status_flag IS NOT :status',
                [
                    'id'     => [1, PDO::PARAM_INT],
                    'status' => [null, PDO::PARAM_NULL],
                ],
            ],
            [
                'inv_title = :title',
                [
                    'title' => 'test-1',
                ],
            ],
        ];
    }

    /**
     * Database Tests Phalcon\DataMapper\Pdo\Connection :: fetchOne()
     *
     * @since 2020-01-25
     */
    #[Group('mysql')]
    public function testDmPdoConnectionFetchOne(): void
    {
        $connection = self::getDataMapperConnection();
        $migration  = new InvoicesMigration(self::getConnection());
        $migration->clear();

        $this->assertSame(
            1,
            $migration->insert(1)
        );

        $all = $connection->fetchOne(
            'select * from co_invoices WHERE inv_id = ?',
            [
                0 => 1,
            ]
        );

        $this->assertIsArray($all);
        $this->assertSame(1, $all['inv_id']);
        $this->assertArrayHasKey('inv_id', $all);
        $this->assertArrayHasKey('inv_cst_id', $all);
        $this->assertArrayHasKey('inv_status_flag', $all);
        $this->assertArrayHasKey('inv_title', $all);
        $this->assertArrayHasKey('inv_total', $all);
        $this->assertArrayHasKey('inv_created_at', $all);
    }

    /**
     * Tests Phalcon\DataMapper\Pdo\Connection :: fetchOne() - bind types
     *
     * @since 2020-01-25
     */
    #[DataProvider('getBindTypes')]
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testDmPdoConnectionFetchOneBindTypes(
        string $where,
        array $params
    ): void {
        $connection = self::getDataMapperConnection();
        $migration  = new InvoicesMigration(self::getConnection());
        $migration->clear();

        $this->assertSame(
            1,
            $migration->insert(1, 1, 1, 'test-1')
        );

        $all = $connection->fetchOne(
            'select * from co_invoices WHERE ' . $where,
            $params
        );

        $this->assertIsArray($all);
        $this->assertSame(1, $all['inv_id']);
    }

    /**
     * Tests Phalcon\DataMapper\Pdo\Connection :: fetchOne() - no result
     *
     * @since  2020-01-25
     */
    #[Group('mysql')]
    public function testDmPdoConnectionFetchOneNoResult(): void
    {
        $connection = self::getDataMapperConnection();
        $migration  = new InvoicesMigration(self::getConnection());
        $migration->clear();

        $this->assertSame(
            1,
            $migration->insert(1)
        );

        $all = $connection->fetchOne(
            'select * from co_invoices WHERE inv_id = ?',
            [
                0 => 7,
            ]
        );

        $this->assertIsArray($all);
        $this->assertEmpty($all);
    }
}
