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

class OffsetAdapter
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var array
     */
    protected $params;

    public function __construct($class, ... $params)
    {
        if (!is_string($class)) {
            throw new \InvalidArgumentException(sprintf('Argument $class is not a string: %s', gettype($class)));
        }
        if (!is_a($class, AbstractSource::class, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Class %s is not instance of %s',
                $class,
                AbstractSource::class
            ));
        }
        $this->class = $class;
        $this->params = $params ?: [];
    }

    public function execute($offset, $limit)
    {
        return new Result($this->getGenerator(
            $offset,
            $limit
        ));
    }

    /**
     * @param $page
     * @param $pageSize
     *
     * @return SourceResultInterface
     */
    public function getSourceResult($page, $pageSize)
    {
        $source = $this->getSource($page, $pageSize);
        $result = $source->execute();
        /**
         * todo count
         */
        return $result;
    }

    /**
     * @param int $page
     * @param int $pageSize
     *
     * @return AbstractSource
     */
    protected function getSource($page, $pageSize)
    {
        return call_user_func_array([$this->class, 'create'], array_merge([$page, $pageSize], $this->params));
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generator
     */
    protected function getGenerator($offset, $limit)
    {
        $offset = (int) $offset;
        $limit = (int) $limit;

        $offset = $offset >= 0 ? $offset : 0;
        $limit = $limit >= 0 ? $limit : 0;

        if ($offset === 0 && $limit > 0) {
            yield from $this->getSourceResult(1, $limit)->generator();
            return;
        }
        if ($offset > 0 && $limit === 0) {
            $result = $this->getSource(2, $offset);
            /**
             * todo total count only after execute !!!NOT WORK!!!
             */
            $totalCount = $result->getTotalCount();
            $need = $totalCount - $offset;

            while ($need > 0) {
                $need -= $offset;
                yield from $this->getSourceResult(2, $offset)->generator();
            }
        }
        if ($offset > 0 && $limit > 0) {
            yield from $this->getSourceResult(2, $limit)->generator();
            $need = $limit;
            while ($need > 0) {
                if ($need < $offset) {
                    $page = (int) floor($offset / $need);
                    $pageSize = $offset / $page;
                    $need -= $pageSize;
                    yield from $this->getSourceResult($page, $pageSize)->generator();
                } else {
                    $need -= $offset;
                    yield from $this->getSourceResult(2, $offset)->generator();
                }
            }
        }
    }
}
