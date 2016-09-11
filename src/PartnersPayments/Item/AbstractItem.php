<?php

namespace PartnersPayments\Item;

/**
 * Class Partner
 *
 * @package PartnersPayments\Item
 */
class AbstractItem
{
    protected $data = [];

    /**
     * AbstractItem constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $property
     *
     * @return mixed|null
     */
    public function __get($property)
    {
        return isset($this->data[$property]) ? $this->data[$property] : null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}
