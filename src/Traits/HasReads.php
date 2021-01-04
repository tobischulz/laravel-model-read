<?php

namespace TobiSchulz\ModelRead\Traits;

use TobiSchulz\ModelRead\Models\Read;

trait HasReads
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
     * @return bool
     */
    public function read() : bool
    {
        if ($this->isRead()) {
            return false;
        }

        $this->reads()->create([
            'user_id' => auth()->id(),
        ]);

        return true;
    }

    /**
     * Removes a read, if it exists.
     *
     * @return bool
     */
    public function unread() : bool
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
     * @return bool
     */
    public function isRead() : bool
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
