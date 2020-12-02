<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ArticlesController extends Controller
{

    /*
     * Display specific article
     */
    public function index($id=null)
    {
        if ($id){
            $content = (new Article)->getByEntityTypesId($id, 'articles');
        }else{
            $content = null;
        }

        $view = view('articles')->with('content', $content->sortBy('title'))->renderSections()['content'];

        return response()->json(['html'=>$view]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json(['category_list'=> (new Category)->getList() ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = (new Article)->create($request->category_id, $request->wiki_title, $request->wiki_content, $request->reference_url);

        return response()->json(['success'=>$result]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Wiki\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show(Article $content)
    {
        $content->title = html_entity_decode($content->title);
        $content->body = html_entity_decode($content->body);

        return response()->json(['content_record'=>$content, 'category_list'=> (new Category)->getList() ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wiki\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $contentId)
    {
        $content = new Article();

        $result = $content->updateRecord($request->category_id, $contentId, $request->wiki_title, $request->wiki_content, $request->reference_url);

        return response()->json(['success'=>$result]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wiki\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function toggleFavorite($contentId)
    {
        $content = new Article();

        $newFavoriteSetting = $content->toggleFavorite($contentId);

        $categoryList = (new Category)->getList();
        $keywords = $content->getKeywords();
        $favorites = $content->getFavorites();

        $view = view('categories', ['categoryList'=>$categoryList, 'keywords'=>$keywords, 'favorites'=>$favorites])->renderSections()['categories'];
//        $view = view('favorites', ['favorites'=>$favorites])->renderSections()['favorites'];


        return response()->json(['success'=>true, 'newValue' => $newFavoriteSetting, 'html'=>$view]);


    }
//    public function toggleFavorite($contentId)
//    {
//        $content = new Article();
//
//        $newFavoriteSetting = $content->toggleFavorite($contentId);
//
//        return response()->json(['success'=>true, 'newValue' => $newFavoriteSetting]);
//
//
//    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wiki\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = (new Article)->destroyRecord($id);

        return response()->json(['success'=>$result]);
    }
}
