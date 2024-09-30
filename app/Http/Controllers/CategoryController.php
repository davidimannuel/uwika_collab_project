<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $categories = Category::where('user_id', Auth::id())->orderBy('updated_at','desc')->paginate(5);
      return view('category.index',[
        'categories' => $categories,
      ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
          'name' => ['required'],
        ]);
        $attributes['user_id'] = Auth::id();
        Category::create($attributes);

        return redirect(route('categories.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
      return view('category.edit',[
        'category' => $category
      ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
      $request->validate([
        'name' => ['required'],
      ]);
      $category->name = $request->input('name');
      $category->save();

      return redirect(route('categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
      $category->delete();
      return redirect(route('categories.index'));
    }
}