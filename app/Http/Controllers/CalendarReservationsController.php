<?php

namespace App\Http\Controllers;

use App\Models\CalendarReservations;
use Illuminate\Http\Request;
use PHPUnit\Exception;
use Symfony\Component\Console\Output\ConsoleOutput;

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
        if ($request->input('multiple')) {
            try {
                $reservations = json_decode($request->input("reservations"), true);
                foreach ($reservations as $reservation) {
                    (new CalendarReservations([
                        "lab_id" => $reservation['lab_id'],
                        "instructor_id" => $reservation['instructor_id'],
                        "course_id" => $reservation['course_id'],
                        "group_id" => $reservation['group_id'],
                        "grouping" => $reservation['grouping'],
                        "select_year" => $reservation['select_year'],
                        "select_month" => $reservation['select_month'],
                        "select_day" => $reservation['select_day'],
                        "select_hour" => $reservation['select_hour'],
                        "tipo" => $reservation['tipo']
                    ]))->save();
                }
                return [];
            } catch (Exception $ignore) { return null; }
        }

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
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        if (!$request->input("grouping") || !$request->input("instructor_id") || !$request->input("course_id") || !$request->input("group_id")) {
            return null;
        }

        CalendarReservations::where('grouping', $request->input("grouping"))
            ->update([
                'instructor_id' => $request->input("instructor_id"),
                'course_id' => $request->input("course_id"),
            ]);

        $reservation = CalendarReservations::firstWhere('grouping', $request->input("grouping"));
        $status = $reservation->instructor_id == $request->input("instructor_id") && $reservation->course_id == $request->input("course_id");

        if ($status) return "status: successful";
        return null;
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
