<?php
namespace App\Entities;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ClientEntity extends AbstractEntity implements ClientEntityInterface
{
    use EntityTrait, ClientTrait;

    private $secret;

    public function set_name(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string|string[] $uri
     */
    public function set_redirect_uri($uri)
    {
        $this->redirectUri = $uri;
    }

    public function set_confidential()
    {
        $this->isConfidential = true;
    }

    public function set_secret(string $hash)
    {
        $this->secret = $hash;
    }

    public function validate_secret(string $secret): bool
    {
        return password_verify($secret, $this->secret);
    }

    public static function hash_secret(string $secret): string
    {
        return password_hash($secret, PASSWORD_DEFAULT);
    }
}
