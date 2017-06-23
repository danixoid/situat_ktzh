<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    protected $fillable = ['author_id','position_id','source','task','timer'];

    public function author()
    {
        return $this->belongsTo("App\User");
    }

    public function position()
    {
        return $this->belongsTo("App\Position");
    }
}
