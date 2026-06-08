<?php

namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProdutoController
{
    private array $produtos;
    private int $proximoId = 4;

    public function __construct()
    {
        $this->produtos = [
            1 => new Produto(1, 'Notebook', 3499.90),
            2 => new Produto(2, 'Mouse',      89.90),
            3 => new Produto(3, 'Teclado',   249.90),
        ];
    }

    public function listar(Request $request, Response $response): Response
    {
        $lista = array_map(fn($p) => $p->toArray(), $this->produtos);
        return $this->json($response, array_values($lista));
    }

    public function buscar(Request $request, Response $response, array $args): Response
    {
        $produto = $this->produtos[(int) $args['id']] ?? null;

        if (!$produto) {
            return $this->json($response, ['erro' => 'Produto não encontrado'], 404);
        }

        return $this->json($response, $produto->toArray());
    }

    public function criar(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();

        if (empty($data['nome']) || empty($data['preco'])) {
            return $this->json($response, ['erro' => 'Nome e preço são obrigatórios'], 422);
        }

        $produto = new Produto($this->proximoId, $data['nome'], (float) $data['preco']);
        $this->produtos[$this->proximoId] = $produto;
        $this->proximoId++;

        return $this->json($response, $produto->toArray(), 201);
    }

    public function atualizar(Request $request, Response $response, array $args): Response
    {
        $id      = (int) $args['id'];
        $data    = (array) $request->getParsedBody();
        $produto = $this->produtos[$id] ?? null;

        if (!$produto) {
            return $this->json($response, ['erro' => 'Produto não encontrado'], 404);
        }

        if (!empty($data['nome']))  $produto->nome  = $data['nome'];
        if (!empty($data['preco'])) $produto->preco = (float) $data['preco'];

        return $this->json($response, $produto->toArray());
    }

    public function deletar(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];

        if (!isset($this->produtos[$id])) {
            return $this->json($response, ['erro' => 'Produto não encontrado'], 404);
        }

        unset($this->produtos[$id]);
        return $this->json($response, ['mensagem' => 'Produto removido com sucesso']);
    }

    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
