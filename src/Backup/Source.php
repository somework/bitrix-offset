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

abstract class Source
{
    protected $offset;
    protected $limit;
    protected $listed;

    public function __construct($listed = 0)
    {
        $this->listed = (int) $listed;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \SomeWork\Bitrix\Offset\Result
     */
    public function execute($offset, $limit)
    {
        return new Result($this->getGenerator(
            $offset,
            $limit - $this->listed
        ));
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generator
     */
    protected function getGenerator($offset, $limit)
    {
        if ($limit) {
            $page = $offset > 0 ? 2 : 1;
            $pageSize = $offset ?: $limit;

            yield from $this->get(
                $page,
                $pageSize,
                $limit
            );
            $this->listed += $limit;
            $this->getGenerator(
                $page * $pageSize - $pageSize + $limit,
                $limit - $this->listed
            );
        }
    }

    abstract protected function get($page, $pageSize, $limit);
}
