<?php

namespace App\Entity;

use App\Db\Database;
use \PDO;

class Vaga
{
    public $id;
    public $titulo;
    public $descricao;
    public $ativo;
    public $dia;

    public function register()
    {
        //Definir a data
        date_default_timezone_set("America/Sao_Paulo");
        $this->dia = date('Y-m-d H:i:s');

        //Inserir a Vaga no Banco
        $obDatabase = new Database('vagas');
        $this->id = $obDatabase->insert([
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            'dia' => $this->dia
        ]);

        return true;
    }

    public function edit()
    {
        return (new Database('vagas'))->update('id = ' . $this->id, [
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            'dia' => $this->dia
        ]);
    }

    public function cancel()
    {
        return (new Database('vagas'))->delete('id = ' . $this->id);
    }

    /**
     * Método responsável por obter as vagas do Banco de Dados
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return array
     */
    public static function getVagas($where = null, $order = null, $limit = null)
    {
        return (new Database('vagas'))->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Método responsável por buscar uma vaga com base em seu ID
     * @param integer $id
     * @return Vaga 
     */
    public static function getVaga($id)
    {
        return (new Database('vagas'))->select('id = ' . $id)->fetchObject(self::class);
    }
}
