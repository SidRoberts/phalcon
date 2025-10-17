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
use Phalcon\Mvc\Model\Exception;
use Phalcon\Mvc\Model\Transaction\Manager;
use Phalcon\Tests\AbstractDatabaseTestCase;
use Phalcon\Tests\Support\Migrations\CustomersMigration;
use Phalcon\Tests\Support\Migrations\InvoicesMigration;
use Phalcon\Tests\Support\Models\Customers;
use Phalcon\Tests\Support\Models\Invoices;
use Phalcon\Tests\Support\Models\NoPrimaryKey;
use Phalcon\Tests\Support\Traits\DiTrait;
use PHPUnit\Framework\Attributes\Group;

use function date;
use function uniqid;

#[Group('phql')]
final class DeleteTest extends AbstractDatabaseTestCase
{
    use DiTrait;

    public function setUp(): void
    {
        $this->setNewFactoryDefault();
        $this->setDatabase();
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-02-01
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelDelete(): void
    {
        /**
         * The following tests need to skip sqlite because we will get
         * a General Error 5 database is locked error
         */
        $title                    = uniqid('inv-');
        $date                     = date('Y-m-d H:i:s');
        $invoice                  = new Invoices();
        $invoice->inv_cst_id      = 2;
        $invoice->inv_status_flag = 3;
        $invoice->inv_title       = $title;
        $invoice->inv_total       = 100.12;
        $invoice->inv_created_at  = $date;

        $this->assertNotFalse(
            $invoice->create()
        );

        $this->assertTrue(
            $invoice->delete()
        );
    }

    /**
     * @author Balázs Németh <https://github.com/zsilbi>
     * @since  2020-08-02
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelDeleteCascadeRelated(): void
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

        $this->assertEquals(
            2,
            $customer->invoices->count()
        );

        $this->assertEquals(
            1,
            $customer->paidInvoices->count()
        );

        $this->assertEquals(
            1,
            $customer->unpaidInvoices->count()
        );

        $this->assertTrue(
            $customer->delete()
        );

        $invoices = Invoices::find();

        $this->assertEquals(
            1,
            $invoices->count()
        );

        $this->assertEquals(
            $unpaidInvoiceId,
            $invoices[0]->inv_id
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2022-11-18
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelDeleteGetRelated(): void
    {
        /** @var PDO $connection */
        $connection = self::getConnection();

        $custId    = 2;
        $firstName = uniqid('cust-', true);
        $lastName  = uniqid('cust-', true);

        /**
         * Set up a customer
         */
        $customersMigration = new CustomersMigration($connection);
        $customersMigration->insert($custId, 0, $firstName, $lastName);

        $title = uniqid('inv-');

        /**
         * Set up invoices for the customer
         *
         * 2 paid
         * 3 unpaid
         */
        $invoicesMigration = new InvoicesMigration($connection);
        $invoicesMigration->insert(
            40,
            $custId,
            Invoices::STATUS_PAID,
            $title . '-paid'
        );
        $invoicesMigration->insert(
            41,
            $custId,
            Invoices::STATUS_PAID,
            $title . '-paid'
        );

        $invoicesMigration->insert(
            50,
            $custId,
            Invoices::STATUS_PAID,
            $title . '-paid'
        );
        $invoicesMigration->insert(
            51,
            $custId,
            Invoices::STATUS_PAID,
            $title . '-paid'
        );
        $invoicesMigration->insert(
            52,
            $custId,
            Invoices::STATUS_PAID,
            $title . '-paid'
        );

        /**
         * Get the customer from the database
         *
         * @var Customers $customer
         */
        $customer = Customers::findFirst($custId);

        /**
         * Check for the number of invoices
         */
        $this->assertEquals(
            5,
            $customer->invoices->count()
        );

        /**
         * Get paid invoices using the property
         */
        $invoices = $customer->paidInvoices;
        /** @var Invoices $invoice */
        foreach ($invoices as $invoice) {
            if ($invoice->inv_id < 50) {
                $this->assertTrue(
                    $invoice->delete()
                );
            }
        }

        /**
         * Just in case for a refresh
         *
         * @var Customers $customer
         */
        $customer = Customers::findFirst($custId);

        /**
         * Check for the number of invoices
         */
        $this->assertEquals(
            3,
            $customer->invoices->count()
        );

        /**
         * Get unpaid invoices using getRelated()
         */
        $invoices = $customer->getRelated('invoices');
        /** @var Invoices $invoice */
        foreach ($invoices as $invoice) {
            $this->assertTrue(
                $invoice->delete()
            );
        }

        /**
         * Check for the number of invoices
         */
        $customer->invoices->refresh();

        $this->assertEquals(
            0,
            $customer->invoices->count()
        );
    }

    /**
     * @author Balázs Németh <https://github.com/zsilbi>
     * @since  2020-10-17
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelDeleteRestrictRelated(): void
    {
        /** @var PDO $connection */
        $connection = self::getConnection();

        $customerId = 2;

        $firstName = uniqid('cust-', true);
        $lastName  = uniqid('cust-', true);

        $customersMigration = new CustomersMigration($connection);
        $customersMigration->insert($customerId, 0, $firstName, $lastName);

        $title = uniqid('inv-');

        $invoicesMigration = new InvoicesMigration($connection);
        $invoicesMigration->insert(
            1,
            $customerId,
            Invoices::STATUS_INACTIVE,
            $title . '-inactive'
        );

        /**
         * @var Customers $customer
         */
        $customer = Customers::findFirst($customerId);


        $this->assertEquals(
            1,
            $customer->inactiveInvoices->count()
        );

        $this->assertFalse(
            $customer->delete()
        );

        $this->assertCount(
            1,
            $customer->getMessages()
        );

        $this->assertSame(
            'Record is referenced by model ' . Invoices::class,
            current($customer->getMessages())->getMessage()
        );
    }

    /**
     * @author Balázs Németh <https://github.com/zsilbi>
     * @since  2020-10-17
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelDeleteRestrictRelatedInTransaction(): void
    {
        /** @var PDO $connection */
        $connection = self::getConnection();

        $invoiceId  = 1;
        $customerId = 2;

        $firstName = uniqid('cust-', true);
        $lastName  = uniqid('cust-', true);

        $customersMigration = new CustomersMigration($connection);
        $customersMigration->insert($customerId, 0, $firstName, $lastName);

        $title = uniqid('inv-');

        $invoicesMigration = new InvoicesMigration($connection);
        $invoicesMigration->insert(
            $invoiceId,
            $customerId,
            Invoices::STATUS_INACTIVE,
            $title . '-inactive'
        );

        /**
         * Step 1:
         * Create two models Model A and Model B and setup relations
         * so that Model A cannot be deleted if it is used in Model B
         *
         * @var Customers $customer
         * @var Invoices  $invoice
         */
        $customer = Customers::findFirst($customerId); // Model A
        $invoice  = Invoices::findFirst($invoiceId);   // Model B

        /**
         * Step 2:
         * Start a transaction and set both models to use that same transaction
         *
         * @var Manager $transactionManager
         */
        $transactionManager = $this->getDi()->getShared('transactionManager');

        $transaction = $transactionManager->get();

        $customer->setTransaction($transaction);
        $invoice->setTransaction($transaction);

        /**
         * Make sure foreign key restrict works
         */
        $this->assertFalse(
            $customer->delete()
        );

        /**
         * Step 3:
         * Delete Model B first so that Model A passes FK constraint check successfully
         */
        $this->assertTrue(
            $invoice->delete()
        );

        /**
         * Step 4:
         * Then try to delete Model A
         */
        $this->assertTrue(
            $customer->delete()
        );

        $transaction->rollback();

        /**
         * Test again foreign key restrict
         */
        $this->assertFalse(
            $customer->delete()
        );
    }

    /**
     * Tests Phalcon\Mvc\Model :: delete() without a primary key
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-12
     */
    #[Group('mysql')]
    public function testMvcModelDeleteWithoutAPrimaryKey(): void
    {
        $model = new NoPrimaryKey();

        $model->nokey_id = 123;
        $model->nokey_name = "John Smith";

        $model->save();

        $this->expectException(Exception::class);

        $this->expectExceptionMessage(
            "A primary key must be defined in the model in order to perform the operation in '"
                . NoPrimaryKey::class . "'"
        );

        $model->delete();
    }
}
