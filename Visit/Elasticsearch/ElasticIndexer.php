<?php

namespace Visit\Elasticsearch;

use Elastica\Document;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Visit\Elasticsearch\Data\DocumentElastic;
use Visit\Elasticsearch\Data\ElasticData;
use Visit\Elasticsearch\Data\Serializer;

class ElasticIndexer
{
    use ElasticClientTrait;

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ElasticData
     */
    private $elasticData;
    /**
     * @var SymfonyStyle|null
     */
    private $outPut;

    public function __construct(?SymfonyStyle $outPut = null)
    {
        $this->connect();
        $this->serializer = (new Serializer())->create();
        $this->elasticData = new ElasticData();
        $this->outPut = $outPut;
    }

    public function getAll()
    {
        return $this->elasticData->getAllData();
    }

    public function treatment()
    {
        $allData = $this->getAll();
        if (isset($allData->error)) {
            Mailer::sendError('Erreur sync tourisme', $allData->error);

            return ['error' => $allData->error];
        }

        foreach ($allData->posts as $data) {
            $documentElastic = $this->elasticData->createDocumentElasticFromX($data);
            if ($this->outPut) {
                $this->outPut->writeln($documentElastic->name);
            }
            $this->addPost($documentElastic);
        }

        foreach ($allData->categories as $data) {
            $documentElastic = $this->elasticData->createDocumentElasticFromX($data);
            if ($this->outPut) {
                $this->outPut->writeln($documentElastic->name);
            }
            $this->addCategory($documentElastic);
        }

        foreach ($allData->offres as $data) {
            $documentElastic = $this->elasticData->createDocumentElasticFromX($data);
            if ($this->outPut) {
                $this->outPut->writeln($documentElastic->name);
            }
            $this->addOffre($documentElastic);
        }

        return [];
    }

    public function addPost(DocumentElastic $documentElastic)
    {
        $content = $this->serializer->serialize($documentElastic, 'json');
        $id = $this->createIdPost($documentElastic->id);
        $doc = new Document($id, $content);
        $this->index->addDocument($doc);
    }

    private function addCategory(DocumentElastic $documentElastic)
    {
        $content = $this->serializer->serialize($documentElastic, 'json');
        $id = 'category_'.$documentElastic->id;
        $doc = new Document($id, $content);
        $this->index->addDocument($doc);
    }

    private function addOffre(DocumentElastic $documentElastic)
    {
        $content = $this->serializer->serialize($documentElastic, 'json');
        $id = 'offre_'.$documentElastic->id;
        $doc = new Document($id, $content);
        $this->index->addDocument($doc);
    }

    public function deletePost(int $postId)
    {
        $id = $this->createIdPost($postId);
        $this->index->deleteById($id);
    }

    protected function createIdPost(int $postId): string
    {
        return 'post_'.$postId;
    }
}
