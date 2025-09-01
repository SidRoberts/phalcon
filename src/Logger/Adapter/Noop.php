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

namespace Phalcon\Logger\Adapter;

use Phalcon\Logger\Item;

/**
 * Class Noop
 */
class Noop extends AbstractAdapter
{
    /**
     * Closes the stream
     *
     * @return bool
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Processes the message i.e. writes it to the file
     *
     * @param Item $item
     *
     * @return void
     */
    public function process(Item $item): void
    {
        // noop
    }
}
