<?php

namespace App\Http\Controllers\Management;

use App\Table;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $tables = Table::all();
      return view('management.table')->with('tables', $tables);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('management.createTable');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
        'name' => 'required|unique:tables|max:17'
      ]);
      $table = new Table();
      $table->name = $request->name;
      $table->save();
      $request->session()->flash('status', 'Table: ' . $request->name . ' was created successfully');
      return redirect('/management/table');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $table = Table::find($id);
      return view('management.editTable')->with('table', $table);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $request->validate([
        'name' => 'required|unique:tables|max:17'
      ]);
      $table = Table::find($id);
      $table->name = $request->name;
      $table->save();
      $request->session()->flash('status', 'Table: ' . $table->name . ' has been updated');
      return redirect('/management/table');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $table = Table::find($id);
      Session()->flash('status', 'Table: ' . $table->name . ' was deleted successfully');
      $table->delete();
      return redirect('/management/table');
    }
}
