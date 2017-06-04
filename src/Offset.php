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

class Offset
{
    /**
     * @param int $offset
     * @param int $limit
     * @param int $nowCount
     *
     * @return array|null
     * @throws \LogicException
     */
    public static function logic($offset, $limit, $nowCount = 0)
    {
        $offset = (int) $offset;
        $limit = (int) $limit;
        $nowCount = (int) $nowCount;

        $offset = $offset >= 0 ? $offset : 0;
        $limit = $limit >= 0 ? $limit : 0;
        $nowCount = $nowCount >= 0 ? $nowCount : 0;

        /**
         * Means that you should get all
         */
        if ($offset === 0 && $limit === 0 && $nowCount === 0) {
            return ['page' => 0, 'size' => 0];
        }

        if ($offset === 0 && $limit === 0 && $nowCount > 0) {
            return ['page' => 2, 'size' => $nowCount];
        }

        if ($offset === 0 && $limit > 0 && $nowCount === 0) {
            return ['page' => 1, 'size' => $limit];
        }

        if ($offset > 0 && $limit === 0) {
            return ['page' => 2, 'size' => $nowCount + $offset];
        }

        if ($limit > 0 && $nowCount > 0) {
            if ($limit > $nowCount) {
                return static::logic($offset + $nowCount, $limit - $nowCount);
            }
            /**
             * Means that you should stop fetching
             */
            return null;
        }

        if ($offset > 0 && $limit > 0) {
            if ($offset === $limit) {
                return ['page' => 2, 'size' => $limit];
            }
            if ($offset < $limit) {
                return ['page' => 2, 'size' => $offset];
            }
            if ($offset > $limit) {
                for ($i = $limit; $i > 0; $i--) {
                    if ($offset % $i === 0) {
                        return ['page' => ($offset / $i) + 1, 'size' => $i];
                    }
                }
            }
        }

        /**
         * Really its can be here oO
         */
        throw new \LogicException('Something gone wrong');
    }
}
