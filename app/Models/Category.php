<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    //

    protected $fillable = ['title','status'];

       /**
     * Scope a query to only include popular users.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', 1);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class,'category_id');

    }

}
