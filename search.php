<?php
require_once 'vendor/autoload.php';

use Visit\Elasticsearch\Searcher;

$query = 'Ferme';

$searcher = new Searcher();
$result = $searcher->search2($query);
$hits = [];
foreach ($result->getResults() as $result) {
    $hit = $result->getHit();
    $hits[] = $hit['_source'];
}
echo json_encode($hits);
