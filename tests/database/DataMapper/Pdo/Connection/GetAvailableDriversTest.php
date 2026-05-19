<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Database\DataMapper\Pdo\Connection;

use PDO;
use Phalcon\DataMapper\Pdo\Connection;
use Phalcon\Tests\AbstractDatabaseTestCase;
use PHPUnit\Framework\Attributes\Group;

use function explode;

final class GetAvailableDriversTest extends AbstractDatabaseTestCase
{
    /**
     * Database Tests Phalcon\DataMapper\Pdo\Connection :: getAvailableDrivers()
     *
     * @since 2020-01-25
     */
    #[Group('mysql')]
    public function testDmPdoConnectionGetAvailableDrivers(): void
    {
        $connection = self::getDataMapperConnection();

        $this->assertSame(
            PDO::getAvailableDrivers(),
            $connection::getAvailableDrivers()
        );
    }

    /**
     * Database Tests Phalcon\DataMapper\Pdo\Connection :: getDriverName()
     *
     * @since 2020-01-25
     */
    #[Group('mysql')]
    public function testDmPdoConnectionGetDriverName(): void
    {
        $connection = self::getDataMapperConnection();

        $dsn = self::getDatabaseDsn();
        $dsn = explode(':', $dsn);

        $this->assertSame(
            $dsn[0],
            $connection->getDriverName()
        );
    }
}
