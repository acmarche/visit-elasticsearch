<?php


namespace Visit\Elasticsearch\Data;


use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class Serializer
{
    public static function create(): SerializerInterface
    {
        return new \Symfony\Component\Serializer\Serializer(
            [
                new ArrayDenormalizer(),
                new DateTimeNormalizer(),
                new ObjectNormalizer(null, null, null, new PhpDocExtractor()),
            ],
            [
                new JsonEncoder(),
            ]
        );
    }
}
