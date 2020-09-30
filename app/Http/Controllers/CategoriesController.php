<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        request()->validate([
//            'new_category_title' => 'required|unique:wiki_category,title',
//            'new_content_title' => 'required'
//        ]);

//        $validator = Validator::make($request->all(), [
//            'new_category_title' => 'required|unique:wiki_category,title',
//            'new_content_title' => 'required',
//        ]);

//        if ($validator->fails()) {
//            //Todo this validator is working
//        }

        $content = ($request->has('new_content')) ? $request->new_content : null;

        $categoryTitleExists = (new Category)->findExistingByTitle($request->new_category_title);

        if ($categoryTitleExists) {
            flash("{$request->new_category_title} already exists and can't be readded")->error();
        } else {
            $newCategoryId = (new Category)->createAndReturnId($request->new_category_title);

            if ($newCategoryId) {
                $emptyContentRec = (new Article)->create($newCategoryId, $request->new_content_title, $content);

                flash("New category {$request->new_category_title} created")->success();
            }
        }

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
