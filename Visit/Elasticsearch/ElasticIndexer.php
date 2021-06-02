<?php

namespace Visit\Elasticsearch;

use Elastica\Document;
use Elastica\Response;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Visit\Elasticsearch\Data\DocumentElastic;
use Visit\Elasticsearch\Data\RemoteData;
use Visit\Elasticsearch\Data\Serializer;

class ElasticIndexer
{
    use ElasticClientTrait;

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var RemoteData
     */
    private $remoteData;
    /**
     * @var SymfonyStyle|null
     */
    private $outPut;

    public function __construct(?SymfonyStyle $outPut = null)
    {
        $this->connect();
        $this->serializer = (new Serializer())->create();
        $this->remoteData = new RemoteData();
        $this->outPut = $outPut;
    }

    public function getAll()
    {
        return $this->remoteData->getAllData();
    }

    public function treatment()
    {
        $allData = $this->getAll();
        if (isset($allData->error)) {
            Mailer::sendError('Erreur sync tourisme', $allData->error);

            return ['error' => $allData->error];
        }

        foreach ($allData->posts as $data) {
            $documentElastic = $this->remoteData->createDocumentElasticFromX($data);
            if ($this->outPut) {
                $this->outPut->writeln($documentElastic->name);
            }
            $this->addPost($documentElastic);
        }

        foreach ($allData->categories as $data) {
            $documentElastic = $this->remoteData->createDocumentElasticFromX($data);
            if ($this->outPut) {
                $this->outPut->writeln($documentElastic->name);
            }
            $this->addCategory($documentElastic);
        }

        foreach ($allData->offres as $data) {
            $documentElastic = $this->remoteData->createDocumentElasticFromX($data);
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
        $response = $this->index->addDocument($doc);
        $this->handleResponse($response);
    }

    private function addCategory(DocumentElastic $documentElastic)
    {
        $content = $this->serializer->serialize($documentElastic, 'json');
        $id = 'category_'.$documentElastic->id;
        $doc = new Document($id, $content);
        $response = $this->index->addDocument($doc);
        $this->handleResponse($response);
    }

    private function addOffre(DocumentElastic $documentElastic)
    {
        $content = $this->serializer->serialize($documentElastic, 'json');
        $id = 'offre_'.$documentElastic->id;
        $doc = new Document($id, $content);
        $response = $this->index->addDocument($doc);
        $this->handleResponse($response);
    }

    private function handleResponse(Response $response)
    {
        if ($response->hasError()) {
            if ($this->outPut) {
                $this->outPut->error($response->getErrorMessage());
            }
        }
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
