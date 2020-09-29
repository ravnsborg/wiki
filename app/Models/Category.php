<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Category extends Model
{
    protected $table = 'wiki_category';

    protected $fillable = ['title', 'entity_id'];

    public function getList()
    {
        return $this->select('id', 'title')->orderBy('title')->get();
    }

    public function findExistingByTitle($title)
    {
        return $this->where('title', $title)->exists();
    }

    public function createAndReturnId($title)
    {
        return $this->insertGetId(
            [
                'title' => $title,
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
        return $this->hasMany(Content::class, 'wiki_category_id');
    }

}
