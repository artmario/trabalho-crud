<?php


class Produto
{
    private $id,$nome;

    /**
     * Produto constructor.
     * @param $id
     * @param $nome
     */
    public function __construct($id, $nome)
    {
        $this->id = $id;
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function __toString()
    {
        return ($this->getNome() ." ". $this->getId());
    }


}