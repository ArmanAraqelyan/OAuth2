<?php
namespace App\Entities;

class RevokableEntity extends AbstractEntity
{
    private $is_revoked;
    private $revoke_date;

    public function is_revoked()
    {
        return $this->is_revoked;
    }

    public function set_revoked()
    {
        $this->is_revoked = true;
    }

    public function get_revoke_date()
    {
        return $this->revoke_date;
    }

    public function set_revoke_date(int $time)
    {
        $this->revoke_date = $time;
        $this->set_revoked();
    }
}