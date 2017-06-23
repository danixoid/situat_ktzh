<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{

    protected $fillable = ['position_id','user_id','chief_id','count','mark','note'];

    public function user()
    {
        return $this->belongsTo(\App\User::class,"user_id");
    }

    public function chief()
    {
        return $this->belongsTo(\App\User::class,"chief_id");
    }

    public function quest()
    {
        return $this->belongsTo(\App\Quest::class);
    }

    public function position()
    {
        return $this->belongsTo(\App\Position::class);
    }

}
