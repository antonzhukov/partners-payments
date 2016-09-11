<?php

namespace PartnersPayments\Item;

abstract class AbstractCollection implements \Iterator, \Countable
{
    /**
     * Array of Item objects
     * @var array
     */
    protected $items;

    /**
     * AbstractCollection constructor.
     *
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Function of Iterator interface
     * @return mixed
     */
    public function rewind()
    {
        return empty($this->items) ? false : reset($this->items);
    }

    /**
     * Function of Iterator interface
     * @return mixed
     */
    public function next()
    {
        return empty($this->items) ? false : next($this->items);
    }

    /**
     * Function of Iterator interface
     * @return mixed
     */
    public function key()
    {
        return empty($this->items) ? false : key($this->items);
    }

    /**
     * Function of Iterator interface
     * @return mixed
     */
    public function current()
    {
        return empty($this->items) ? false : current($this->items);
    }

    /**
     * Function of Iterator interface
     * @return bool
     */
    public function valid()
    {
        return empty($this->items) ? false : current($this->items) !== false;
    }

    /* Function of Countable interface
     * @return int
     */
    /**
     * @return int
     */
    public function count()
    {
        return empty($this->items) ? 0 : count($this->items);
    }

    /**
     * @return object
     */
    public function last()
    {
        return $this->count() ? $this->items[$this->count() - 1] : null;
    }

    /**
     * @return object
     */
    public function first()
    {
        return $this->count() ? $this->items[0] : null;
    }
}
