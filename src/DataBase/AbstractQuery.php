<?php
namespace App\DataBase;

abstract class AbstractQuery {

    /** @var QueryType $type */
    protected $type;

    /** @var array $fields */
    protected $fields = [];

    /** @var array $values */
    protected $values = [];

    /** @var array $conditions */
    protected $conditions = [];

    public function __construct(QueryType $type)
    {
        $this->type = $type;
    }

    abstract public function form_query();

    public function add_fields(array $fields)
    {
        $this->fields = array_merge($this->fields, $fields);
    }

    public function add_values(array $values)
    {
        $this->values = array_merge($this->values, $values);
    }
    
    public function add_conditions(array $conditions)
    {
        $this->conditions = array_merge($this->conditions, $conditions);
    }

    public function set_fields(array $fields)
    {
        $this->fields = $fields;
    }

    public function set_values(array $values)
    {
        $this->values = $values;
    }

    public function set_conditions(array $conditions)
    {
        $this->conditions = $conditions;
    }

    public function get_fields()
    {
        return $this->fields;
    }

    public function get_values()
    {
        return $this->values;
    }

    public function get_conditions()
    {
        return $this->conditions;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function copy_query(): self
    {
        return clone $this;
    }

}
