<?php
namespace App\DataBase;

class QueryType
{
    public static function CREATE(): QueryType
    {
        return new QueryType('create');
    }

    public static function READ(): QueryType
    {
        return new QueryType('read');
    }

    public static function UPDATE(): QueryType
    {
        return new QueryType('update');
    }

    public static function DELETE(): QueryType
    {
        return new QueryType('delete');
    }

    /** @var string $type */
    protected $type;

    protected function __construct(string $type)
    {
        $this->type = $type;
    }

    public function __toString()
    {
        return $this->type;
    }
}