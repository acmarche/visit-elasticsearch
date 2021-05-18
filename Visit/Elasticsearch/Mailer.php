<?php


namespace Visit\Elasticsearch;

class Mailer
{
    public static function sendError(string $subject, string $message)
    {
        $to = 'webmaster@marche.be';
        wp_mail($to, $subject, $message);
    }

}
