<?php

namespace Esa\Helper\Traits;

trait EnumToArray
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_combine(self::values(), self::names());
    }

    /**
     * return matching name for values passed as an argument
     * @param array<int,int> $value
     * @return array<int,string>
     */
    public static function valuesInArray(array $value): array
    {
        $valArray = self::toArray();
        return array_intersect_key($valArray, array_flip($value));
    }

    /**
     * return matching value for the names passed as an argument
     * @param array<int,string> $names
     * @return array<string,int>
     */
    public static function namesInArray(array $names): array
    {
        $valArray = self::toArray();
        return array_flip(array_intersect($valArray, $names));
    }

    public static function getNameFromValue($value)
    {
        $names = self::toArray();
        return $names[$value] ?? null;
    }

    public static function getValueByName($name)
    {
        $values = array_flip(self::toArray());
        return $values[$name] ?? null;
    }

    public static function nameExists($name)
    {
        $values = array_flip(self::toArray());
        return array_key_exists($name, $values);
    }

    public static function valueExists($value)
    {
        $names = self::toArray();
        return array_key_exists($value, $names);
    }
}
