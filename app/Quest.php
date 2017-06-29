<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    protected $fillable = ['author_id'/*,'position_id','source'*/,'task','timer'];

    protected $appends = ['shortTask'];

    public function author()
    {
        return $this->belongsTo("App\User");
    }


    public function positions()
    {
        return $this
            ->belongsToMany(\App\Position::class,"position_quest")
            ->withTimestamps();
    }

    public function getShortTaskAttribute() {
        return mb_substr(mb_ereg_replace("<[^>]+>","",$this->task),0,50);
    }

    public function hasPosition($id)
    {
        if ($this->position()->find($id)) {
            return true;
        }

        return false;
    }
}
