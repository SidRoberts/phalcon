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

namespace Phalcon\Tests\Database\DataMapper\Table\Row;

use Phalcon\DataMapper\Table\AbstractRow;
use Phalcon\DataMapper\Table\Exception\ImmutableAfterDeletedException;
use Phalcon\DataMapper\Table\Exception\InvalidOptionException;
use Phalcon\DataMapper\Table\Exception\PropertyDoesNotExistException;
use Phalcon\Tests\AbstractDatabaseTestCase;
use Phalcon\Tests\Support\DataMapper\Table\Invoices\InvoicesRow;
use PHPUnit\Framework\Attributes\Group;

use function uniqid;

final class RowTest extends AbstractDatabaseTestCase
{
    #[Group('mysql')]
    public function testConstructor(): void
    {
        $row = new InvoicesRow();

        $this->assertInstanceOf(InvoicesRow::class, $row);
        $this->assertInstanceOf(AbstractRow::class, $row);
    }

    #[Group('mysql')]
    public function testConstructorWithData(): void
    {
        $title = uniqid('tit-');
        $data  = [
            'inv_id'          => 1,
            'inv_cst_id'      => 1,
            'inv_status_flag' => 1,
            'inv_title'       => $title,
            'inv_total'       => 100.0,
            'inv_created_at'  => '2024-02-01 10:11:12',
        ];

        $row = new InvoicesRow($data);

        $this->assertSame(
            $data,
            $row->getCopy()
        );
    }

    #[Group('mysql')]
    public function testConstructorWithUnknownColumnsThrowsException(): void
    {
        $title = uniqid('tit-');
        $data  = [
            'inv_id'          => 1,
            'inv_cst_id'      => 1,
            'inv_status_flag' => 1,
            'inv_title'       => $title,
            'inv_total'       => 100.0,
            'inv_created_at'  => '2024-02-01 10:11:12',
            'other_column'    => 'random stuff',
        ];

        $this->expectException(PropertyDoesNotExistException::class);
        $this->expectExceptionMessage(
            '[' . InvoicesRow::class . '::other_column] does not exist'
        );

        $row = new InvoicesRow($data);
    }

    #[Group('mysql')]
    public function testGetCopy(): void
    {
        $title = uniqid('tit-');
        $data  = [
            'inv_id'          => 1,
            'inv_cst_id'      => 1,
            'inv_status_flag' => 1,
            'inv_title'       => $title,
            'inv_total'       => 100.0,
            'inv_created_at'  => '2024-02-01 10:11:12',
        ];

        $row = new InvoicesRow($data);

        $this->assertSame(
            $data,
            $row->getCopy()
        );
    }

    #[Group('mysql')]
    public function testGetDiff(): void
    {
        $title = uniqid('tit-');
        $data  = [
            'inv_id'          => 1,
            'inv_cst_id'      => 1,
            'inv_status_flag' => 1,
            'inv_title'       => $title,
            'inv_total'       => 100.0,
            'inv_created_at'  => '2024-02-01 10:11:12',
        ];

        $row = new InvoicesRow($data);

        $row
            ->set('inv_id', 2)
            ->set('inv_cst_id', 2)
            ->set('inv_status_flag', 0)
        ;

        $expected = [
            'inv_id'          => 2,
            'inv_cst_id'      => 2,
            'inv_status_flag' => 0,
        ];

        $this->assertSame(
            $expected,
            $row->getDiff()
        );

        $row->set('inv_status_flag', false);

        $expected = [
            'inv_id'          => 2,
            'inv_cst_id'      => 2,
            'inv_status_flag' => false,
        ];

        $this->assertSame(
            $expected,
            $row->getDiff()
        );
    }

    #[Group('mysql')]
    public function testGetInit(): void
    {
        $title = uniqid('tit-');
        $data  = [
            'inv_id'          => 1,
            'inv_cst_id'      => 1,
            'inv_status_flag' => 1,
            'inv_title'       => $title,
            'inv_total'       => 100.0,
            'inv_created_at'  => '2024-02-01 10:11:12',
        ];

        $row = new InvoicesRow($data);

        $this->assertSame(
            $data,
            $row->getInit()
        );
    }

    #[Group('mysql')]
    public function testGetIterator(): void
    {
        $title = uniqid('tit-');
        $data  = [
            'inv_id'          => 1,
            'inv_cst_id'      => 1,
            'inv_status_flag' => 1,
            'inv_title'       => $title,
            'inv_total'       => 100.0,
            'inv_created_at'  => '2024-02-01 10:11:12',
        ];

        $row = new InvoicesRow($data);

        foreach ($row as $name => $value) {
            $this->assertSame($data[$name], $value);
        }
    }

    #[Group('mysql')]
    public function testHas(): void
    {
        $row = new InvoicesRow();

        $this->assertTrue(
            $row->has('inv_id')
        );

        $this->assertFalse(
            $row->has('unknown_column')
        );
    }

    #[Group('mysql')]
    public function testJsonSerialize(): void
    {
        $title = uniqid('tit-');
        $data  = [
            'inv_id'          => 1,
            'inv_cst_id'      => 1,
            'inv_status_flag' => 1,
            'inv_title'       => $title,
            'inv_total'       => 100.0,
            'inv_created_at'  => '2024-02-01 10:11:12',
        ];

        $row = new InvoicesRow($data);

        $this->assertSame(
            $data,
            $row->jsonSerialize()
        );
    }

    #[Group('mysql')]
    public function testRemove(): void
    {
        $row = new InvoicesRow();

        $this->assertNull(
            $row->get('inv_id')
        );

        $row->set('inv_id', 1);

        $this->assertSame(
            1,
            $row->get('inv_id')
        );

        $row->remove('inv_id');

        $this->assertNull(
            $row->get('inv_id')
        );
    }

    #[Group('mysql')]
    public function testRemoveDeletedThrowsException(): void
    {
        $this->expectException(ImmutableAfterDeletedException::class);
        $this->expectExceptionMessage(
            '[' . InvoicesRow::class . '::inv_id] is immutable after the row is deleted'
        );

        $row = new InvoicesRow();

        $row->set('inv_id', 1);
        $row->setLastAction($row::DELETE);

        $row->remove('inv_id');
    }

    #[Group('mysql')]
    public function testSet(): void
    {
        $title = uniqid('tit-');

        $row = new InvoicesRow();
        $row
            ->set('inv_id', 1)
            ->set('inv_cst_id', 1)
            ->set('inv_status_flag', 1)
            ->set('inv_title', $title)
            ->set('inv_total', 100.0)
            ->set('inv_created_at', '2024-02-01 10:11:12')
        ;

        $expected = [
            'inv_id'          => 1,
            'inv_cst_id'      => 1,
            'inv_status_flag' => 1,
            'inv_title'       => $title,
            'inv_total'       => 100.0,
            'inv_created_at'  => '2024-02-01 10:11:12',
        ];

        $this->assertSame(
            $expected,
            $row->getCopy()
        );
    }

    #[Group('mysql')]
    public function testSetOnDeletedRowThrowsException(): void
    {
        $this->expectException(ImmutableAfterDeletedException::class);
        $this->expectExceptionMessage(
            '[' . InvoicesRow::class . '::inv_id] is immutable after the row is deleted.'
        );

        $row = new InvoicesRow();

        $row
            ->setLastAction($row::DELETE)
            ->set('inv_id', 1)
        ;
    }

    #[Group('mysql')]
    public function testSetUnknownColumnThrowsException(): void
    {
        $this->expectException(PropertyDoesNotExistException::class);
        $this->expectExceptionMessage(
            '[' . InvoicesRow::class . '::other_column] does not exist'
        );

        $row = new InvoicesRow();

        $row->set('other_column', 'random stuff');
    }

    #[Group('mysql')]
    public function testLastAction(): void
    {
        $row = new InvoicesRow();

        $this->assertNull(
            $row->getLastAction()
        );

        /**
         * New row - INSERT
         */
        $this->assertSame(
            $row::INSERT,
            $row->getNextAction()
        );

        /**
         * set Delete - Next action is null
         */
        $row->setDelete(true);

        $this->assertNull(
            $row->getNextAction()
        );

        /**
         * unset Delete - Next action is INSERT
         */
        $row->setDelete(false);
        $this->assertSame(
            $row::INSERT,
            $row->getNextAction()
        );

        /**
         * unset Delete - Next action is INSERT
         */
        $row
            ->setLastAction($row::INSERT)
            ->setDelete(true)
        ;
        $this->assertSame(
            $row::DELETE,
            $row->getNextAction()
        );

        /**
         * Revert back to default
         */
        $row->setDelete(false);

        /**
         * Set action to SELECT and change a field - UPDATE
         */
        $row
            ->setLastAction($row::SELECT)
            ->set('inv_id', 1)
        ;

        $this->assertSame(
            $row::UPDATE,
            $row->getNextAction()
        );

        /**
         * Revert the field
         */
        $row->set('inv_id', null);

        $this->assertNull(
            $row->getNextAction()
        );

        /**
         * Set action to SELECT - null getNextAction
         */
        $row->setLastAction($row::SELECT);

        $this->assertNull(
            $row->getNextAction()
        );
    }

    #[Group('mysql')]
    public function testLastActionInvalidThrowsException(): void
    {
        $this->expectException(InvalidOptionException::class);
        $this->expectExceptionMessage(
            'Invalid option supplied [other_option]'
        );

        $row = new InvoicesRow();

        $row->setLastAction('other_option');
    }

    #[Group('mysql')]
    public function testSetNumericToBool(): void
    {
        $title = uniqid('tit-');

        $data = [
            'inv_id'          => 1,
            'inv_cst_id'      => 1,
            'inv_status_flag' => 1,
            'inv_title'       => $title,
            'inv_total'       => 100.0,
            'inv_created_at'  => '2024-02-01 10:11:12',
        ];
        $row = new InvoicesRow($data);

        $row->set('inv_status_flag', true);

        $this->assertSame(
            [],
            $row->getDiff()
        );

        $row->set('inv_status_flag', false);

        $expected = [
            'inv_status_flag' => false,
        ];

        $this->assertSame(
            $expected,
            $row->getDiff()
        );
    }

    #[Group('mysql')]
    public function testSetBoolToNumeric(): void
    {
        $title = uniqid('tit-');

        $data = [
            'inv_id'          => 1,
            'inv_cst_id'      => 1,
            'inv_status_flag' => true,
            'inv_title'       => $title,
            'inv_total'       => 100.0,
            'inv_created_at'  => '2024-02-01 10:11:12',
        ];
        $row = new InvoicesRow($data);

        $row->set('inv_status_flag', 1);

        $this->assertSame(
            [],
            $row->getDiff()
        );

        $row->set('inv_status_flag', 0);

        $expected = [
            'inv_status_flag' => 0,
        ];

        $this->assertSame(
            $expected,
            $row->getDiff()
        );
    }
}
