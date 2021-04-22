<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $categories = Category::paginate(5);
      return view('management.category')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('management.createCategory');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // validation
      $validatedData = $request->validate([
        // 'name' => 'bail|required|unique:categories|max:55'
        'name' => ['bail','required', 'unique:categories', 'max:140'] // 'bail' means stop at the first failure; nested attrib: author.name ; author.description
      ]); 

      $category = new Category();
      $category->name = $request->name;
      $category->save();
      $request->session()->flash('status', $request->name . ' was saved successfully');
      return(redirect('/management/category'));
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
      $category = Category::find($id);
      return view('management.editCategory')->with('category', $category);
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
      // validation
      $request->validate([        
        'name' => ['bail','required', 'unique:categories', 'max:140'] // 'bail' means stop at the first failure; nested attrib: author.name ; author.description
        ]);
      $category = Category::find($id);
      $category->name = $request->name; // apply changes
      $category->save();
      // flash and redirect
      $request->session()->flash('status', $request->name . ' was updated successfully');
      return(redirect('/management/category'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
      $request->session()->flash('status', $request->name . ' was deleted successfully');
      Category::destroy($id);
      return redirect('/management/category');
    }
}
