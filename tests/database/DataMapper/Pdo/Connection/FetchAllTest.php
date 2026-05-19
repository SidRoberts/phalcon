<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Database\DataMapper\Pdo\Connection;

use Phalcon\DataMapper\Pdo\Connection;
use Phalcon\Tests\AbstractDatabaseTestCase;
use Phalcon\Tests\Support\Migrations\InvoicesMigration;
use PHPUnit\Framework\Attributes\Group;

final class FetchAllTest extends AbstractDatabaseTestCase
{
    /**
     * Database Tests Phalcon\DataMapper\Pdo\Connection :: fetchAll()
     *
     * @since 2020-01-25
     */
    #[Group('mysql')]
    public function testDmPdoConnectionFetchAll(): void
    {
        $connection = self::getDataMapperConnection();
        $migration  = new InvoicesMigration(self::getConnection());
        $migration->clear();

        $this->assertSame(
            1,
            $migration->insert(1)
        );

        $this->assertSame(
            1,
            $migration->insert(2)
        );

        $this->assertSame(
            1,
            $migration->insert(3)
        );

        $this->assertSame(
            1,
            $migration->insert(4)
        );

        $all = $connection->fetchAll(
            'SELECT * from co_invoices'
        );
        $this->assertCount(4, $all);

        $this->assertSame(1, $all[0]['inv_id']);
        $this->assertSame(2, $all[1]['inv_id']);
        $this->assertSame(3, $all[2]['inv_id']);
        $this->assertSame(4, $all[3]['inv_id']);

        $all = $connection->yieldAll(
            'SELECT * from co_invoices'
        );

        $results = [];
        foreach ($all as $key => $item) {
            $results[$key] = $item;
        }
        $this->assertCount(4, $results);

        $this->assertSame(1, $results[0]['inv_id']);
        $this->assertSame(2, $results[1]['inv_id']);
        $this->assertSame(3, $results[2]['inv_id']);
        $this->assertSame(4, $results[3]['inv_id']);
    }
}
