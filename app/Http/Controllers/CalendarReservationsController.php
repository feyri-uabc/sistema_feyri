<?php

namespace App\Http\Controllers;

use App\Models\CalendarReservations;
use Illuminate\Http\Request;

class CalendarReservationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CalendarReservations::orderBy("id")->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'lab_id' => 'integer|required',
            'instructor_id' => 'integer|required',
            'course_id' => 'integer|required',
            'group_id' => 'integer|required',
            'grouping' => 'integer|required',
            'select_year' => 'integer|required',
            'select_month' => 'integer|required',
            'select_day' => 'integer|required',
            'select_hour' => 'integer|required',
            'tipo' => 'string|required',
        ]);

        $reservation = new CalendarReservations([
            "lab_id" => $request->input('lab_id'),
            "instructor_id" => $request->input('instructor_id'),
            "course_id" => $request->input('course_id'),
            "group_id" => $request->input('group_id'),
            "grouping" => $request->input('grouping'),
            "select_year" => $request->input('select_year'),
            "select_month" => $request->input('select_month'),
            "select_day" => $request->input('select_day'),
            "select_hour" => $request->input('select_hour'),
            "tipo" => $request->input('tipo'),
        ]);

        $reservation->save();
        return $reservation;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CalendarReservations::find($id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reservation = CalendarReservations::find($id);
        if ($reservation->delete()) return "Deleted successfully";
    }
}
