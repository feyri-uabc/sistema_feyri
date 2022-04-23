<?php

namespace App\Http\Controllers;

use App\Models\CalendarGroups;
use Illuminate\Http\Request;

class CalendarGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CalendarGroups::orderBy("id")->get();
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

        $group = new CalendarGroups([
            "name" => $request->input('name'),
            "description" => $request->input('description')
        ]);

        $group->save();
        return $group;
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

        $group = CalendarGroups::find($id);
        return $group->update($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CalendarGroups::findorFail($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = CalendarGroups::find($id);
        if ($group->delete()) return "Deleted successfully";
    }
}
