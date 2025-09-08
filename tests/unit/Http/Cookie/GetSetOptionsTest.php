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

namespace Phalcon\Tests\Unit\Http\Cookie;

use Phalcon\Tests\Unit\Http\Helper\AbstractHttpBase;

final class GetSetOptionsTest extends AbstractHttpBase
{
    /**
     * Tests Phalcon\Http\Cookie :: getOptions()/setOptions()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-09
     */
    public function testHttpCookieGetSetOptions(): void
    {
        $this->setDiService('sessionStream');

        $options = [
            "samesite" => "Lax",
        ];

        $cookie  = $this->getCookieObject();

        $this->assertSame(
            $options,
            $cookie->getOptions()
        );

        $newOptions = [
            'secure'   => false,
            'httponly' => false,
        ];

        $cookie->setOptions($newOptions);

        $this->assertSame(
            $newOptions,
            $cookie->getOptions()
        );
    }
}
