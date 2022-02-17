<?php
namespace App\DataBase\Interfaces;

interface RelationalInterface
{
    public function relate(RelationalInterface $to, array $on);

    public function get_connector();
}