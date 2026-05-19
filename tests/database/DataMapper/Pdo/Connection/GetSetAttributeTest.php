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

final class GetSetAttributeTest extends AbstractDatabaseTestCase
{
    /**
     * Database Tests Phalcon\DataMapper\Pdo\Connection ::
     * getAttribute()/setAttribute()
     *
     * @since 2020-01-25
     */
    #[Group('mysql')]
    public function testDmPdoConnectionGetSetAttribute(): void
    {
        $connection = self::getDataMapperConnection();

        $this->assertSame(
            PDO::ERRMODE_EXCEPTION,
            $connection->getAttribute(PDO::ATTR_ERRMODE)
        );

        $connection->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_WARNING
        );

        $this->assertSame(
            PDO::ERRMODE_WARNING,
            $connection->getAttribute(PDO::ATTR_ERRMODE)
        );
    }
}
