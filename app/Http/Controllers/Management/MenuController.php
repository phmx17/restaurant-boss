<?php

namespace App\Http\Controllers\Management;

use App\Menu;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $menus = Menu::all();
      return view('management.menu')->with('menus', $menus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $categories = Category::all();
      return view('management.createMenu')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
      $validated = $request->validate([
        'name' => ['bail','required', 'unique:menus', 'max:140'],
        'price' => ['required', 'numeric'],
        'category_id' => ['required', 'numeric']
      ]);
      // storing image
      $imageName = 'noimage.png';   // default if no image
        if ($request->image){
          $request->validate([
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg|max:5000'
          ]);
          $imageName = date('mdYHis').uniqid() . '.' . $request->image->extension();  // create unique image name
          $request->image->move(public_path('menu_images'), $imageName);  // move image to public-folder for storage
        };

      $menu = new Menu();
      $menu->name = $request->name;  
      $menu->price = $request->price;
      $menu->image = $imageName;
      $menu->description = $request->description;
      $menu->category_id = $request->category_id;
      $menu->save();
      $request->session()->flash('status', $request->name . ' was saved successfully');
      return redirect('/management/menu');
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
      $menu = Menu::find($id);
      $categories = Category::all();
      return view('management/editMenu')->with('menu', $menu)->with('categories', $categories); 
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
        'name' => 'required|max:255',
        'price' => 'required|numeric',
        'category_id' => 'required|numeric',
      ]);
      // handle image
      $menu = Menu::find($id);
      if($request->image) {
        $request->validate([
          'image' => 'nullable|file|image|mimes:jpeg,png,jpg|max:5000'
        ]);
        // Delete current image before saving new one
        if($menu->image != "noimage.png"){  // unless there is no image to begin with in which case don't delete anything
          $imageName = $menu->image;
          unlink(public_path('menu_images') . '/' . $imageName);  // delete
        }
        $imageName = date('mdYHis').uniqid() . '.' . $request->image->extension(); 
        $request->image->move(public_path('menu_images'), $imageName);
      } else {
        $imageName = $menu->image;  // image has not changed therefore continue to use current image (name) as in database
      }
      // assign vals to model
      $menu->name = $request->name;
      $menu->price = $request->price;
      $menu->image = $imageName;
      $menu->description = $request->description;
      $menu->category_id = $request->category_id;
      $menu->save();

      // flash and redirect
      $request->session()->flash('status', $request->name . ' was updated successfully');
      return redirect('/management/menu');    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)  // getting Request in order to flash it
    {
      $menu = Menu::find($id);
      if($menu->image != 'noImage.png') {
        unlink(public_path('menu_images') . '/' . $menu->image);
      }
      Session()->flash('status', $menu->name . ' was deleted successfully' );
      $menu->delete();
      return redirect('/management/menu');
    }
}
