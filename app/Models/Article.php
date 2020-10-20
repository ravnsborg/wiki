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
        '/-' => '-------------------------------------',
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

    public function getById($contentId)
    {
        return $this->find($contentId);
    }

    /**
     * Get all content for a specific category
     * @param $categoryId
     * @return mixed
     */
    public function getByCategoryId($categoryId)
    {

        return $this->select(
            'articles.id',
            'articles.title',
            'articles.categories_id',
            'articles.body',
            'articles.url',
            'articles.updated_at',
            'categories.title AS wiki_category_title'
        )
            ->join('categories', 'categories.id', '=', 'articles.categories_id')
            ->where('categories_id', $categoryId)
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
        return $this->keywords;
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





