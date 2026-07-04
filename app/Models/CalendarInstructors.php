<?php

namespace App\Models;

use App\Models\Traits\HasSafeDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarInstructors extends Model
{
    use HasFactory, HasSafeDates;
    protected $fillable = ["name", "contact"];

    protected $table = "calendar_instructors";
}
