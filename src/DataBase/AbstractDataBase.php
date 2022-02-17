<?php
namespace App\DataBase;

abstract class AbstractDataBase {
    abstract public function create_query(QueryType $type): AbstractQuery;

    abstract public function exec(AbstractQuery $query): AbstractAnswer;
}
