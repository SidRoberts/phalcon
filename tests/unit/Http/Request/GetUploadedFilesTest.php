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

namespace Phalcon\Tests\Unit\Http\Request;

use Phalcon\Tests\Support\Page\Http;
use Phalcon\Tests\Unit\Http\Helper\AbstractHttpBase;

final class GetUploadedFilesTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestGetUploadedFiles(): void
    {
        $_FILES['photo'] = [
            'name'     => ['f0', 'f1', ['f2', 'f3'], [[[['f4']]]]],
            'type'     => [
                Http::CONTENT_TYPE_PLAIN,
                Http::CONTENT_TYPE_CSV,
                ['image/png', 'image/gif'],
                [[[['application/octet-stream']]]],
            ],
            'tmp_name' => ['t0', 't1', ['t2', 't3'], [[[['t4']]]]],
            'error'    => [0, 0, [0, 0], [[[[8]]]]],
            'size'     => [10, 20, [30, 40], [[[[50]]]]],
        ];

        $request    = $this->getRequestObject();
        $all        = $request->getUploadedFiles();
        $successful = $request->getUploadedFiles(true);

        $this->assertCount(5, $all);

        $this->assertCount(4, $successful);

        for ($counter = 0; $counter <= 4; ++$counter) {
            $this->assertFalse(
                $all[$counter]->isUploadedFile()
            );
        }

        $data = [
            'photo.0',
            'photo.1',
            'photo.2.0',
            'photo.2.1',
            'photo.3.0.0.0.0',
        ];

        for ($counter = 0; $counter <= 4; ++$counter) {
            $this->assertSame(
                $data[$counter],
                $all[$counter]->getKey()
            );
        }


        $this->assertSame(
            'f0',
            $all[0]->getName()
        );

        $this->assertSame(
            'f1',
            $all[1]->getName()
        );

        $this->assertSame(
            'f2',
            $all[2]->getName()
        );

        $this->assertSame(
            'f3',
            $all[3]->getName()
        );

        $this->assertSame(
            'f4',
            $all[4]->getName()
        );


        $this->assertSame(
            't0',
            $all[0]->getTempName()
        );

        $this->assertSame(
            't1',
            $all[1]->getTempName()
        );

        $this->assertSame(
            't2',
            $all[2]->getTempName()
        );

        $this->assertSame(
            't3',
            $all[3]->getTempName()
        );

        $this->assertSame(
            't4',
            $all[4]->getTempName()
        );


        $this->assertSame(
            'f0',
            $successful[0]->getName()
        );

        $this->assertSame(
            'f1',
            $successful[1]->getName()
        );

        $this->assertSame(
            'f2',
            $successful[2]->getName()
        );

        $this->assertSame(
            'f3',
            $successful[3]->getName()
        );


        $this->assertSame(
            't0',
            $successful[0]->getTempName()
        );

        $this->assertSame(
            't1',
            $successful[1]->getTempName()
        );

        $this->assertSame(
            't2',
            $successful[2]->getTempName()
        );

        $this->assertSame(
            't3',
            $successful[3]->getTempName()
        );
    }
}
