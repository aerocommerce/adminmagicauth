<?php

namespace Aerocargo\Aeroauth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendToken extends Mailable
{
    use Queueable, SerializesModels;

    protected $token;

    protected $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $url)
    {
        $this->token = $token;

        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('aeroauth::emails.send-token', [
            'token' => $this->token,
            'url' => $this->url
        ]);
    }
}
