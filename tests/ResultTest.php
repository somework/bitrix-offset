<?php

/*
 * This file is part of the Bitrix/Offset package.
 *
 * (c) Pinchuk Igor <i.pinchuk.work@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SomeWork\Bitrix\Offset;

use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testGoodFetch()
    {
        $count = 5;
        $function = function () use ($count) {
            for ($i = 1; $i <= $count; $i++) {
                yield $i;
            }
        };

        $items = 0;
        $result = new Result($function->call($this));
        while ($item = $result->fetch()) {
            $items++;
        }
        $this->assertEquals($count, $items);
    }

    public function testBadFetch()
    {
        $function = function () {
            for ($i = 4; $i <= 2; $i++) {
                yield $i;
            }
        };
        $result = new Result($function->call($this));
        $this->assertFalse($result->fetch());
    }
}
