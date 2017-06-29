<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Exam extends Model
{

    protected $fillable = ['position_id','user_id','chief_id',
        'count','mark','note'];

    protected $hidden = ['signs'];

    protected $appends = ['started','finished','color','amISigner'];

    protected static function boot()
    {
        parent::boot();

        static::created(
            function($exam) {
                $quests = \App\Position::find($exam->position_id)
                    ->quests()
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

        static::updated(
            function($exam)
            {
                if($exam->finished)
                {
                    Mail::queue('email.exam_finished',compact($exam),
                        function($message) use ($exam){
                            $message->subject('Коммерческое предложение от B-Apps LLP');
                            $message->to($exam->chief->email);
                            $message->from(env("MAIL_USERNAME","danixoid@gmail.com"),
                                'Администратор SITUAT.KZ');
                        });
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

    public function signs()
    {
        return $this->hasMany(\App\Sign::class);
    }

    public function position()
    {
        return $this->belongsTo(\App\Position::class);
    }

    public function tickets()
    {
        return $this->hasMany(\App\Ticket::class);
    }

    public function quests()
    {
        return $this->hasManyThrough(\App\Quest::class,
            \App\Ticket::class,"exam_id",
            "id","id");
    }

    public function getStartedAttribute()
    {
        $finish = false;
        foreach ($this->tickets as $ticket)
        {
            if($ticket->started_at ) {
                $finish = true;
            }
        }

        return $finish;
    }

    public function getFinishedAttribute()
    {
        $finish = true;
        foreach ($this->tickets as $ticket)
        {
            if(!$ticket->finished_at ) {
                $finish = false;
            }
        }

        return $finish;
    }

    public function getStatusAttribute()
    {
        return $this->finished
            ? "exam_finished"
            : ( $this->started
                ? "started"
                : "exam_not_started");
    }

    public function getColorAttribute()
    {
        return $this->finished
            ? "success"
            : ( $this->started
                ? "primary"
                : "warning");
    }

    public function getAmISignerAttribute()
    {
        $iAmSigner = false;

        if(Auth::check()) {
            foreach ($this->signs as $sign) {
                if($sign->signer_id == Auth::user()->id)
                {
                    $iAmSigner = true;
                    break;
                }
            }
        }

        return $iAmSigner;
    }

}
