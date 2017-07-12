<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    protected $fillable = ['author_id','task','timer'];

    protected $appends = ['shortTask'];

    public function author()
    {
        return $this->belongsTo("App\User");
    }

    public function tickets()
    {
        return $this->hasMany(\App\Ticket::class);
    }


    public function self()
    {
        return $this
            ->belongsToMany(\App\Quest::class,"position_quest")
            ->withPivot("func_id", "position_id", "org_id")
            ->withTimestamps();
    }

    public function orgs()
    {
        return $this
            ->belongsToMany(\App\Org::class,"position_quest")
            ->withPivot("func_id", "position_id")
            ->withTimestamps();
    }

    public function funcs()
    {
        return $this
            ->belongsToMany(\App\Func::class,"position_quest")
            ->withPivot("org_id", "position_id")
            ->withTimestamps();
    }

    public function positions()
    {
        return $this
            ->belongsToMany(\App\Position::class,"position_quest")
            ->withPivot("org_id", "func_id")
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
