<?php

namespace Aerocargo\Adminmagicauth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendToken
 * @package Aerocargo\Adminmagicauth
 */
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
        return $this->view('adminmagicauth::emails.send', [
            'token' => $this->token,
            'url' => $this->url . '&token='. $this->token
        ]);
    }
}
