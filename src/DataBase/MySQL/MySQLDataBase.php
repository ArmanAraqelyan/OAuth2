<?php
namespace App\DataBase\MySQL;

use \PDO;
use App\DataBase\Relation\RelationalDataBase;
use App\DataBase\AbstractQuery;
use App\DataBase\AbstractAnswer;
use App\DataBase\QueryType;
use App\DataBase\Proxy\ProxyAnswer;

final class MySQLDataBase extends RelationalDataBase 
{

    /** @var PDO $PDO */
    private $PDO;

    public function __construct($host, $dbname, $user, $pass)
    {
        $this->PDO = new PDO('mysql:host=' . $host . ';dbname=' . $dbname,
            $user, $pass);
        
        $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function create_query(QueryType $type): AbstractQuery
    {
        return new MySQLQuery($type);
    }

    /**
     * Undocumented function
     *
     * @param MySQLQuery $query
     * @return AbstractAnswer
     */
    public function exec(AbstractQuery $query): AbstractAnswer
    {
        $answer = $this->PDO->query($query->form_query());

        return new ProxyAnswer($answer === false ? 'failure' : 'success', $answer->fetchAll());
    }
}
