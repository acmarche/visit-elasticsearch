<?php

namespace Visit\Elasticsearch;

use Elastica\Exception\InvalidException;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
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
     *
     * @return ResultSet
     * @throws  InvalidException
     */
    public function search2(string $keywords): ResultSet
    {
        $query = new BoolQuery();
        $matchName = new MatchQuery('name', $keywords);
        $matchNameStemmed = new MatchQuery('name.stemmed', $keywords);
        $matchContent = new MatchQuery('content', $keywords);
        $matchContentStemmed = new MatchQuery('content.stemmed', $keywords);
        $matchExcerpt = new MatchQuery('excerpt', $keywords);
        $matchCatName = new MatchQuery('tags', $keywords);
        $query->addShould($matchName);
        $query->addShould($matchNameStemmed);
        $query->addShould($matchExcerpt);
        $query->addShould($matchContent);
        $query->addShould($matchContentStemmed);
        $query->addShould($matchCatName);

        return $this->index->search($query);
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
