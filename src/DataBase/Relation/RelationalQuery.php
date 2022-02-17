<?php
namespace App\DataBase\Relation;

use App\DataBase\AbstractQuery;
use App\DataBase\Interfaces\RelationalInterface;

abstract class RelationalQuery extends AbstractQuery implements RelationalInterface {

    /** @var string $table */
    protected $table;

    public function set_table(string $table)
    {
        $this->table = $table;
    }

    public function get_table()
    {
        return $this->table;
    }

}
