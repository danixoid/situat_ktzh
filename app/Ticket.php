<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['quest_id','exam_id','answer','finished_at'];

    public function exams() {
        return $this->hasMany(\App\Exam::class);
    }

    public function quest() {
        return $this->belongsTo(\App\Quest::class);
    }
}
