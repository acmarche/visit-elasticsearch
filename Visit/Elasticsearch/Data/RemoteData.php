<?php

namespace Visit\Elasticsearch\Data;

class RemoteData
{
    /**
     * @var string
     */
    private $url;

    public function __construct()
    {
        $this->url = 'https://www.visitmarche.be/wp-json/visit/all';
    }

    public function getAllData(): \stdClass
    {
        $t = json_decode(file_get_contents($this->url));//2 times error ssl
        $t = json_decode(file_get_contents($this->url));//2 times error ssl
        $t = json_decode(file_get_contents($this->url));

        return $t;
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
