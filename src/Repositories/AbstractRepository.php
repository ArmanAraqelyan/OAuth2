<?php
namespace App\Repositories;

use App\DataBase\AbstractAnswer;
use App\DataBase\AbstractDataBase;
use App\DataBase\AbstractQuery;
use App\DataBase\QueryType;
use App\Entities\AbstractEntity;

abstract class AbstractRepository {
    
    /** @var AbstractDataBase $db */
    protected $db;
    
    public function __construct(AbstractDataBase $db)
    {
        $this->db = $db;
    }

    public function create(array $values): bool
    {
        list($fields, $values) = [array_keys($values), array_values($values)];

        $query = $this->create_query(QueryType::CREATE(), $fields, $values);
        $answer = $this->exec($query);

        return $answer->get_status() !== 'failure';
    }

    /**
     * @param array $conditions
     * @return null|AbstractEntity|AbstractEntity[]
     */
    public function get(array $conditions)
    {
        $query = $this->create_query(QueryType::READ(), [], [], $conditions);

        $answer = $this->exec($query);
        
        return count($answer->get_data()) > 0 ? $this->extract_entities($answer->get_data()) : null;
    }

    public function set(array $values, array $conditions): bool
    {
        list($fields, $values) = [array_keys($values), array_values($values)];

        $query = $this->create_query(QueryType::UPDATE(), $fields, $values, $conditions);
        $answer = $this->exec($query);

        return $answer->get_status() !== 'failure';
    }

    public function delete(array $conditions): bool
    {
        $query = $this->create_query(QueryType::DELETE(), [], [], $conditions);
        $answer = $this->exec($query);

        return $answer->get_status() !== 'failure';
    }


    protected function create_query(QueryType $type, array $fields = [], array $values = [], array $conditions = []): AbstractQuery
    {
        /** @var AbstractQuery $query */
        $query = $this->db->create_query($type);

        $query->add_fields($fields);
        $query->add_values($values);
        $query->add_conditions($conditions);

        return $query;
    }

    protected function exec(AbstractQuery $query): AbstractAnswer
    {
        return $this->db->exec($query);
    }

    /**
     * @param array $data
     * @return null|AbstractEntity|AbstractEntity[]
     */
    public function extract_entities(array $data)
    {
        if (1 > count($data)) {
            return null;
        }

        $result = 
            array_map(
                function ($entity) {
                    return $this->create_entity($entity);
                },
                $data
            );
        
        return count($result) > 1 ? $result : $result[0];
    }

    /**
     * @param array $data
     * @return AbstractEntity
     */
    abstract function create_entity(array $data): AbstractEntity;
}