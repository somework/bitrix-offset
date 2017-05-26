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

class Result
{
    /**
     * @var \Generator
     */
    private $generator;

    public function __construct(\Generator $generator)
    {
        $this->generator = $generator;
    }

    public function fetch()
    {
        if ($this->generator->valid()) {
            $value = $this->generator->current();
            $this->generator->next();
            return $value;
        }
        return false;
    }
}
