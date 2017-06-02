<?php

namespace SomeWork\Bitrix\Offset;

interface SourceInterface
{
    /**
     * @param $page
     * @param $pageSize
     *
     * @return SourceResultInterface
     */
    public function execute($page, $pageSize);
}