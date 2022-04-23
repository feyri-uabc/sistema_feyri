<?php

namespace App\Http\Controllers;

use App\Models\CalendarCourses;
use Illuminate\Http\Request;

class CalendarCoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CalendarCourses::orderBy("id")->get();
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
            'description' => 'required'
        ]);

        $course = new CalendarCourses([
            "name" => $request->input('name'),
            "description" => $request->input('description')
        ]);

        $course->save();
        return $course;
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
            'description' => 'required'
        ]);

        $course = CalendarCourses::find($id);
        return $course->update($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CalendarCourses::findorFail($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = CalendarCourses::find($id);
        if ($course->delete()) return "Deleted successfully";
    }
}
