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
        CalendarReservations::orderBy("id")->get();
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
            'lab' => 'integer|required',
            'instructor' => 'integer|required',
            'year' => 'integer|required',
            'month' => 'integer|required',
            'day' => 'integer|required',
            'hour' => 'integer|required',
        ]);

        $reservation = new CalendarReservations([
            "lab_id" => $request->input('lab'),
            "instructor_id" => $request->input('instructor'),
            "select_year" => $request->input('year'),
            "select_month" => $request->input('month'),
            "select_day" => $request->input('day'),
            "select_hour" => $request->input('hour'),
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
