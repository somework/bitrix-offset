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

class SourceResultCallbackAdapter implements SourceResultInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * SourceResultCallbackAdapter constructor.
     *
     * @param callable $callback Callback should return generator
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return \Generator
     */
    public function generator()
    {
        yield from call_user_func($this->callback);
    }
}
