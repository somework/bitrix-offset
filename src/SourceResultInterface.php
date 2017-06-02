<?php


namespace SomeWork\Bitrix\Offset;


interface SourceResultInterface
{
    /**
     * @return \Generator
     */
    public function generator();

    /**
     * @return int
     */
    public function getTotalCount();
}