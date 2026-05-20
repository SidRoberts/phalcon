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

use Phalcon\Di\Di;
use Phalcon\Mvc\Model\Exception;
use Phalcon\Tests\AbstractDatabaseTestCase;
use Phalcon\Tests\Support\Migrations\InvoicesMigration;
use Phalcon\Tests\Support\Models\Invoices;
use Phalcon\Tests\Support\Models\Products;
use Phalcon\Tests\Support\Traits\DiTrait;
use PHPUnit\Framework\Attributes\Group;

use function date;
use function uniqid;

final class UnserializeTest extends AbstractDatabaseTestCase
{
    use DiTrait;

    public function setUp(): void
    {
        $this->setNewFactoryDefault();
        $this->setDatabase();

        (new InvoicesMigration(self::getConnection()));
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-01-31
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelUnserialize(): void
    {
        $title = uniqid('inv-');
        $date  = date('Y-m-d H:i:s');
        $data  = [
            'inv_id'          => null,
            'inv_cst_id'      => 5,
            'inv_status_flag' => 2,
            'inv_title'       => $title,
            'inv_total'       => 100.12,
            'inv_created_at'  => $date,
        ];

        $invoice = new Invoices();
        $invoice->assign($data);

        $result = $invoice->save();
        $this->assertNotFalse($result);

        $serialized = serialize($data);

        $newModel = new Invoices();
        $newModel->unserialize($serialized);

        $this->assertEquals(
            $data,
            $newModel->toArray()
        );
    }

    /**
     * Tests Phalcon\Mvc\Model :: unserialize() - without a DI
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-12
     */
    #[Group('mysql')]
    public function testMvcModelUnserializeWithoutADI(): void
    {
        $this->expectException(Exception::class);

        $this->expectExceptionMessage(
            "A dependency injection container is required to access the services related to the ODM in '"
                . Products::class . "'"
        );

        $product = new Products();

        Di::reset();

        $product->unserialize('a:0:{}');
    }
}
