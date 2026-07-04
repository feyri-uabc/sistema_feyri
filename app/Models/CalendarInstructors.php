<?php

namespace App\Models;

use App\Models\Traits\HasSafeDates;
use App\Models\Traits\SanitizesUtf8;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarInstructors extends Model
{
    use HasFactory, HasSafeDates, SanitizesUtf8;
    protected $fillable = ["name", "contact"];

    protected $table = "calendar_instructors";

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }

    public function setContactAttribute($value)
    {
        $this->attributes['contact'] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }
}
