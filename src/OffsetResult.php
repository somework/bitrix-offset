<?php

/*
 * This file is part of the SomeWork/OffsetPage package.
 *
 * (c) Pinchuk Igor <i.pinchuk.work@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SomeWork\OffsetPage;

class OffsetResult implements SourceResultInterface
{
    /**
     * @var \Generator
     */
    protected $generator;

    /**
     * @var int
     */
    protected $totalCount = 0;

    /**
     * OffsetResult constructor.
     *
     * @param \Generator $generator
     */
    public function __construct(\Generator $generator)
    {
        $this->totalCount = 0;
        $this->generator = $generator;
    }

    /**
     * @return \Generator
     * @throws \InvalidArgumentException
     */
    public function generator(): \Generator
    {
        while ($sourceResult = $this->getSourceResult()) {
            if (!is_object($sourceResult) || !($sourceResult instanceof SourceResultInterface)) {
                throw new \InvalidArgumentException(sprintf(
                    'Result of generator is not an instance of %s',
                    SourceResultInterface::class
                ));
            }
            $sourceCount = $sourceResult->getTotalCount();
            if ($sourceCount > $this->totalCount) {
                $this->totalCount = $sourceCount;
            }

            foreach ($sourceResult->generator() as $result) {
                yield $result;
            }
        }
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
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
}
