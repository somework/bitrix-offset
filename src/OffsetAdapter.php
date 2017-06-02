<?php


namespace SomeWork\Bitrix\Offset;


class OffsetAdapter
{
    /**
     * @var SourceInterface
     */
    protected $source;

    public function __construct(SourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return SourceResultInterface
     */
    public function execute($offset, $limit)
    {
        $result = new OffsetResult($this->logic($offset, $limit));
    }

    /**
     * @param $offset
     * @param $limit
     *
     * @return \Generator
     */
    public function logic($offset, $limit)
    {
        $offset = (int)$offset;
        $limit = (int)$limit;

        $offset = $offset >= 0 ? $offset : 0;
        $limit = $limit >= 0 ? $limit : 0;

        if ($offset === 0 && $limit > 0) {
            yield $this->source->execute(1, $limit);
            return;
        }

        if ($offset > 0 && $limit === 0) {
            $result = $this->source->execute(2, $offset);
            $totalCount = $result->getTotalCount();
            yield $result;

            $need = $totalCount - $offset;
            while ($need > 0) {
                $need -= $offset;
                yield $this->source->execute(2, $offset);
            }
        }

        if ($offset > 0 && $limit > 0) {
            yield $this->source->execute(2, $offset);
            $need = $limit - $offset;
            while ($need > 0) {
                if ($need < $offset) {
                    $page = (int)floor($offset / $need);
                    $pageSize = $offset / $page;
                    $need -= $pageSize;
                    yield $this->source->execute($page, $pageSize);
                } else {
                    $need -= $offset;
                    yield $this->source->execute(2, $offset);
                }
            }
        }
    }


}