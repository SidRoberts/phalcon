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

final class DebugInfoTest extends AbstractDatabaseTestCase
{
    /**
     * Database Tests Phalcon\DataMapper\Pdo\Connection :: __debugInfo()
     *
     * @since 2020-01-25
     */
    #[Group('mysql')]
    public function testDmPdoConnectionDebugInfo(): void
    {
        $connection = self::getDataMapperConnection();

        $expected = [
            'arguments' => [
                $this->getDatabaseDsn(),
                '****',
                '****',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ],
                [],
            ],
        ];

        $this->assertSame(
            $expected,
            $connection->__debugInfo()
        );
    }
}
