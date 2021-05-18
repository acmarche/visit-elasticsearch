<?php
require_once 'vendor/autoload.php';

use Visit\Elasticsearch\Searcher;

$query = $_GET['keyword'] ?? null;
if (!$query) {
    return        [];
}
$query = urldecode($query);
$searcher = new Searcher();
$result = $searcher->search($query);
$hits = [];
foreach ($result->getResults() as $result) {
    $hit = $result->getHit();
    $hits[] = $hit['_source'];
}
echo json_encode($hits);
