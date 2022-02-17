<?php
namespace App\Entities;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\ScopeTrait;

class ScopeEntity extends AbstractEntity implements ScopeEntityInterface
{
    use EntityTrait, ScopeTrait;

    /** @var string $name */
    private $name;
    /** @var string $description */
    private $description;

    public function get_name(): string
    {
        return $this->name;
    }

    public function set_name(string $name)
    {
        $this->name = $name;
    }

    public function get_description(): string
    {
        return $this->description;
    }
    
    public function set_description(string $description)
    {
        $this->description = $description;
    }
}
