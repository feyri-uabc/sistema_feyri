<?php

namespace App\Models;

use App\Models\Traits\HasSafeDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarGroups extends Model
{
    use HasFactory, HasSafeDates;
    protected $fillable = ["name", "description"];

    protected $table = "calendar_groups";
}
