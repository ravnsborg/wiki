<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    public $preferredEntitiesId = null;

    protected $table = 'categories';

    protected $fillable = ['title', 'entities_id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->preferredEntitiesId = Auth::user()->preferred_entities_id;
    }

    /*
     * Retrieve the categories for this entity
     */
    public function getList()
    {
        return $this->select('id', 'title')
            ->where('entities_id', $this->preferredEntitiesId)
            ->orderBy('title')
            ->get();
    }

    public function findExistingByTitle($title)
    {
        return $this->where('title', $title)
            ->where('entities_id', $this->preferredEntitiesId)
            ->exists();
    }

    public function createAndReturnId($title)
    {
        return $this->insertGetId(
            [
                'title' => $title,
                'entities_id' => $this->preferredEntitiesId,
                'created_at' => Carbon::now()
            ]
        );
    }


    /**
     * Returns all content that belongs to the category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function content()
    {
        return $this->hasMany(Article::class, 'categories_id');
    }

}
