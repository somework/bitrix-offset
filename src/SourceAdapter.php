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

abstract class SourceAdapter extends AbstractSource
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var object
     */
    protected $source;

    /**
     * @var string
     */
    protected $totalCountMethod;

    /**
     * @return SourceResultInterface
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function execute()
    {
        if (!is_callable($this->callback)) {
            throw new \RuntimeException('You should set callback to get source first');
        }
        if (!$this->totalCountMethod) {
            throw new \RuntimeException('You should set method to get total count first');
        }
        $this->source = call_user_func($this->callback);
        if (!is_object($this->source)) {
            throw new \InvalidArgumentException(sprintf(
                'Source received by callback is not an object: %s',
                gettype($this->source)
            ));
        }
        if (!method_exists($this->source, $this->totalCountMethod)) {
            throw new \RuntimeException(sprintf(
                'Method %s does not exist in %s',
                $this->totalCountMethod,
                get_class($this->source)
            ));
        }
        $this->setTotalCount((int) call_user_func([$this->source, $this->totalCountMethod]));
        return $this->getSourceResult();
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @param string $totalCountMethod
     *
     * @return $this
     */
    public function setTotalCountMethod($totalCountMethod)
    {
        $this->totalCountMethod = $totalCountMethod;
        return $this;
    }

    /**
     * @return SourceResultInterface
     */
    abstract protected function getSourceResult();
}
