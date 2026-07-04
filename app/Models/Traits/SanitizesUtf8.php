<?php

namespace App\Models\Traits;

trait SanitizesUtf8
{
    public function toArray()
    {
        $data = parent::toArray();
        array_walk_recursive($data, function (&$value) {
            if (is_string($value)) {
                $value = str_replace("\0", '', $value);
                if (strlen($value) > 5000) {
                    $value = substr($value, 0, 5000);
                }
                $clean = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
                $value = $clean !== false ? $clean : '';
            }
        });
        return $data;
    }
}
