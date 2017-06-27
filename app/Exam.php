<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{

    protected $fillable = ['position_id','user_id','chief_id','count','mark','note'];

    protected $appends = ['finished'];

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

    public function getFinishedAttribute()
    {
        $finish = true;
        foreach ($this->tickets as $ticket)
        {
            if($ticket->finished_at == null) {
                $finish = false;
            }
        }

        return $finish;
    }

}
