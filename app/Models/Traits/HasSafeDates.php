<?php

namespace App\Models\Traits;

use Carbon\Exceptions\InvalidFormatException;

trait HasSafeDates
{
    protected function asDateTime($value)
    {
        try {
            return parent::asDateTime($value);
        } catch (InvalidFormatException | \ValueError $e) {
            return now();
        }
    }
}
