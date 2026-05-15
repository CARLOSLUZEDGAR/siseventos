<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailPersonal extends Mailable
{
    use Queueable, SerializesModels;
        public $infor;
        public $ges;
        public $doc;
        // public $pass;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($informacion, $gestion, $documento)
    {
        $this->infor = $informacion;
        $this->ges = $gestion;
        $this->doc = $documento;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('DESTINO')->view('correos.destinoMailPersonal');
        // return $this->view('view.name');
    }
}
