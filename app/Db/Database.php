<?php

namespace App\Db;

use \PDO; //Dependência da classe!
use PDOException;

class Database
{
    const HOST = 'localhost';
    const NAME = 'Jobs';
    const USER = 'root';
    const PASSWORD = "";

    //nome da tabela a ser manipulada. 
    private $table;

    //instância de conexão com o Bando de Dados.
    private $connection;

    //Define a tabela e instancia a conexão. 
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    //Método responsável por criar uma conexão com o Banco de Dados!
    private function setConnection()
    {
        try {
            $this->connection = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::NAME, self::USER, self::PASSWORD);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            /*1° param = atributo para alterar; 
            2° param = valor a ser recebido
            Obs.: ERRMODE é o modo de erro.*/
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Método responsável por executar queries dentro do Banco de Dados
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public function execute($query, $params = [])
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);

            return $statement;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Método responsável por inserir dados no banco
     * @param array $values [ field => value ]
     * @return integer ID inserido
     */
    public function insert($values)
    {
        //Dados da query
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?'); /*A função array_pad() insere um número especificado de elementos, 
        com um valor especificado, em um array. 
        Obs.: Nessa caso, a função excluirá "?" se o parâmetro de tamanho for menor que o tamanho da matriz original.*/

        //Monta a query
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';
        //Obs.: o método implode serve pra concatenar as posições do array com o separador, que nesse caso é a vírgula.

        //Execução do insert
        $this->execute($query, array_values($values));

        return $this->connection->lastInsertId();
    }

    /**
     * Método responsável por executar uma consulta no Banco de Dados
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        //Dados da query
        $where = strlen($where) ? 'WHERE ' . $where : '';
        $order = strlen($order) ? 'ORDER BY ' . $order : '';
        $limit = strlen($limit) ? 'LIMIT ' . $limit : '';

        //Monta a query
        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit . '';

        //Executa a query
        return $this->execute($query);
    }


    public function update($where, $values)
    {
        //Dados da query
        $fields = array_keys($values);

        //Monta query
        $query = 'UPDATE ' . $this->table . ' SET ' . implode('=?,', $fields) . '=? WHERE ' . $where;

        //Executa query
        $this->execute($query, array_values($values));
        return true;
    }

    public function delete($where)
    {
        //Monta a query
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;

        //Executa a query
        $this->execute($query);
        return true;
    }
}
