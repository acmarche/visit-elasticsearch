<?php

namespace Visit\Elasticsearch;

use Elastica\Query\MultiMatch;
use Elastica\ResultSet;

/**
 * https://github.com/ruflin/Elastica/tree/master/tests
 * Class Searcher
 *
 */
class Searcher
{
    use ElasticClientTrait;

    public function __construct()
    {
        $this->connect();
    }

    /**
     * @param string $keywords
     * @param int $limit
     *
     * @return ResultSet
     */
    public function search(string $keywords, int $limit = 50): ResultSet
    {
        $options = ['limit' => $limit];
        $query = new MultiMatch();
        $query->setFields(
            [
                'name^1.2',
                'name.stemmed',
                'content',
                'content.stemmed',
                'excerpt',
                'tags',
            ]
        );
        $query->setQuery($keywords);
        $query->setType(MultiMatch::TYPE_MOST_FIELDS);

        $result = $this->index->search($query, $options);

        return $result;
    }
}
