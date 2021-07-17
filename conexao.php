<?php

class Banco
{
    public $host = "127.0.0.1";
    public $user = "usuario";
    public $passwd = "senha";
    public $db = "aula";
    public $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli($this->host, $this->user, $this->passwd, $this->db);
    }
    public function buscaCol($id,$col)
    {
        $res = $this->mysqli->query("SELECT * FROM produtos where id=".$id);
        if($res)
        {
            $ret = $res->fetch_array();
            return $ret[$col];
        }
        else return null;
    }
    public function deletar($id){
        $this->mysqli->query("DELETE FROM produtos where id=".$id);
    }
}
