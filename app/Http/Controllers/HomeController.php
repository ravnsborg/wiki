<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;
use App\Models\Link;
use App\Models\User;
use App\Models\Entity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use phpDocumentor\Reflection\File;


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
            'favorites' => $content->getFavorites(),
        ]);
    }


    /*
     * Searchs on category id or keywords
     * to retrieve content
     */
    public function search(Request $request)
    {
        if ($request->has('category_id')) {
            $content = (new Article)->getByEntityTypesId($request->category_id, 'categories');
        }elseif ($request->has('article_id')) {
            $content = (new Article)->getByEntityTypesId($request->article_id, 'articles');
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


    /*
     * Backup db to storage folder
     */
    public function db_dump()
    {
        $filePath = storage_path() . '/DB/';
        $fileName = env('DB_DATABASE') . '_' . Carbon::now()->format('Y-m-d_H') . '.sql';

        if (!file_exists($filePath)) {
            mkdir($filePath);
        }

        $fullPath = $filePath . $fileName;

        $result = exec('/Applications/MAMP/Library/bin/mysqldump --user='.env('DB_USERNAME').' --password='.env('DB_PASSWORD').' --host='.env('DB_HOST') . ' --port='.env('DB_PORT') . ' ' . env('DB_DATABASE').'  > ' . $fullPath);

        flash("Backup saved to {$fullPath}")->info();
        return redirect('/');
    }


}
