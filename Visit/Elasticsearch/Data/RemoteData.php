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

    public function createDocumentElasticFromX(\stdClass $object): DocumentElastic
    {
        $document = new DocumentElastic();
        $document->id = $object->id;
        $document->name = $object->name;
        $document->excerpt = $object->excerpt;
        $document->content = $object->content;
        $document->tags = $object->tags;
        $document->date = $object->date;
        $document->url = $object->url;
        $document->image = $object->image;

        return $document;
    }
}
