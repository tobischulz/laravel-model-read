<?php

namespace TobiSchulz\ModelRead;

use phpDocumentor\Reflection\Types\Boolean;
use TobiSchulz\ModelRead\Models\Read;

trait HasRead
{
    /**
     * Collection of reads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reads()
    {
        return $this->morphMany(Read::class, 'read')
            ->orderByDesc('created_at');
    }

    /**
     * Store a new read for this model and current user.
     *
     * @return TobiSchulz\ModelRead\Models\Read
     */
    public function read() : Read
    {
        return $this->reads()->create([
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Removes a read, if it exists.
     *
     * @return Boolean
     */
    public function unread() : Boolean
    {
        $read = $this->reads()->where([
            'user_id' => auth()->id(),
        ])->first();

        if (!$read) {
            return false;
        }

        return $read->delete();
    }

    /**
     * Checks if the current user has read this model.
     *
     * @return Boolean
     */
    public function isRead() : Boolean
    {
        $read = $this->reads()->where([
            'user_id' => auth()->id(),
        ]);

        return $read->exists();
    }

    /**
     * Hooking in delete method to delete all polymorph relationships.
     *
     * @return void
     */
    protected static function bootHasRead() : void
    {
        self::deleting(function ($model) {
            $model->reads()->delete();
        });
    }
}
