<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Content;
use App\Models\Link;
use Illuminate\Support\Facades\Log;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display main page with list of
     * categories and defaulted wiki content
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->middleware('auth');
        $content = new Content;

        return view('index', [
            'categoryList' => (new Category)->getList(),
            'content' => $content->getByCategoryId(1),
            'links' => Link::all()->sortBy('label'),
            'keywords' => $content->getKeywords(),
        ]);
    }


    /*
     * Searchs on category id or keywords
     * to retrieve content
     */
    public function search(Request $request)
    {
        if ($request->has('category_id')){
            $content = (new Content)->getByCategoryId($request->category_id);
        }elseif ($request->has('search_string')){
            $content = (new Content)->getBySearchString($request->search_string);
        }else{
            $content = null;
        }

        if ($content){
            $categoryUniqueListing = $content->unique()->keyBy('wiki_category_title')->all();
        }else{
            $categoryUniqueListing = null;
        }

        $view = view('content')->with('content', $content->sortBy('title'))->renderSections()['content'];


        return response()->json(['html'=>$view, 'categoryList'=>$categoryUniqueListing]);
    }
}
