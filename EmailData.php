<?php

class EmailData
{
    public ArrayObject $attributes;

    /**
     * @param string $id
     * @param object $attributes
     * @param string $type
     */
    public function __construct(public string $id, object $attributes, public string $type = 'email')
    {
        $this->attributes = new ArrayObject();
        $this->addAttributes($attributes);
    }

    /**
     * @return ArrayObject
     */
    public function getAttributes(): ArrayObject
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addAttribute(string $name, string $value): self
    {
        $this->attributes->offsetSet($name, $value);
        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(object|array $attributes): self
    {
        foreach ($attributes as $name => $value) {

            //echo "<p>\$name = $name<br />\$value = $value</p>";

            $this->addAttribute($name, $value);
        }
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeAttribute(string $name): self
    {
        unset($this->attributes[key($attribute)]);
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

}