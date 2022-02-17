<?php
namespace App\Repositories;

use App\DataBase\AbstractQuery;
use App\DataBase\QueryType;
use App\DataBase\Relation\RelationalQuery;

abstract class TableBasedRepository extends AbstractRepository {
    
    /** @var string $table */
    protected $table;

    protected function create_query(QueryType $type, array $fields = [], array $values = [], array $conditions = []): AbstractQuery
    {
        /** @var RelationalQuery $query */
        $query = parent::create_query($type, $fields, $values, $conditions);
        $query->set_table($this->table);

        return $query;
    }
}