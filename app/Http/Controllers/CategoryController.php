<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('id')) {
            $request->validate([
                'name' => 'required|min:5',
                'description' => 'required',
            ]);

            $category = Category::find($request->id);
            if (isset($category)) {
                $category->update([
                    'name' => $request->name,
                    'description' => $request->description,
                ]);

                echo json_encode([
                    'id' => $request->id,
                    'name' => $request->name,
                    'description' => $request->description,
                ]);
                exit();
            }
        }

        $request->validate([
            'name' => 'required|min:5',
            'description' => 'required',
        ]);

        $category = Category::create($request->all());
        echo json_encode([
            'name' => $request->name,
            'description' => $request->description,
            'id' => $category->id
        ]);
        exit();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        echo json_encode([
            'name' => $category->name,
            'description' => $category->description,
            'id' => $category->id,
        ]);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $categoryName = $category->name;
        $category->delete();
        echo json_encode([
            'message' => "Category deleted of name - " . $categoryName,
        ]);
    }
}
