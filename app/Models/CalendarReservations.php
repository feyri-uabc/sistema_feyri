<?php

namespace App\Models;

use App\Models\Traits\HasSafeDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarReservations extends Model
{
    use HasFactory, HasSafeDates;

    protected $table = "calendar_reservations";
    protected $fillable = [
        "lab_id", "instructor_id", "course_id", "group_id",
        "select_year", "select_month", "select_day", "select_hour",
        "tipo", "grouping"
    ];

    public function lab(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CalendarLabs::class, 'lab_id', 'id');
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CalendarInstructors::class, 'instructor_id', 'id');
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CalendarGroups::class, 'group_id', 'id');
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CalendarCourses::class, 'course_id', 'id');
    }
}
