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

namespace Phalcon\Tests\Database\DataMapper\Statement\Select;

use Phalcon\DataMapper\Statement\Select;
use Phalcon\Tests\AbstractStatementTestCase;
use PHPUnit\Framework\Attributes\Group;

use function env;

final class ColumnsTest extends AbstractStatementTestCase
{
    /**
     * Database Tests Phalcon\DataMapper\Statement\Select :: columns()
     *
     * @since 2020-01-20
     */
    #[Group('mysql')]
    public function testDmStatementSelectColumns(): void
    {
        $driver = env('driver');
        $select = Select::new($driver);

        $this->assertFalse(
            $select->hasColumns()
        );

        $select
            ->columns(['inv_id', 'inv_cst_id', 'COUNT(inv_total)'])
            ->from('co_invoices')
        ;

        $expected = 'SELECT inv_id, inv_cst_id, COUNT(inv_total) '
            . 'FROM co_invoices';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );

        $select->reset();

        $select
            ->columns(
                [
                    'id'         => 'inv_id',
                    'customerId' => 'inv_cst_id',
                    'total'      => 'COUNT(inv_total)',
                ]
            )
            ->from('co_invoices')
        ;

        $expected = 'SELECT '
            . 'inv_id AS id, '
            . 'inv_cst_id AS customerId, '
            . 'COUNT(inv_total) AS total '
            . 'FROM co_invoices';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );
    }
}
