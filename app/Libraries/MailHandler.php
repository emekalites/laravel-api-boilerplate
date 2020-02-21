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
    public function sendEmail($mail){
        $mailer = Mail::send($mail->template, ['mail' => $mail], function ($m) use ($mail) {
            $m->from($mail->from_email, $mail->from_name);
            $m->to(strtolower($mail->to_email), $mail->to_name)->subject($mail->subject);
        });
        return $mailer;
    }

    public function sendEmailAttachment($mail){
        $mailer = Mail::send($mail->template, ['mail' => $mail], function ($m) use ($mail) {
            $m->from($mail->from_email, $mail->from_name);
            $m->to(strtolower($mail->to_email), $mail->to_name)->subject($mail->subject);
            $m->attach($mail->file);
        });
        return $mailer;
    }
}
