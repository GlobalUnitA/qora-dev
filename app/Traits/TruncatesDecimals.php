<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait TruncatesDecimals
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($this->isDecimalValue($value)) {
            return rtrim(rtrim((string) $value, '0'), '.');
        }

        return $value;
    }

    protected static function bootTruncatesDecimals()
    {
        static::saving(function ($model) {
            foreach ($model->getAttributes() as $key => $value) {
                if ($model->isDecimalValue($value) && !is_null($value)) {
                    $model->$key = $model->truncateDecimal($value, 9);
                }
            }
        });
    }

    protected function isDecimalValue($value)
    {
        return is_float($value) || (is_numeric($value) && strpos((string)$value, '.') !== false);
    }

    protected function truncateDecimal($number, $precision = 9)
    {
        if (strpos((string)$number, '.') === false) {
            return $number;
        }

        $parts = explode('.', (string)$number);
        $decimal = substr($parts[1], 0, $precision);
        return $parts[0] . '.' . $decimal;
    }
}