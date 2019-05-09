<?php
/**
 * Created by PhpStorm.
 * User: emnity
 * Date: 11/30/17
 * Time: 9:57 PM
 */

namespace App\Libraries;

use Mail;

class MailHandler
{
    private $name = 'App Name';
    private $email = 'email';

    public function sendEmail($data){
        $mailer = Mail::send($data->template, ['data' => $data], function ($m) use ($data) {
            $m->from($this->email, $this->name);
            $m->to($data->email, $data->name)->subject($data->subject);
        });
        return $mailer;
    }
}
