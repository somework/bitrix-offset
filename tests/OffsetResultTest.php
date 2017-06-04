<?php

/*
 * This file is part of the SomeWork/OffsetPage package.
 *
 * (c) Pinchuk Igor <i.pinchuk.work@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SomeWork\OffsetPage\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SomeWork\OffsetPage\OffsetResult;
use SomeWork\OffsetPage\SourceResultInterface;

class OffsetResultTest extends TestCase
{
    public function testNotSourceResultInterfaceGenerator()
    {
        $this->expectException(InvalidArgumentException::class);
        $notSourceResultGeneratorFunction = function () {
            yield 1;
        };

        $offsetResult = new OffsetResult($notSourceResultGeneratorFunction());
        $generator = $offsetResult->generator();
        $generator->current();
    }

    public function testSourceResultInterfaceGenerator()
    {
        $sourceResult = $this
            ->getMockBuilder(SourceResultInterface::class)
            ->setMethods(['getTotalCount', 'generator'])
            ->getMock();

        $sourceResult
            ->method('getTotalCount')
            ->willReturn(10);

        $sourceResult
            ->method('generator')
            ->willReturn($this->getGenerator(['test']));

        $offsetResult = new OffsetResult($this->getGenerator([$sourceResult]));
        $generator = $offsetResult->generator();
        $this->assertEquals($generator->current(), 'test');
    }

    public function testTotalCountSet()
    {
        $sourceResult = $this
            ->getMockBuilder(SourceResultInterface::class)
            ->setMethods(['getTotalCount', 'generator'])
            ->getMock();

        $sourceResult
            ->expects($this->exactly(1))
            ->method('getTotalCount')
            ->willReturn(10);

        $sourceResult
            ->method('generator')
            ->willReturn($this->getGenerator(['test']));

        $offsetResult = new OffsetResult($this->getGenerator([$sourceResult]));
        $generator = $offsetResult->generator();
        $generator->current();
        $this->assertEquals(10, $offsetResult->getTotalCount());
    }

    /**
     * @dataProvider totalCountProvider
     *
     * @param $totalCountValues
     * @param $expectsCount
     */
    public function testTotalCountNotChanged(array $totalCountValues, int $expectsCount)
    {
        $sourceResult = $this
            ->getMockBuilder(SourceResultInterface::class)
            ->setMethods(['getTotalCount', 'generator'])
            ->getMock();


        $sourceResultArray = [];
        foreach ($totalCountValues as $totalCountValue) {
            $clone = clone $sourceResult;
            $clone
                ->method('generator')
                ->willReturn($this->getGenerator([$totalCountValue]));
            $clone
                ->method('getTotalCount')
                ->willReturn($totalCountValue);
            $sourceResultArray[] = $clone;
        }

        $offsetResult = new OffsetResult($this->getGenerator($sourceResultArray));
        $generator = $offsetResult->generator();
        while ($generator->valid()) {
            $generator->current();
            $generator->next();
        }

        $this->assertEquals($expectsCount, $offsetResult->getTotalCount());
    }

    /**
     * @return array
     */
    public function totalCountProvider(): array
    {
        return [
            [
                [8, 9, 10],
                10,
            ],
            [
                [],
                0,
            ],
            [
                [20, 0, 10],
                20,
            ],
            [
                [-1, -10],
                0,
            ],
        ];
    }

    /**
     * @param $value
     *
     * @return \Generator
     */
    protected function getGenerator(array $value)
    {
        foreach ($value as $item) {
            yield $item;
        }
    }
}
