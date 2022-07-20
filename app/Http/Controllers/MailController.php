<?php

namespace App\Http\Controllers;

use Error;

class MailController extends Controller
{
    public function SendMail()
    {

        try {
            $message = [
                "from_email" => "no-reply@bertek.co.id",
                "subject" => "Hello world",
                "text" => "Welcome to Mailchimp Transactional!",
                "to" => [
                    [
                        "email" => "info@bertek.co.id",
                        "type" => "to"
                    ]
                ]
            ];

            $mailchimp = new \MailchimpTransactional\ApiClient();
            $mailchimp->setApiKey('dtAcWivONA_IqDaLptrLBA');
            $response = $mailchimp->messages->send(["message" => $message]);

            return $response;
        } catch (Error $e) {
            echo 'Error: ',  $e->getMessage(), "\n";
        }
    }

    public function AddAllowList()
    {
        $mailchimp = new \MailchimpTransactional\ApiClient();
        $mailchimp->setApiKey('dtAcWivONA_IqDaLptrLBA');
        $response = $mailchimp->allowlists->add([
            "email" => 'fazri@bertek.co.id',
        ]);

        dd($response);
    }
}

