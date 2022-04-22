<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarReservations extends Model
{
    use HasFactory;

    protected $table = "calendar_reservations";
    protected $guarded = ["id"];


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
