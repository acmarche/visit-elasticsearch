<?php
require_once 'vendor/autoload.php';

use Visit\Elasticsearch\Searcher;

$query = 'Ferme';

$searcher = new Searcher();
$result = $searcher->search2($query);
echo ("Found: ".$result->count());
echo ('-------------------');
foreach ($result->getResults() as $result) {
    $hit = $result->getHit();
    $source = $hit['_source'];
    echo $source['name'];
}

