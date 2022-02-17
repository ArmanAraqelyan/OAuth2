<?php
namespace App\Repositories;

use \DateTimeImmutable;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use App\Entities\AbstractEntity;
use App\Entities\AuthCodeEntity;

class AuthCodeRepository extends TableBasedRepository implements AuthCodeRepositoryInterface
{
    protected $table = 'auth_code';

    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $this->create([
            'identifier' => $authCodeEntity->getIdentifier(),
            'expiry_time' => $authCodeEntity->getExpiryDateTime()->getTimestamp(),
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'client_id' => $authCodeEntity->getClient()->getIdentifier(),
            'scope' => json_encode($authCodeEntity->getScopes())
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAuthCode($codeId)
    {
        $this->set(['revoked' => true, 'revoke_date' => time()], [['identifier', '=', "\"$codeId\""]]);
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($codeId)
    {
        /** @var AuthCodeEntity $entity */
        $entity = $this->get([['identifier', '=', "\"$codeId\""]]);

        return $entity->is_revoked();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewAuthCode()
    {
        return new AuthCodeEntity();
    }

    public function create_entity(array $data): AbstractEntity
    {
        $entity = new AuthCodeEntity();
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
