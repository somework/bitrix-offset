<?php


namespace SomeWork\Bitrix\Offset;


class OffsetResult implements SourceResultInterface
{
    /**
     * @var \Generator
     */
    private $generator;

    /**
     * @var int
     */
    private $totalCount;

    public function __construct(\Generator $generator)
    {
        $this->totalCount = 0;
        $this->generator = $generator;
    }

    /**
     * @return \Generator
     */
    public function generator()
    {
        while ($sourceResult = $this->getSourceResult()) {
            if (!is_object($sourceResult) || !($sourceResult instanceof SourceResultInterface)) {
                continue;
            }
            if ($sourceResult->getTotalCount() > $this->totalCount) {
                $this->totalCount = $sourceResult->getTotalCount();
            }
            yield from $sourceResult->generator();
        }
    }

    protected function getSourceResult()
    {
        if ($this->generator->valid()) {
            $value = $this->generator->current();
            $this->generator->next();
            return $value;
        }
        return null;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }
}