<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;
use App\Models\Link;
use App\Models\User;
use App\Models\Entity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $content = new Article;



        return view('index', [
            'categoryList' => (new Category)->getList(),
            'content' => [],
            'links' => Link::all()->sortBy('title'),
            'usersEntitiesList' => (new Entity)->getUsersEntities(),
            'entitySelected' => (new Entity)->getSelectedEntityName(),
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
            $content = (new Article)->getByCategoryId($request->category_id);
        }elseif ($request->has('search_string')){
            $content = (new Article)->getBySearchString($request->search_string, $request->search_all_entities);
        }else{
            $content = null;
        }

        if ($content){
            $categoryUniqueListing = $content->unique()->keyBy('wiki_category_title')->all();
        }else{
            $categoryUniqueListing = null;
        }

        $view = view('articles')->with('content', $content->sortBy('title'))->renderSections()['content'];


        return response()->json(['html'=>$view, 'categoryList'=>$categoryUniqueListing]);
    }


    /*
     * Assign a preferred entity id to the current user
     * and redirect to homepage to load these categories
     */
    public function assignEntitySelection($entityId)
    {
        (new User)->assignPreferredEntityId($entityId);

        return redirect('/');
    }


}
