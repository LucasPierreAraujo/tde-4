<?php

namespace App;

class Produto
{
    public int $id;
    public string $nome;
    public float $preco;

    public function __construct(int $id, string $nome, float $preco)
    {
        $this->id    = $id;
        $this->nome  = $nome;
        $this->preco = $preco;
    }

    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'nome'  => $this->nome,
            'preco' => $this->preco,
        ];
    }
}
