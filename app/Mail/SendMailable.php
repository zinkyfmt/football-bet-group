<?php

namespace App\Mail;

use App\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $setting = Setting::find(1);
        $ownName = $setting->owner_name;
        $address = $setting->admin_email;
        $subject = $setting->email_subject;
        $name = $this->data['name'];
        $newSubject = str_replace('{{$NAME}}', $name, $subject);
        $body = $setting->email_template;
        $newBody = str_replace('{{$NAME}}', $name, $body);
        return $this->view('email.name')
            ->from($address, $ownName)
            // ->cc($address, $name)
            // ->bcc($address, $name)
            // ->replyTo($address, $name)
            ->subject($newSubject)
            ->with([ 'content' => $newBody]);
    }
}