<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Calendar;
use App\Models\CalendarLabs;
use Illuminate\Http\Request;

class CalendarLabsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CalendarLabs::orderBy("name")->get();
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
            'description' => 'string|required'
        ]);

        $lab = new CalendarLabs([
            "name" => $request->input('name'),
            "description" => $request->input('description')
        ]);

        $lab->save();
        return $lab;
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

        $lab = CalendarLabs::find($id);
        return $lab->update($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CalendarLabs::findorFail($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lab = CalendarLabs::find($id);
        if ($lab->delete()) return "Deleted successfully";
    }
}
