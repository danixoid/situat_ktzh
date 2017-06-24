<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{

    protected static function boot()
    {
        parent::boot();

        static::created(
            function($exam) {
                $quests = \App\Quest::where('position_id',$exam->position_id)
                    ->inRandomOrder()
                    ->take($exam->count)
                    ->get();

                foreach($quests as $quest)
                {
                    \App\Ticket::create([
                        'exam_id' => $exam->id,
                        'quest_id' => $quest->id
                    ]);
                }
            }
        );

        static::deleting(
            function($exam)
            {
                $exam->tickets()->delete();
            }
        );
    }

    protected $fillable = ['position_id','user_id','chief_id','count','mark','note'];

    public function user()
    {
        return $this->belongsTo(\App\User::class,"user_id");
    }

    public function chief()
    {
        return $this->belongsTo(\App\User::class,"chief_id");
    }

    public function quests()
    {
        return $this->hasManyThrough(\App\Quest::class,
            \App\Ticket::class,"exam_id",
            "id","id");
    }

    public function position()
    {
        return $this->belongsTo(\App\Position::class);
    }

    public function tickets()
    {
        return $this->hasMany(\App\Ticket::class);
    }

}
