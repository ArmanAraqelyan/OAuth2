<?php
namespace App\DataBase\MySQL;

use \Exception;
use App\DataBase\QueryType;
use App\DataBase\Relation\RelationalQuery;
use App\DataBase\Interfaces\RelationalInterface;

final class MySQLQuery extends RelationalQuery {

    /** @var string $template */
    private $template;

    /** @var array $relations*/
    private $relations;

    public function __construct(QueryType $type)
    {
        parent::__construct($type);

        switch($type) {
            case QueryType::CREATE():
                $this->template = "INSERT INTO :table (:fields) VALUES (:values)";
                break;
            case QueryType::READ():
                $this->template = "SELECT :fields FROM :table WHERE :conditions";
                break;
            case QueryType::UPDATE():
                $this->template = "UPDATE :table SET :field_values WHERE :conditions";
                break;
            case QueryType::DELETE():
                $this->template = "DELETE FROM :table WHERE :conditions";
                break;
            default:
                throw new Exception("Undefined Query Type");
        }
    }

    public function form_query(): string
    {
        $table = '`' . $this->table . '`';
        $fields = implode(', ', $this->fields) ?: '*';
        $values = $this->form_values($this->values);
        $conditions = $this->form_conditions($this->conditions ?: [['1', '=', '1']]);
        $relations = $this->form_relations($this->relations ?: []);
        $field_values = $this->form_field_values($this->fields, $this->values);

        return 
            str_replace(
                [':table', ':fields', ':values', ':conditions', ':relations', ':field_values'],
                [ $table,   $fields,   $values,   $conditions,   $relations,   $field_values ],
                $this->template
            );
    }

    public function __toString(): string
    {
        return $this->form_query();
    }

    public function relate(RelationalInterface $to, array $on)
    {
        if ($this->type != QueryType::READ()) {
            throw new Exception("Unable to create relationship. It is possible only during the reading");
        }

        $this->template = "SELECT :fields :relations FROM :table WHERE :conditions";
        $this->relations[] = [$to->get_connector(), $on];
    }

    public function get_connector()
    {
        return $this->table;
    }

    private function form_conditions(array $conditions): string
    {
        $result = '';
        $OR = isset($conditions['OR']) ? $conditions['OR'] : [];
        $AND = isset($conditions['AND']) ? $conditions['AND'] : [];
        unset($conditions['OR']);
        unset($conditions['AND']);

        $result .= 
        implode(
            ' AND ',
            array_map(
                function ($condition) {
                    return implode(' ', $condition);
                },
                $conditions
            )
        );
            
        if (isset($OR) && !empty($OR)) {
            $result .= ' OR ' . '(' . $this->form_conditions($OR) . ')';
        }
        if (isset($AND) && !empty($AND)) {
            $result .= ' AND ' . '(' . $this->form_conditions($AND) . ')';
        }

        return $result;
    }

    private function form_relations(array $relations): string
    {
        return implode(
            ' ',
            array_map(
                function ($relation) {
                    $table = $relation[0];
                    $conditions = $this->form_conditions($relation[1]);
                    return "JOIN $table ON $conditions";
                },
                $relations
            )
        );
    }

    private function form_values(array $values): string
    {
        return 
            implode(
                ', ',
                array_map(
                    function ($value) {
                        return 
                        is_null($value) ? "NULL" :
                            (is_numeric($value) ? $value :
                            '"' . addslashes($value) . '"');
                    },
                    $values
                )
            );
    }

    private function form_field_values(array $fields, array $values): string
    {
        $result = '';

        for($i = 0; $i < count($fields); $i++) {
            $value = is_null($values[$i]) ? "NULL" :
                            (is_numeric($values[$i]) ? $values[$i] :
                            '"' . addslashes($values[$i]) . '"');
            
            $result .= 
                $fields[$i] . '=' . $value . ', ';
        }

        $result = rtrim($result, ', ');

        return $result;
    }
}
