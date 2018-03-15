<?php

return [
    'MailTransport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'username@gmail.com',
        'password' => 'password',
        'port' => '587',
        'encryption' => 'tls',
    ],
  //https://developer.paypal.com/developer/applications/
    'paypal_client_id'=>'____',
    'paypal_client_secret'=>'_________________________________________',
    'site_url'=>'http://127.0.0.1:8080',
];
