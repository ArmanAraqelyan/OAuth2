<?php
namespace App\Repositories;

use App\Entities\AbstractEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Entities\UserEntity;
use DateTimeImmutable;
use Exception;

class UserRepository extends TableBasedRepository implements UserRepositoryInterface
{
    protected $table = 'user';

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        return $this->login($username, $password);
    }

    public function create_entity(array $data): AbstractEntity
    {
        $entity = new UserEntity();
        $entity->set_id($data['id']);
        $entity->set_name($data['name']);
        $entity->set_mail($data['mail']);
        $entity->set_pass($data['pass']);
        $entity->set_reg_date(new DateTimeImmutable('@' . $data['reg_date']));

        return $entity;
    }

    public function login($username, $password): ?UserEntity
    {
        $password = UserEntity::hash_pass($password);

        return $this->get(
            [
                ['pass', '=', "\"$password\""], 
                'AND' => [
                    ['mail', '=', "\"$username\""],
                    'OR' => [['name', '=', "\"$username\""]]
                ]
            ]
        );
    }

    public function register(string $name, string $email, string $password)
    {
        $password = UserEntity::hash_pass($password);

        $this->create(['name' => $name, 'mail' => $email, 'pass' => $password, 'reg_date' => time()]);
    }
}
