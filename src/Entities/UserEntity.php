<?php
namespace App\Entities;

use DateTimeImmutable;
use League\OAuth2\Server\Entities\UserEntityInterface;

class UserEntity extends AbstractEntity implements UserEntityInterface
{
    /** @var string $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var string $pass */
    private $pass;

    /** @var string $mail */
    private $mail;

    /** @var DateTimeImmutable $reg_date */
    private $reg_date;

    /**
     * Return the user's identifier.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->id;
    }
    
    public function set_id(string $id)
    {
        $this->id = $id;
    }
    
    public function get_name(): string
    {
        return $this->name;
    }

    public function set_name(string $name)
    {
        $this->name = $name;
    }

    public function get_pass(): string
    {
        return $this->pass;
    }
    
    public function set_pass(string $pass)
    {
        $this->pass = $pass;
    }


    public function get_mail(): string
    {
        return $this->mail;
    }
    
    public function set_mail(string $mail)
    {
        $this->mail = $mail;
    }

    public static function hash_pass(string $seed)
    {
        return sha1($seed);
    }

    public function get_reg_date()
    {
        return $this->reg_date;
    }
    
    public function set_reg_date(DateTimeImmutable $reg_date)
    {
        $this->reg_date = $reg_date;
    }
}
