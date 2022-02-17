<?php
namespace App\Repositories;

use DateTimeImmutable;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use App\Entities\AbstractEntity;
use App\Entities\AccessTokenEntity;
use App\Entities\RefreshTokenEntity;
use App\Repositories\AccessTokenRepository;

class RefreshTokenRepository extends TableBasedRepository implements RefreshTokenRepositoryInterface
{
    protected $table = 'refresh_token';
    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $this->create([
            'identifier' => $refreshTokenEntity->getIdentifier(),
            'expiry_time' => $refreshTokenEntity->getExpiryDateTime()->getTimestamp(),
            'access_token' => $refreshTokenEntity->getAccessToken()->getIdentifier(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        $this->set(['revoked' => true, 'revoke_date' => time()], [['identifier', '=', "\"$tokenId\""]]);
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        /** @var RefreshTokenEntity $entity */
        $entity = $this->get([['identifier', '=', "\"$tokenId\""]]);

        return $entity->is_revoked();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        $refresh_token = new RefreshTokenEntity();
        return $refresh_token;
    }

    public function create_entity(array $data): AbstractEntity
    {
        $entity = new RefreshTokenEntity();
        $entity->setIdentifier($data['identifier']);
        $entity->setExpiryDateTime(new DateTimeImmutable('@' . $data['expiry_time']));
        
        /** @var AccessTokenEntity $access_token */
        $access_token = (new AccessTokenRepository($this->db))->get([['identifier', '=', '"' . $data['access_token'] . '"']]);
        $entity->setAccessToken($access_token);

        if ($data['revoke_date'] !== null && $data['revoked']) {
            $entity->set_revoke_date($data['revoke_date']);
        }

        return $entity;
    }
}
