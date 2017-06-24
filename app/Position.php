<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['org_id','name'];

    protected $appends = ['orgPath'];


    public function org()
    {
        return $this->belongsTo(\App\Org::class);
    }

    public function getOrgPathAttribute()
    {
        return $this->org->orgPath;
    }

    public function quests() {
        return $this->hasMany(\App\Quest::class);
    }

    public function exams() {
        return $this->hasMany(\App\Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

}
