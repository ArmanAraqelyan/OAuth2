<?php
namespace App\Repositories;

use App\Entities\AbstractEntity;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use App\Entities\ClientEntity;
use App\Repositories\TableBasedRepository;

class ClientRepository extends TableBasedRepository implements ClientRepositoryInterface
{
    protected $table = 'client';

    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier)
    {
        return $this->get([['id', '=', $clientIdentifier]]);
    }

    /**
     * {@inheritdoc}
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        /** @var ClientEntity @client */
        $client = $this->getClientEntity($clientIdentifier);

        if (!$client) {
            return false;
        }

        if (
            $client->isConfidential() === true
            && $client->validate_secret($clientSecret) === false
        ) {
            return false;
        }

        return true;
    }

    public function create_entity(array $data): AbstractEntity
    {
        $client = new ClientEntity();
        $client->setIdentifier($data['id']);

        $client->set_name($data['name']);
        $client->set_redirect_uri(json_decode($data['uri']));
        $client->set_secret($data['secret']);
        
        if ($data['confidential']) $client->set_confidential();

        return $client;
    }
}
