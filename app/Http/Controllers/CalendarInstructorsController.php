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
    public function index()
    {
	return CalendarInstructors::orderBy("id")->get();
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
            "name" => $request->input('name'),
            "contact" => $request->input('contact')
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
     * Example api/{controller}/{id}
     * Request contain vars
     *
     * @param Request $request
     * @param $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|required',
            'contact' => 'required'
        ]);
        
        $calendar = CalendarInstructors::find($id);
        return $calendar->update($request->all());
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
