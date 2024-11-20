<?php

namespace Esa\Helper\Enums;

use Esa\Helper\Traits\EnumToArray;

enum DoctorType: int
{
    use EnumToArray;
    case GMC = 1;
    case EU = 2;
    case GPHC = 3;
    case Test = 4;
    case IMC = 5;
    case NMC = 6;

    /**
     * Return full form of Doctor Type
     * @return string
     * @example echo DoctorType::GMC->description(); // "General Medical Council"
     */
    public function description(): string
    {
        return static::getDescription($this);
    }


    /**
     * @example DoctorType::getDescription(DoctorType::GMC); // "General Medical Council"
     */
    public static function getDescription(self $value): string
    {
        return match ($value) {
            DoctorType::GMC => 'General Medical Council',
            DoctorType::EU => 'EU',
            DoctorType::GPHC => 'General Pharmaceutical Council',
            DoctorType::Test => 'TEST',
            DoctorType::IMC => 'Irish Medical Council',
            DoctorType::NMC => 'Nursing and Midwifery Council',
            default => ''
        };
    }

    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            DoctorType::GMC => 'GMC',
            DoctorType::EU => 'EU',
            DoctorType::GPHC => 'GPhC',
            DoctorType::Test => 'Test',
            DoctorType::IMC => 'IMC',
            DoctorType::NMC => 'NMC',
            default => ''
        };
    }

    public static function toList(): array
    {
        $response = [];
        $items = self::cases();
        foreach ($items as $item) {
            $response[$item->value] = self::from($item->value)->label();
        }
        return $response;
    }
}
