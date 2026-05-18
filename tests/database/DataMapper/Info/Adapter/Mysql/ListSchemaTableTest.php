<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Database\DataMapper\Info\Adapter\Mysql;

use Phalcon\DataMapper\Info\Adapter\Mysql;
use Phalcon\DataMapper\Pdo\Connection;
use Phalcon\Tests\AbstractDatabaseTestCase;
use PHPUnit\Framework\Attributes\Group;

final class ListSchemaTableTest extends AbstractDatabaseTestCase
{
    /**
     * @since 2025-01-14
     */
    #[Group('mysql')]
    public function testDmInfoAdapterMysqlListSchemaName(): void
    {
        $connection = self::getDataMapperConnection();

        $mysql = new Mysql($connection);

        $this->assertSame(
            ['phalcon', 'co_dialect'],
            $mysql->listSchemaTable('co_dialect')
        );

        $this->assertSame(
            ['phalcon', 'co_dialect'],
            $mysql->listSchemaTable('phalcon.co_dialect')
        );
    }
}
