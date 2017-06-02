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

abstract class AbstractSource
{
    /**
     * @var int
     */
    protected $page = 0;

    /**
     * @var int
     */
    protected $pageSize = 0;

    /**
     * @var SourceResultInterface
     */
    protected $result;

    /**
     * @var int
     */
    protected $totalCount = 0;

    /**
     * AbstractSource constructor.
     *
     * @param int $page
     * @param int $pageSize
     */
    protected function __construct($page, $pageSize)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
    }

    /**
     * @param int $page
     * @param int $pageSize
     *
     * @return $this
     */
    public static function create($page, $pageSize)
    {
        return new static((int) $page, (int) $pageSize);
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->reset();
        $this->page = (int) $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     *
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->reset();
        $this->pageSize = (int) $pageSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    protected function reset()
    {
        $this->result = null;
        $this->totalCount = 0;
    }

    /**
     * @param int $totalCount
     *
     * @return $this
     */
    protected function setTotalCount($totalCount)
    {
        $this->totalCount = (int) $totalCount;
        return $this;
    }

    /**
     * Should set total count
     * @see \SomeWork\Bitrix\Offset\AbstractSource::setTotalCount()
     * @return SourceResultInterface
     */
    abstract public function execute();
}
