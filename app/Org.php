<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
    protected $fillable = ['org_id','name'];

    protected $appends = ['orgPath'];

    public function parent() {
        return $this->belongsTo(\App\Org::class,'org_id');
    }

    public function children() {
        return $this->hasMany(\App\Org::class);
    }

    public function positions() {
        return $this->hasMany(\App\Position::class);
    }

    public function getOrgPathAttribute()
    {
        if($this->org_id != null) {
            return $this->recursiveOrg($this->org_id, $this->name);
        }

        return $this->name;
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
