<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Ticket extends Model
{
    protected $fillable = ['quest_id','exam_id','answer','started_at','finished_at'];

    protected static function boot()
    {
        parent::boot();

        static::updated(
            function ($ticket) {
                if (!$ticket->exam->note && $ticket->exam->finished) {
                    Mail::queue('email.exam_finished', ['exam' => $ticket->exam],
                        function ($message) use ($ticket) {
                            $message->subject('Коммерческое предложение от B-Apps LLP');
                            $message->to($ticket->exam->chief->email);
                            $message->from(env("MAIL_USERNAME", "danixoid@gmail.com"),
                                'Администратор SITUAT.KZ');
                        });
                }
            }
        );
    }

    public function exam() {
        return $this->belongsTo(\App\Exam::class);
    }

    public function quest() {
        return $this->belongsTo(\App\Quest::class);
    }
}
