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
        if($this->org->org_id != null) {
            return $this->recursiveOrg($this->org->org_id, $this->org->name);
        }

        return $this->org->name;
    }

    public function quests() {
        return $this->hasMany(\App\Quest::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function recursiveOrg($org_id,$path) {
        $org = \App\Org::find($org_id);

        $path = $org->name . "/" . $path;

        if($org->org_id != null) {
            return $this->recursiveOrg($org->org_id, $path);
        }

        return $path;
    }
}
