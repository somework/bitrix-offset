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
use SomeWork\OffsetPage\Offset;

class OffsetTest extends TestCase
{
    /**
     * @param $offset
     * @param $limit
     * @param $nowCount
     * @param $expectedResult
     *
     * @dataProvider oneMoreThanZeroProvider
     */
    public function testOneMoreThanZero($offset, $limit, $nowCount, $expectedResult)
    {
        $result = Offset::logic($offset, $limit, $nowCount);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function oneMoreThanZeroProvider(): array
    {
        return [
            'offset=0;limit=0;nowCount=0;' => [
                'offset'         => 0,
                'limit'          => 0,
                'nowCount'       => 0,
                'expectedResult' => [
                    'page' => 0,
                    'size' => 0,
                ],
            ],
            'offset>0;limit=0;nowCount=0;' => [
                'offset'         => 22,
                'limit'          => 0,
                'nowCount'       => 0,
                'expectedResult' => [
                    'page' => 2,
                    'size' => 22,
                ],
            ],
            'offset=0;limit>0;nowCount=0;' => [
                'offset'         => 0,
                'limit'          => 10,
                'nowCount'       => 0,
                'expectedResult' => [
                    'page' => 1,
                    'size' => 10,
                ],
            ],
            'offset=0;limit=0;nowCount>0;' => [
                'offset'         => 0,
                'limit'          => 0,
                'nowCount'       => 33,
                'expectedResult' => [
                    'page' => 2,
                    'size' => 33,
                ],
            ],
        ];
    }

    /**
     * @param $offset
     * @param $limit
     * @param $nowCount
     * @param $expectedResult
     *
     * @dataProvider offsetZeroProvider
     */
    public function testOffsetZero($offset, $limit, $nowCount, $expectedResult)
    {
        $result = Offset::logic($offset, $limit, $nowCount);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function offsetZeroProvider(): array
    {
        return [
            'offset=0;limit>0;nowCount=0;'                => [
                'offset'         => 0,
                'limit'          => 22,
                'nowCount'       => 0,
                'expectedResult' => [
                    'page' => 1,
                    'size' => 22,
                ],
            ],
            'offset=0;limit=0;nowCount>0;'                => [
                'offset'         => 0,
                'limit'          => 0,
                'nowCount'       => 22,
                'expectedResult' => [
                    'page' => 2,
                    'size' => 22,
                ],
            ],
            'offset=0;limit>0;nowCount>0;limit<nowCount;' => [
                'offset'         => 0,
                'limit'          => 2,
                'nowCount'       => 10,
                'expectedResult' => null,
            ],
            'offset=0;limit>0;nowCount>0;limit=nowCount;' => [
                'offset'         => 0,
                'limit'          => 10,
                'nowCount'       => 10,
                'expectedResult' => null,
            ],
            'offset=0;limit>0;nowCount>0;limit>nowCount;' => [
                'offset'         => 0,
                'limit'          => 22,
                'nowCount'       => 10,
                'expectedResult' => [
                    'page' => 2,
                    'size' => 10,
                ],
            ],
        ];
    }

    /**
     * @param $offset
     * @param $limit
     * @param $nowCount
     * @param $expectedResult
     *
     * @dataProvider limitZeroProvider
     */
    public function testLimitZero($offset, $limit, $nowCount, $expectedResult)
    {
        $result = Offset::logic($offset, $limit, $nowCount);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function limitZeroProvider(): array
    {
        return [
            'offset>0;limit=0;nowCount=0;'                 => [
                'offset'         => 22,
                'limit'          => 0,
                'nowCount'       => 0,
                'expectedResult' => [
                    'page' => 2,
                    'size' => 22,
                ],
            ],
            'offset>0;limit=0;nowCount>0;nowCount<offset;' => [
                'offset'         => 22,
                'limit'          => 0,
                'nowCount'       => 2,
                'expectedResult' => [
                    'page' => 2,
                    'size' => 24,
                ],
            ],
            'offset>0;limit=0;nowCount>0;nowCount=offset;' => [
                'offset'         => 22,
                'limit'          => 0,
                'nowCount'       => 22,
                'expectedResult' => [
                    'page' => 2,
                    'size' => 44,
                ],
            ],
            'offset>0;limit=0;nowCount>0;nowCount>offset;' => [
                'offset'         => 22,
                'limit'          => 0,
                'nowCount'       => 40,
                'expectedResult' => [
                    'page' => 2,
                    'size' => 62,
                ],
            ],
        ];
    }

    /**
     * @param $offset
     * @param $limit
     * @param $nowCount
     * @param $expectedResult
     *
     * @dataProvider limitOffsetMoreThanZeroProvider
     */
    public function testLimitOffsetMoreThanZero($offset, $limit, $nowCount, $expectedResult)
    {
        $result = Offset::logic($offset, $limit, $nowCount);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function limitOffsetMoreThanZeroProvider(): array
    {
        return [
            'offset>0;limit>0;nowCount=0;' => [
                'offset'         => 22,
                'limit'          => 33,
                'nowCount'       => 0,
                'expectedResult' => [
                    'page' => 2,
                    'size' => 22,
                ],
            ],
            'offset>0;limit>0;nowCount>0;limit>nowCount;limit-nowCount<offset;' => [
                'offset'         => 22,
                'limit'          => 33,
                'nowCount'       => 13,
                'expectedResult' => [
                    'page' => 6,
                    'size' => 7,
                ],
            ],
            'offset>0;limit>0;nowCount>0;limit>nowCount;limit-nowCount=offset;' => [
                'offset'         => 22,
                'limit'          => 35,
                'nowCount'       => 13,
                'expectedResult' => [
                    'page' => 6,
                    'size' => 7,
                ],
            ],
            'offset>0;limit>0;nowCount>0;limit>nowCount;limit-nowCount>offset;' => [
                'offset'         => 22,
                'limit'          => 35,
                'nowCount'       => 1,
                'expectedResult' => [
                    'page' => 2,
                    'size' => 23,
                ],
            ],
            'offset>0;limit>0;nowCount>0;limit=nowCount;' => [
                'offset'         => 22,
                'limit'          => 33,
                'nowCount'       => 33,
                'expectedResult' => null,
            ],
            'offset>0;limit>0;nowCount>0;limit<nowCount;' => [
                'offset'         => 22,
                'limit'          => 32,
                'nowCount'       => 33,
                'expectedResult' => null,
            ],
        ];
    }
}
