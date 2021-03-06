<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Article extends Model
{
    protected $table = 'articles';
    protected $userId = null;
    protected $entityId = null;

    protected $fillable = ['categories_id', 'title', 'body', 'url', 'sort_order'];


    private $keywords = [
        '/--' => '---------------------------------------------------------------------------------------------------------------',
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->userId = Auth::user()->id;
        $this->entityId = Auth::user()->preferred_entities_id;
    }

    public function create($categoryId, $title, $body, $referenceUrl = null)
    {
        return $this->insert(
            [
                'categories_id' => $categoryId,
                'title' => htmlentities($title),
                'body' => htmlentities($this->convertKeywords($body)),
                'url' => htmlentities($referenceUrl),
                'created_at' => Carbon::now()
            ]
        );
    }


    public function updateRecord($categoryId, $contentId, $title, $body, $referenceUrl = null)
    {
        return $this->where('id', $contentId)
        ->update([
            'title' => htmlentities($title),
            'body' => htmlentities($this->convertKeywords($body)),
            'url' => htmlentities($referenceUrl),
            'categories_id' => htmlentities($categoryId),
            'updated_at' => Carbon::now()
        ]);

    }

    /*
     * Updates article's `is_favorite` value with
     * the opposite boolean value currently set
     */
    public function toggleFavorite($contentId)
    {
        $article = $this->find($contentId);

        $this->where('id', $contentId)
            ->update([
                'is_favorite' => !$article->is_favorite,
                'updated_at' => Carbon::now()
        ]);

        return !$article->is_favorite;
    }

    public function getById($contentId)
    {
        return $this->find($contentId);
    }

    /**
     * Get all content for a
     * specific category or article
     * dependant on which 'entity' type
     * was passed in.
     *
     * @param $categoryId
     * @return mixed
     */
//    public function getByCategoryId($categoryId)
    public function getByEntityTypesId($id, $tableName)
    {
        if (!in_array($tableName, ['articles', 'categories']) ){
            return [];
        }

        return $this->select(
            'articles.id',
            'articles.title',
            'articles.categories_id',
            'articles.body',
            'articles.url',
            'articles.is_favorite',
            'articles.updated_at',
            'categories.title AS wiki_category_title'
        )
        ->join('categories', 'categories.id', '=', 'articles.categories_id')
        ->where("{$tableName}.id", $id)
        ->get();
    }

    /**
     * Get all content with the requested
     * string of characters.
     *
     * @param $searchString
     * @return mixed
     */
    public function getBySearchString($searchString, $searchAllEntities = false)
    {

        $query = $this->select(
            'articles.id',
            'articles.title',
            'articles.categories_id',
            'articles.body',
            'articles.url',
            'articles.is_favorite',
            'articles.updated_at',
            'categories.title AS wiki_category_title'
            )
            ->join('categories', 'categories.id', 'articles.categories_id');

            if (!$searchAllEntities){
                $query = $query
                    ->join('entities', 'entities.id', 'categories.entities_id')
                    ->where('categories.entities_id',  $this->entityId)
                    ->where('entities.users_id', $this->userId);
            }

            $query = $query->where(function($query) use ($searchString) {
                $query->where('articles.body', 'LIKE', "%{$searchString}%")
                      ->orWhere('articles.title', 'LIKE', "%{$searchString}%");
            })
            ->get();

            return $query;
    }


    /*
     * Replace special keywords into
     * meaningful syntax in content
     */
    public function convertKeywords($text)
    {
        foreach ($this->keywords as $key => $value){
            $text = str_replace($key, $value, $text);
        }

        return $text;
    }

    /*
     * Return array of all keywords
     * that can be converted
     */
    public function getKeywords()
    {
        $kewords = [];

        foreach ($this->keywords as $key => $value){
            $keywords[$key] = substr($value,0,28);
        }
        return $keywords;
    }

    /*
     * Return array of all favorite articles
     */
    public function getFavorites()
    {
        return $this->select(
            'articles.id',
            'articles.title',
            'articles.is_favorite',
            'categories.title AS categories_title'
        )
        ->join('categories', 'categories.id', '=', 'articles.categories_id')
        ->where('is_favorite', 1)
        ->orderBy('categories.title')
        ->get();
    }

    /**
     * Return category object the content belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }


    public function destroyRecord($contentId)
    {
        return $this->find($contentId)->delete();
    }
}





