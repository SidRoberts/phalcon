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

namespace Phalcon\Tests\Database\Mvc\Model;

use PDO;
use Phalcon\Tests\AbstractDatabaseTestCase;
use Phalcon\Tests\Support\Migrations\CustomersMigration;
use Phalcon\Tests\Support\Migrations\InvoicesMigration;
use Phalcon\Tests\Support\Models\Customers;
use Phalcon\Tests\Support\Models\Invoices;
use Phalcon\Tests\Support\Traits\DiTrait;
use PHPUnit\Framework\Attributes\Group;

use function uniqid;

#[Group('phql')]
final class GetRelatedTest extends AbstractDatabaseTestCase
{
    use DiTrait;

    public function setUp(): void
    {
        $this->setNewFactoryDefault();
        $this->setDatabase();
    }

    /**
     * @author Balázs Németh <https://github.com/zsilbi>
     * @since  2020-08-02
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelGetRelated(): void
    {
        /** @var PDO $connection */
        $connection = self::getConnection();

        $custId = 2;

        $firstName = uniqid('cust-', true);
        $lastName  = uniqid('cust-', true);

        $customersMigration = new CustomersMigration($connection);
        $customersMigration->insert($custId, 0, $firstName, $lastName);

        $paidInvoiceId   = 4;
        $unpaidInvoiceId = 5;

        $title = uniqid('inv-');

        $invoicesMigration = new InvoicesMigration($connection);
        $invoicesMigration->insert(
            $paidInvoiceId,
            $custId,
            Invoices::STATUS_PAID,
            $title . '-paid'
        );
        $invoicesMigration->insert(
            $unpaidInvoiceId,
            $custId,
            Invoices::STATUS_UNPAID,
            $title . '-unpaid'
        );

        /**
         * @var Customers $customer
         */
        $customer = Customers::findFirst($custId);
        $invoices = $customer->getRelated(
            'invoices',
            [
                'order' => 'inv_id DESC',
            ]
        );

        $this->assertEquals(
            2,
            $invoices->count()
        );

        $this->assertInstanceOf(Invoices::class, $invoices[0]);

        $this->assertEquals(
            $unpaidInvoiceId,
            $invoices[0]->inv_id
        );

        $this->assertEquals(
            $paidInvoiceId,
            $invoices[1]->inv_id
        );

        $paidInvoices = $customer->getRelated('paidInvoices');

        $this->assertEquals(
            1,
            $paidInvoices->count()
        );

        $this->assertInstanceOf(Invoices::class, $paidInvoices[0]);

        $this->assertEquals(
            $paidInvoiceId,
            $paidInvoices[0]->inv_id
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-10-01
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelGetRelatedChangeForeignKey(): void
    {
        /** @var PDO $connection */
        $connection = self::getConnection();

        $custIdOne    = 10;
        $firstNameOne = uniqid('cust-1-', true);
        $lastNameOne  = uniqid('cust-1-', true);

        $custIdTwo    = 20;
        $firstNameTwo = uniqid('cust-2-', true);
        $lastNameTwo  = uniqid('cust-2-', true);

        $customersMigration = new CustomersMigration($connection);
        $customersMigration->insert($custIdOne, 0, $firstNameOne, $lastNameOne);
        $customersMigration->insert($custIdTwo, 0, $firstNameTwo, $lastNameTwo);

        $invoiceId         = 40;
        $title             = uniqid('inv-');
        $invoicesMigration = new InvoicesMigration($connection);
        $invoicesMigration->insert(
            $invoiceId,
            $custIdOne,
            Invoices::STATUS_PAID,
            $title . '-paid'
        );

        /**
         * Find the invoice. Then use `getRelated` to get the customer. It
         * should return CustomerOne.
         *
         * Change the FK to the customer. Call `getRelated` again. It should
         * return CustomerTwo
         */
        $invoice = Invoices::findFirst(
            [
                'conditions' => 'inv_id = :inv_id:',
                'bind'       => [
                    'inv_id' => $invoiceId,
                ],
            ]
        );

        /**
         * Assert that the correct customer is stored
         */
        $this->assertEquals(
            $custIdOne,
            $invoice->inv_cst_id
        );

        /**
         * Call get related - We should get CustomerOne
         */
        /** @var Customers $customer */
        $customer = $invoice->getRelated('customer');

        $this->assertInstanceOf(Customers::class, $customer);

        $this->assertEquals($custIdOne, $customer->cst_id);

        $invoice->inv_cst_id = $custIdTwo;

        $this->assertTrue(
            $invoice->save()
        );

        /**
         * Now call getRelated. We should get CustomerTwo
         */
        /** @var Customers $customer */
        $customer = $invoice->getRelated('customer');

        $this->assertInstanceOf(Customers::class, $customer);

        $this->assertEquals($custIdTwo, $customer->cst_id);
    }

    /**
     * Consecutive calls to getRelated() without arguments must return the same
     * cached object — no second database round-trip.
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/16409
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-23
     *
     * @group mysql
     * @group pgsql
     * @group sqlite
     */
    public function testMvcModelGetRelatedReturnsCachedResult(): void
    {
        /** @var PDO $connection */
        $connection = self::getConnection();

        $custId    = 3;
        $firstName = uniqid('cust-', true);
        $lastName  = uniqid('cust-', true);

        $customersMigration = new CustomersMigration($connection);
        $customersMigration->insert($custId, 0, $firstName, $lastName);

        $invoicesMigration = new InvoicesMigration($connection);
        $invoicesMigration->insert(30, $custId, Invoices::STATUS_PAID, uniqid('inv-'));
        $invoicesMigration->insert(31, $custId, Invoices::STATUS_PAID, uniqid('inv-'));

        $customer = Customers::findFirst($custId);

        // camelCaseInvoices has reusable: false, so only this->related provides caching
        $first  = $customer->getRelated('camelCaseInvoices');
        $second = $customer->getRelated('camelCaseInvoices');

        // Both calls must return the exact same object — second is served from related cache
        $this->assertSame($first, $second);
        $this->assertCount(2, $first);
    }

    /**
     * getRelated() must return a relation set via __set (dirty related) rather
     * than fetching from the database.
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/16409
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-04-23
     *
     * @group mysql
     * @group pgsql
     * @group sqlite
     */
    public function testMvcModelGetRelatedPrioritisesDirtyRelated(): void
    {
        /** @var PDO $connection */
        $connection = self::getConnection();

        $custId    = 4;
        $firstName = uniqid('cust-', true);
        $lastName  = uniqid('cust-', true);

        $customersMigration = new CustomersMigration($connection);
        $customersMigration->insert($custId, 0, $firstName, $lastName);

        $invoicesMigration = new InvoicesMigration($connection);
        $invoicesMigration->insert(40, $custId, Invoices::STATUS_PAID, uniqid('inv-'));

        /** @var Invoices $invoice */
        $invoice = Invoices::findFirst(40);

        // Create a replacement customer and assign it via __set — goes into dirtyRelated
        $newCustomer          = new Customers();
        $newCustomer->cst_id  = 99;
        $invoice->customer    = $newCustomer;

        // getRelated() must return the dirty relation, not the DB one
        $actual = $invoice->getRelated('customer');
        $this->assertSame($newCustomer, $actual);
    }
}
