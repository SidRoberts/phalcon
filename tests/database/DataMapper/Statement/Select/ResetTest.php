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

final class ResetTest extends AbstractStatementTestCase
{
    /**
     * Database Tests Phalcon\DataMapper\Statement\Select :: reset()
     *
     * @since 2020-01-20
     */
    #[Group('mysql')]
    public function testDmStatementReset(): void
    {
        $driver = env('driver');
        $select = Select::new($driver);

        /**
         * The query does not make sense but it is just to test the method
         */
        $select
            ->with('cte1', 'SELECT * FROM co_customers')
            ->columns(['inv_id', 'inv_cst_id', 'COUNT(inv_total)'])
            ->from('co_invoices')
            ->having('inv_total = :total')
            ->bindValue('total', 100)
            ->appendWhere('inv_total > ', 100)
            ->limit(10)
            ->offset(50)
            ->groupBy('inv_status_flag')
            ->orderBy(['inv_cst_id'])
            ->setFlag('LOW_PRIORITY')
        ;

        $expected = 'WITH ' . $select->quote($driver, 'cte1') . ' AS (SELECT * FROM co_customers) '
            . 'SELECT '
            . 'LOW_PRIORITY '
            . 'inv_id, inv_cst_id, COUNT(inv_total) '
            . 'FROM co_invoices '
            . 'WHERE inv_total > :_1_1_ '
            . 'GROUP BY inv_status_flag '
            . 'HAVING inv_total = :total '
            . 'ORDER BY inv_cst_id '
            . 'LIMIT 10 '
            . 'OFFSET 50';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );

        /**
         * resetWith
         */
        $select->resetWith();

        $expected = 'SELECT '
            . 'LOW_PRIORITY '
            . 'inv_id, inv_cst_id, COUNT(inv_total) '
            . 'FROM co_invoices '
            . 'WHERE inv_total > :_1_1_ '
            . 'GROUP BY inv_status_flag '
            . 'HAVING inv_total = :total '
            . 'ORDER BY inv_cst_id '
            . 'LIMIT 10 '
            . 'OFFSET 50';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );


        /**
         * resetColumns()
         */
        $this->assertTrue(
            $select->hasColumns()
        );

        $select->resetColumns();

        $this->assertFalse(
            $select->hasColumns()
        );

        $expected = 'SELECT '
            . 'LOW_PRIORITY '
            . '* '
            . 'FROM co_invoices '
            . 'WHERE inv_total > :_1_1_ '
            . 'GROUP BY inv_status_flag '
            . 'HAVING inv_total = :total '
            . 'ORDER BY inv_cst_id '
            . 'LIMIT 10 '
            . 'OFFSET 50';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );

        /**
         * resetFlags()
         */
        $select->resetFlags();

        $expected = 'SELECT '
            . '* '
            . 'FROM co_invoices '
            . 'WHERE inv_total > :_1_1_ '
            . 'GROUP BY inv_status_flag '
            . 'HAVING inv_total = :total '
            . 'ORDER BY inv_cst_id '
            . 'LIMIT 10 '
            . 'OFFSET 50';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );

        /**
         * resetFrom()
         */
        $select->resetFrom();

        $expected = 'SELECT '
            . '* '
            . 'WHERE inv_total > :_1_1_ '
            . 'GROUP BY inv_status_flag '
            . 'HAVING inv_total = :total '
            . 'ORDER BY inv_cst_id '
            . 'LIMIT 10 '
            . 'OFFSET 50';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );

        /**
         * resetGroupBy()
         */
        $select->resetGroupBy();

        $expected = 'SELECT '
            . '* '
            . 'WHERE inv_total > :_1_1_ '
            . 'HAVING inv_total = :total '
            . 'ORDER BY inv_cst_id '
            . 'LIMIT 10 '
            . 'OFFSET 50';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );

        /**
         * resetHaving()
         */
        $select->resetHaving();

        $expected = 'SELECT '
            . '* '
            . 'WHERE inv_total > :_1_1_ '
            . 'ORDER BY inv_cst_id '
            . 'LIMIT 10 '
            . 'OFFSET 50';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );

        /**
         * resetLimit()
         */
        $select->resetLimit();

        $expected = 'SELECT '
            . '* '
            . 'WHERE inv_total > :_1_1_ '
            . 'ORDER BY inv_cst_id';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );

        /**
         * resetOrderBy()
         */
        $select->resetOrderBy();

        $expected = 'SELECT '
            . '* '
            . 'WHERE inv_total > :_1_1_';

        $this->assertSame(
            $expected,
            $select->getStatement()
        );

        /**
         * resetWhere()
         */
        $select->resetWhere();

        $this->assertSame(
            'SELECT *',
            $select->getStatement()
        );

        /**
         * reset()
         */
        $select
            ->columns(['inv_id', 'inv_cst_id', 'COUNT(inv_total)'])
            ->from('co_invoices')
            ->having('inv_total = :total')
            ->bindValue('total', 100)
            ->appendWhere('inv_total > ', 100)
            ->limit(10)
            ->offset(50)
            ->groupBy('inv_status_flag')
            ->orderBy(['inv_cst_id'])
            ->setFlag('LOW_PRIORITY')
        ;

        $select->reset();

        $this->assertSame(
            'SELECT *',
            $select->getStatement()
        );
    }
}
