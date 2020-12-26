<?php

namespace Co2Control\Enumerables;

abstract class Enum implements \JsonSerializable
{
    /** @var string */
    protected $value;

    public function __construct(string $value = null)
    {
        static::assert($value);

        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getValue() ?: '';
    }

    /** @return string|null */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }

    /**
     * @return bool
     */
    public function is(string $name = null)
    {
        return $this->value === $name;
    }

    /**
     * @param string[] $names
     *
     * @return bool
     */
    public function isAny(array $names)
    {
        foreach ($names as $name) {
            if ($this->value == (string) $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isNot(string $name = null)
    {
        return $this->value !== $name;
    }

    /**
     * @return bool
     */
    public function isNotAny(array $names)
    {
        return ! $this->isAny($names);
    }

    /**
     * @return string[]
     */
    public static function getValues(): array
    {
        $oClass = new \ReflectionClass(get_called_class());

        return array_values($oClass->getConstants());
    }

    /**
     * @return string[]
     */
    public static function getTranslatedValues(): array
    {
        return array_combine(
            static::getValues(),
            array_map(
                function (?string $value) {
                    return (new static($value))->translate();
                },
                static::getValues()
            )
        );
    }

    /**
     * @return Enum[]
     */
    public static function getObjects(): array
    {
        return array_map(function (?string $value) {
            return new static($value);
        }, array_filter(static::getValues()));
    }

    /**
     * This requires the Laravel trans function so don't use it outside Laravel "context".
     */
    public function translate(): string
    {
        $oClass = get_called_class();
        $value = $this->value;

        return (string) trans("enum.$oClass.$value");
    }

    ///////////////////////
    // FACTORY

    /**
     * @throws \InvalidArgumentException
     *
     * @return Enum|mixed
     */
    public static function fromString(string $name = null)
    {
        return new static ($name);
    }

    protected static function assert(string $name = null)
    {
        if (! in_array($name, static::getValues(), true)) {
            $oClass = new \ReflectionClass(get_called_class());

            throw new \InvalidArgumentException('enum.' . camel_case($oClass->getShortName()) . '.notFound');
        }
    }
}
