<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewKshMemberRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $credential;

    public function __construct($credential)
    {
        $this->credential = $credential;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Anggota Baru KSH')
            ->to($this->credential['email'])
            ->markdown('mail.new-ksh-member-registered', [
                'credential' => $this->credential,
            ]);
    }
}
