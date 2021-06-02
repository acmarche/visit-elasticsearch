<?php
require_once 'vendor/autoload.php';

use Visit\Elasticsearch\ElasticRemoteSearcher;

$query = $_GET['keyword'] ?? null;

if (!$query) {
    return [];
}
$query = urldecode($query);
$searcher = new ElasticRemoteSearcher();
$result = $searcher->search($query);
$hits = [];
foreach ($result->getResults() as $result) {
    $hit = $result->getHit();
    $hits[] = $hit['_source'];
}
echo json_encode($hits);
