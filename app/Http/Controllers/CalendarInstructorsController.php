<?php

namespace App\Http\Controllers;

use App\Models\CalendarInstructors;
use Illuminate\Http\Request;

class CalendarInstructorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): \Illuminate\Http\Response
    {
        return CalendarInstructors::orderBy("name")->get();
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
            'name' => 'string|required',
            'contact' => 'required'
        ]);

        $instructor = new CalendarInstructors([
            "name" => $request->input('title'),
            "contact" => $request->input('title')
        ]);

        $instructor->save();
        return $instructor;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CalendarInstructors::findorFail($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $instructor = CalendarInstructors::find($id);
        if ($instructor->delete()) return "Deleted successfully";
    }
}
