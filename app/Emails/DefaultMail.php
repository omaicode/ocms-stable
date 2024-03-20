<?php

namespace App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DefaultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $is_view;
    public $subject;
    public $content;
    public $variables;    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($is_view = false, $subject, $content, array $variables = [])
    {
        $this->is_view = $is_view;
        $this->subject = $subject;
        $this->content = $content;
        $this->variables = $variables;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->subject)
            ->markdown(
                $this->is_view ? $this->content : 'admin.email_templates.default', 
                $this->is_view ? $this->variables : array_merge($this->content, $this->variables)
            );
    }
}
