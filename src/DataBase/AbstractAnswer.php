<?php
namespace App\DataBase;

abstract class AbstractAnswer {
    private $status;

    private $data;

    public function __construct($status, $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function get_data()
    {
        return $this->data;
    }
}
