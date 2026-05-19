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

final class FetchAffectedTest extends AbstractDatabaseTestCase
{
    /**
     * Database Tests Phalcon\DataMapper\Pdo\Connection :: fetchAffected()
     *
     * @since 2020-01-25
     */
    #[Group('mysql')]
    public function testDmPdoConnectionFetchAffected(): void
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

        $all = $connection->fetchAffected(
            'delete from co_invoices'
        );

        $this->assertSame(4, $all);
    }
}
