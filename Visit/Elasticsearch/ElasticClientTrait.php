<?php

namespace Visit\Elasticsearch;

use Elastica\Client;
use Elastica\Index;
use Symfony\Component\Dotenv\Dotenv;

trait ElasticClientTrait
{
    /**
     * @var Client
     */
    public $client;

    /**
     * @var Index
     */
    private $index;

    public function connect(string $host = 'localhost', int $port = 9200)
    {
        self::loadEnv();
        $username = $_ENV['ELASTIC_USER'];
        $password = $_ENV['ELASTIC_PASSWORD'];
        $ds = $username.':'.$password.'@'.$host;
        $this->client = new Client(
            [
                'host' => $ds,
                'port' => $port,
            ]
        );
        //$this->client->setLogger(); todo
        $this->setIndex(ElasticServer::INDEX_NAME_VISIT_FR);
    }

    public function setIndex(string $name)
    {
        $this->index = $this->client->getIndex($name);
    }

    public static function loadEnv(): void
    {
        $dotenv = new Dotenv();
        try {
            $dotenv->load(__DIR__.'/../../.env');
        } catch (\Exception $exception) {
            echo "error load env: ".$exception->getMessage();
        }
    }
}
