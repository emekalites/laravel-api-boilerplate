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

    public function sendEmail($user){
        $mailer = Mail::send('email.view', ['user' => $user], function ($m) use ($user) {
            $m->from($this->email, $this->name);
            $m->to($user->email, $user->name)->subject('Subject');
        });
        return $mailer;
    }
}
