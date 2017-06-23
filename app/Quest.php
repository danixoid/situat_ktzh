<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    protected $fillable = ['author_id','position_id','source','task','timer'];

    protected $appends = ['shortSource','shortTask'];

    public function author()
    {
        return $this->belongsTo("App\User");
    }

    public function position()
    {
        return $this->belongsTo("App\Position");
    }

    public function getShortSourceAttribute() {
        return mb_substr(mb_ereg_replace("<[^>]+>","",$this->source),0,50);
    }

    public function getShortTaskAttribute() {
        return mb_substr(mb_ereg_replace("<[^>]+>","",$this->task),0,50);
    }
}
