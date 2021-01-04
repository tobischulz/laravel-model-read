<?php

namespace TobiSchulz\ModelRead\Models;

use Illuminate\Database\Eloquent\Model;

class Read extends Model
{
    protected $table = 'model_reads';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
