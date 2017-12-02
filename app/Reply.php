<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A reply has an owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
        // It is necessary to make the foreign key is explicit.

        //return $this->belongsTo(User::class);
        //To use the command like this, the public function's name has to be user
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited'); //You should check the name at migration file '$table->unsignedInteger('favorited_id');'
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists())
        {
            return $this->favorites()->create($attributes);
        }
    }
}
