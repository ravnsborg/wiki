<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'users_id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->userId = Auth::user()->id;
        $this->entityId = Auth::user()->preferred_entities_id;
    }

    /*
     * Get all entities associated to this user
     */
    public function getUsersEntities(){
        return $this->select('id', 'title')
            ->where('users_id', $this->userId)
            ->orderBy('title')->get();
    }


    public function getSelectedEntityName()
    {
        return $this->select('title')
            ->join('users', 'users.preferred_entities_id', 'entities.id')
            ->where('users_id', $this->userId)
            ->first();
    }


    /*
     * Create a new entity record
     */
    public function create($requestParams = [])
    {
        return $this->insert(
            [
                'title' => htmlentities($requestParams['title']),
                'users_id' => $this->userId,
                'created_at' => Carbon::now()
            ]
        );
    }


    /*
     * Update the entity recorda
     */
    public function updateRecord($entityId, $requestParams = [])
    {
        return $this->where('id', $entityId)
            ->update([
                'title' => htmlentities($requestParams['edit_title']),
                'updated_at' => Carbon::now()
            ]);
    }

    /*
     * Delete entity record
     */
    public function destroyRecord($entityId)
    {
        return false;
//        return $this->find($entityId)->delete();
    }
}
