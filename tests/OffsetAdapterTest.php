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

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_Matcher_Invocation;
use SomeWork\OffsetPage\OffsetAdapter;
use SomeWork\OffsetPage\SourceInterface;
use SomeWork\OffsetPage\SourceResultInterface;

class OffsetAdapterTest extends TestCase
{
    /** @noinspection MoreThanThreeArgumentsInspection */
    /**
     * @dataProvider resultCountProvider
     *
     * @param int                                             $offset
     * @param int                                             $limit
     * @param int                                             $totalCount
     * @param PHPUnit_Framework_MockObject_Matcher_Invocation $expects
     */
    public function testResultCount(int $offset, int $limit, int $totalCount, $expects, $consecutive)
    {
        /**
         * @var SourceInterface|\PHPUnit_Framework_MockObject_MockObject $sourceMock
         */
        $sourceMock = $this
            ->getMockBuilder(SourceInterface::class)
            ->setMethods(['execute'])
            ->getMock();

        $sourceMock
            ->expects($expects)
            ->method('execute')
            ->withConsecutive(... $consecutive)
            ->willReturn($this->buildSourceResult($totalCount));

        $adapter = new OffsetAdapter($sourceMock);
        $generator = $adapter->execute($offset, $limit)->generator();
        while ($generator->valid()) {
            $generator->current();
            $generator->next();
        }
    }

    /**
     * @return array
     */
    public function resultCountProvider(): array
    {
        return [
            'offset=0 limit>0' => [
                'offset'      => 0,
                'limit'       => 1,
                'totalCount'  => 10,
                'expects'     => $this->once(),
                'consecutive' => [
                    [
                        $this->equalTo(1),
                        $this->equalTo(1),
                    ],
                ],
            ],
            'offset>0 limit=0' => [
                'offset'      => 10,
                'limit'       => 0,
                'totalCount'  => 100,
                'expects'     => $this->once(),
                'consecutive' => [
                    [
                        $this->equalTo(2),
                        $this->equalTo(10),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $totalCount
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|SourceResultInterface
     */
    protected function buildSourceResult(int $totalCount): \PHPUnit_Framework_MockObject_MockObject
    {
        $sourceResultMock = $this
            ->getMockBuilder(SourceResultInterface::class)
            ->setMethods(['generator', 'getTotalCount'])
            ->getMock();

        $sourceResultMock
            ->method('generator')
            ->willReturn($this->getGenerator([]));

        $sourceResultMock
            ->method('getTotalCount')
            ->willReturn($totalCount);

        return $sourceResultMock;
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
