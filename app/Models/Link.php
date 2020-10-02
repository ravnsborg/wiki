<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Link extends Model
{
    protected $table = 'links';
    protected $userId = null;
    protected $entityId = null;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->userId = Auth::user()->id;
        $this->entityId = Auth::user()->preferred_entities_id;
    }

    public function create($requestParams = [])
    {
        return $this->insert(
            [
                'entities_id' => $this->entityId,
                'title' => htmlentities($requestParams['title']),
                'url' => filter_var($requestParams['url'], FILTER_SANITIZE_URL),
                'created_at' => Carbon::now()
            ]
        );
    }

    /*
     * Retrieve links for this specific entity
     */
    public function getEntitiesLinks()
    {
        return $this->where('entities_id', $this->entityId)
            ->orderBy('title')
            ->get();

    }

    public function destroyRecord($linkId)
    {
        return $this->find($linkId)->delete();
    }

    public function updateRecord($linkId, $requestParams = [])
    {
        return $this->where('id', $linkId)
            ->update([
                'title' => htmlentities($requestParams['edit_title']),
                'url' => filter_var($requestParams['edit_url'], FILTER_SANITIZE_URL),
                'updated_at' => Carbon::now()
            ]);

    }
}
