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

namespace Phalcon\Tests\Database\DataMapper\Statement\Update;

use Phalcon\DataMapper\Statement\Update;
use Phalcon\Tests\AbstractDatabaseTestCase;
use PHPUnit\Framework\Attributes\Group;

use function env;

final class HasColumnsTest extends AbstractDatabaseTestCase
{
    /**
     * Database Tests Phalcon\DataMapper\Query\Update :: hasColumns()
     *
     * @since 2020-01-20
     */
    #[Group('mysql')]
    public function testDmStatementUpdateHasColumns(): void
    {
        $driver = env('driver');
        $update = Update::new($driver);

        $this->assertFalse(
            $update->hasColumns()
        );

        $update->columns(['inv_id', 'inv_total']);

        $this->assertTrue(
            $update->hasColumns()
        );
    }
}
