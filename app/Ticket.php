<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['quest_id','exam_id','answer','started_at','finished_at'];

    public function exam() {
        return $this->belongsTo(\App\Exam::class);
    }

    public function quest() {
        return $this->belongsTo(\App\Quest::class);
    }
}
