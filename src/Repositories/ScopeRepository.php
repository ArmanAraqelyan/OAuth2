<?php
namespace App\Repositories;

use App\Entities\AbstractEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use App\Entities\ScopeEntity;

class ScopeRepository extends TableBasedRepository implements ScopeRepositoryInterface
{
    protected $table = 'scope';

    /**
     * {@inheritdoc}
     */
    public function getScopeEntityByIdentifier($scopeIdentifier)
    {
        return $this->get([['id', '=', $scopeIdentifier]]);
    }

    /**
     * {@inheritdoc}
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        return $scopes;
    }

    public function create_entity(array $data): AbstractEntity
    {
        $entity = new ScopeEntity();
        
        $entity->setIdentifier($data['id']);
        $entity->set_name($data['name']);
        $entity->set_description($data['description']);

        return $entity;
    }
}
