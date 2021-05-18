<?php

namespace Visit\Elasticsearch\Data;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ElasticData
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = HttpClient::create();
        $this->url = 'https://www.visitmarche.be/wp-json/visit/all';
    }

    public function getAllData(): \stdClass
    {
        $t = json_decode(file_get_contents($this->url));//2 times error ssl
        $t = json_decode(file_get_contents($this->url));//2 times error ssl
        $t = json_decode(file_get_contents($this->url));
        var_dump($t);

        return $t;

        try {
            $response = $this->httpClient->request(
                'GET',
                $this->url
            );
        } catch (TransportExceptionInterface $exception) {
            Mailer::sendError('Erreur get data tourisme1', $exception->getMessage());

            return ['error' => $exception->getMessage()];
        }

        try {
            $content = $response->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $exception) {
            Mailer::sendError('Erreur get data tourisme2', $exception->getMessage());

            return ['error' => $exception->getMessage()];
        }

        $data = json_decode($content);

        return $data;
    }

    public function createDocumentElasticFromX(\stdClass $post): DocumentElastic
    {
        $document = new DocumentElastic();
        $document->id = $post->id;
        $document->name = $post->name;
        $document->excerpt = $post->excerpt;
        $document->content = $post->content;
        $document->tags = $post->tags;
        $document->date = $post->date;
        $document->url = $post->url;

        return $document;
    }
}
