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
    public function buscaCol($id, $col)
    {
        $res = $this->mysqli->query("SELECT * FROM produtos where id=" . $id);
        if ($res) {
            $ret = $res->fetch_array();
            mysqli_free_result($res);
            return $ret[$col];
        } else return null;
    }
    public function deletar($id)
    {
        $this->mysqli->query("DELETE FROM produtos where id=" . $id);
    }
    public function atualizar()
    {

        if (isset($_POST['nome']) && isset($_POST['id'])) {
            $prod = new Produto($_POST['id'], $_POST['nome']);
            $stm = $this->mysqli->prepare("UPDATE produtos set nome=?,id=? WHERE id = ?");
            $stm->bind_param("sii", $_POST['nome'], $_POST['id'], $_SESSION['id_editar']);
            $stm->execute();
            if ($stm->error != '') {
                $_SESSION['error'] = $stm->error;
            }
        }
        $edicao = false;
    }
    public function listarProdutos()
    {
        $res=$this->mysqli->query("SELECT * FROM produtos");
        if ($res) {
            $produtos = $res->fetch_all(MYSQLI_ASSOC);
            mysqli_free_result($res);
            return $produtos;
        }
        else return [];
    }
}
