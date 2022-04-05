<?php

namespace Visit\Elasticsearch\Data;


class DocumentElastic
{
    /**
     * @var string
     */
    public ?string $id;
    /**
     * @var string
     */
    public ?string $name;
    /**
     * @var string
     */
    public ?string $excerpt;
    /**
     * @var string
     */
    public ?string $content;
    /**
     * @var array
     */
    public array $tags = [];
    /**
     * @var string
     */
    public ?string $date;
    /**
     * @var string
     */
    public ?string $url;
    /**
     * @var string
     */
    public ?string $image;
}
