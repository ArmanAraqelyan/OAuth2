<?php
namespace App\Repositories;

use App\Entities\AbstractEntity;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use App\Entities\AccessTokenEntity;
use DateTimeImmutable;

class AccessTokenRepository extends TableBasedRepository implements AccessTokenRepositoryInterface
{
    protected $table = 'token';

    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $this->create([
            'identifier' => $accessTokenEntity->getIdentifier(),
            'expiry_time' => (int)$accessTokenEntity->getExpiryDateTime()->getTimestamp(),
            'user_id' => (int)$accessTokenEntity->getUserIdentifier(),
            'client_id' => (int)$accessTokenEntity->getClient()->getIdentifier(),
            'scope' => json_encode($accessTokenEntity->getScopes()),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {
        $this->set(['revoked' => true, 'revoke_date' => time()], [['identifier', '=', "\"$tokenId\""]]);
    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {
        /** @var AccessTokenEntity $entity */
        $entity = $this->get([['identifier', '=', "\"$tokenId\""]]);

        return $entity->is_revoked();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        $accessToken->setUserIdentifier($userIdentifier);

        return $accessToken;
    }

    public function create_entity(array $data): AbstractEntity
    {
        $entity = new AccessTokenEntity();
        $entity->setIdentifier($data['identifier']);
        $entity->setExpiryDateTime(new DateTimeImmutable('@' . $data['expiry_time']));
        $entity->setUserIdentifier($data['user_id']);
        $entity->setClient((new ClientRepository($this->db))->getClientEntity($data['client_id']));
        foreach(json_decode($data['scope'], true) as $value)
        {
            $entity->addScope((new ScopeRepository($this->db))->getScopeEntityByIdentifier($value));
        }
        if ($data['revoke_date'] !== null && $data['revoked']) {
            $entity->set_revoke_date($data['revoke_date']);
        }

        return $entity;
    }
}
