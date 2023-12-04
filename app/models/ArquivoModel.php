<?php

namespace App\Models;

use Core\model\Container;
use Core\Model\Model;
use PDO;

class ArquivoModel extends Model
{

	private $id;
	private $id_usuario;
	private $id_categoria;
	private $titulo;
	private $descricao;
	private $espessura_mm;
	private $arquivo;
	private $extensao;
	private $tamanho;
	private $visualizacoes;
	private $ativo;
	private $deleted_at;
	private $updated_at;
	private $created_at;


	public function __get($atributo)
	{
		return $this->$atributo;
	}

	public function __set($atributo, $valor)
	{
		$this->$atributo = $valor;
	}

	//validar se um cadastro pode ser feito
	public function validarCadastro()
	{
		$valido = true;

		if (strlen($this->__get('titulo')) < 3) {
			$valido = false;
		}

		return $valido;
	}

	public function addViews($id)
	{
		$query = "UPDATE arquivos SET visualizacoes = visualizacoes + 1 WHERE id = :id";
		// RETURNING meu_campo AS novo_valor
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
	}

	public function addDowns($id_arquivo, $id_usuario, $ip)
	{
		$query = "insert into downloads (id_arquivo, id_usuario, ip) values (:id_arquivo, :id_usuario, :ip)";
		// RETURNING meu_campo AS novo_valor
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_arquivo', $id_arquivo);
		$stmt->bindValue(':id_usuario', $id_usuario);
		$stmt->bindValue(':ip', $ip);
		$stmt->execute();

		return true;
	}

	public function totalDowns($id_arquivo)
	{
		$query = "select count(id) as total from downloads where id_arquivo = $id_arquivo";


		return $this->db->query($query)->fetchObject()->total;
	}

	//recuperar um categoria por e-mail
	public function getArquivoPorNome()
	{
		$query = "select id, titulo from arquivos where titulo = :titulo";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':titulo', $this->__get('titulo'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	//salvar
	public function salvar()
	{

		$query = "insert into arquivos 
						(id_usuario, id_categoria, titulo, descricao, espessura_mm, arquivo, extensao, tamanho, visualizacoes, ativo) 
						values 
						(:id_usuario, :id_categoria, :titulo, :descricao, :espessura_mm, :arquivo, :extensao, :tamanho, :visualizacoes, :ativo)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':id_categoria', $this->__get('id_categoria'));
		$stmt->bindValue(':titulo', $this->__get('titulo'));
		$stmt->bindValue(':descricao', $this->__get('descricao'));
		$stmt->bindValue(':espessura_mm', $this->__get('espessura_mm'));
		$stmt->bindValue(':arquivo', $this->__get('arquivo'));
		$stmt->bindValue(':extensao', $this->__get('extensao'));
		$stmt->bindValue(':tamanho', $this->__get('tamanho'));
		$stmt->bindValue(':visualizacoes', $this->__get('visualizacoes'));
		$stmt->bindValue(':ativo', $this->__get('ativo'));
		$stmt->execute();

		$this->__set('id', $this->db->lastInsertId());

		return $this;
	}

	public function rowbackArquivo($id)
	{
		// $query = "delete from usuarios where id = :id_usuario";
		$query = "delete from arquivos where id = :id_arquivo";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_arquivo', $id);
		$stmt->execute();

		return true;
	}

	public function getArquivos()
	{
		$sql = "select a.id, a.id_usuario, a.id_categoria, a.titulo, a.descricao, a.espessura_mm, a.arquivo, a.extensao, 
		a.tamanho, a.visualizacoes, a.ativo, a.created_at, 
		c.nome as categoria, u.nome as usernome, u.sobrenome as usersobrenome from arquivos as a 
		inner join categorias as c on a.id_categoria = c.id 
		inner join usuarios as u on a.id_usuario = u.id";

		return $this->db->query($sql)->fetchAll();
	}


	public function getArquivosComImagem()
	{
		$sql = "SELECT a.id, a.id_usuario, a.id_categoria, a.titulo, a.descricao, a.espessura_mm, a.arquivo, a.extensao,
		a.tamanho, a.visualizacoes, a.ativo, a.created_at,
		c.nome as categoria, u.nome as usernome, u.sobrenome as usersobrenome, i.nome, i.id
		FROM arquivos as a
		INNER JOIN categorias as c ON a.id_categoria = c.id
		INNER JOIN usuarios as u ON a.id_usuario = u.id
		LEFT JOIN (
			SELECT id_arquivo, nome, id, ROW_NUMBER() OVER (PARTITION BY id_arquivo ORDER BY id) as row_num
			FROM imagens
		) i ON a.id = i.id_arquivo
		WHERE i.row_num = 1 OR i.row_num IS NULL order by a.id desc";

		return $this->db->query($sql)->fetchAll(PDO::FETCH_DEFAULT);
	}

	public function getArquivosPorCategoria($categoria, $id_categoria, $id_arquivo, $limit = 5)
	{
		$categoria = explode(' ', $categoria, -1);

		$sql = "SELECT a.id, a.id_usuario, a.id_categoria, a.titulo, a.descricao, a.espessura_mm, a.arquivo, a.extensao,
		a.tamanho, a.visualizacoes, a.ativo, a.created_at,
		c.nome as categoria, u.nome as usernome, u.sobrenome as usersobrenome, i.nome, i.id as id_img
		FROM arquivos as a
		INNER JOIN categorias as c ON a.id_categoria = c.id
		INNER JOIN usuarios as u ON a.id_usuario = u.id
		LEFT JOIN (
			SELECT id_arquivo, nome, id, ROW_NUMBER() OVER (PARTITION BY id_arquivo ORDER BY id) as row_num
			FROM imagens
		) i ON a.id = i.id_arquivo
		WHERE a.titulo LIKE '%$categoria[0]%' and a.id != $id_arquivo and i.row_num = 1 OR i.row_num IS NULL order by a.id desc limit $limit";


		if (count($this->db->query($sql)->fetchAll(PDO::FETCH_DEFAULT)) > 0) {
			return $this->db->query($sql)->fetchAll(PDO::FETCH_DEFAULT);
		} else {
			$sql = "SELECT a.id, a.id_usuario, a.id_categoria, a.titulo, a.descricao, a.espessura_mm, a.arquivo, a.extensao,
		a.tamanho, a.visualizacoes, a.ativo, a.created_at,
		c.nome as categoria, u.nome as usernome, u.sobrenome as usersobrenome, i.nome, i.id as id_img
		FROM arquivos as a
		INNER JOIN categorias as c ON a.id_categoria = c.id
		INNER JOIN usuarios as u ON a.id_usuario = u.id
		LEFT JOIN (
			SELECT id_arquivo, nome, id, ROW_NUMBER() OVER (PARTITION BY id_arquivo ORDER BY id) as row_num
			FROM imagens
		) i ON a.id = i.id_arquivo
		WHERE a.id_categoria = $id_categoria  and a.id != $id_arquivo and i.row_num = 1 OR i.row_num IS NULL order by a.id desc limit $limit";
		}

		return $this->db->query($sql)->fetchAll(PDO::FETCH_DEFAULT);
	}


	public function getArquivosComImagemPaginacao($offset, $porPagina)
	{
		$sql = "SELECT a.id as id, a.id_usuario, a.id_categoria, a.titulo, a.descricao, a.espessura_mm, a.arquivo, a.extensao,
		a.tamanho, a.visualizacoes, a.ativo, a.created_at,
		c.nome as categoria, u.nome as usernome, u.sobrenome as usersobrenome, i.nome, i.id as id_img
		FROM arquivos as a
		INNER JOIN categorias as c ON a.id_categoria = c.id
		INNER JOIN usuarios as u ON a.id_usuario = u.id
		LEFT JOIN (
			SELECT id_arquivo, nome, id, ROW_NUMBER() OVER (PARTITION BY id_arquivo ORDER BY id) as row_num
			FROM imagens
		) i ON a.id = i.id_arquivo
		WHERE i.row_num = 1 OR i.row_num IS NULL order by a.id desc LIMIT $offset, $porPagina";

		return $this->db->query($sql)->fetchAll();
	}


	public function getArquivosPesquisaSimples($offset, $porPagina, $pesquisa)
	{
		$sql = "SELECT a.id, a.id_usuario, a.id_categoria, a.titulo, a.descricao, a.espessura_mm, a.arquivo, a.extensao,
		a.tamanho, a.visualizacoes, a.ativo, a.created_at,
		c.nome as categoria, u.nome as usernome, u.sobrenome as usersobrenome, i.nome, i.id
		FROM arquivos as a
			INNER JOIN categorias as c ON a.id_categoria = c.id
			INNER JOIN usuarios as u ON a.id_usuario = u.id
			LEFT JOIN (
				SELECT id_arquivo, nome, id
				FROM imagens
				WHERE id IN (
					SELECT MIN(id)
					FROM imagens
					GROUP BY id_arquivo
				)
			) i ON a.id = i.id_arquivo
		WHERE ((i.id IS NOT NULL) AND a.titulo LIKE '%$pesquisa%') OR a.extensao = '$pesquisa'
		ORDER BY a.id DESC
		LIMIT $offset, $porPagina";

		// dd($sql);

		return $this->db->query($sql)->fetchAll();
	}




	//recuperar uma arquivo por id
	public function getArquivoPorId($id)
	{
		$query = "select 
			a.id, a.id_usuario, a.id_categoria, a.titulo, a.descricao, a.espessura_mm, a.arquivo, a.extensao, a.tamanho, a.visualizacoes, a.ativo, a.created_at, 
			c.nome as categoria,
			u.nome as usernome, u.sobrenome as usersobrenome
			from arquivos as a 
			inner join categorias as c on a.id_categoria = c.id 
			inner join usuarios as u on a.id_usuario = u.id
			and a.id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}


	//atualizar
	public function atualizar()
	{

		// dd($this);

		$query = "update arquivos set titulo = :titulo, id_categoria = :id_categoria,  descricao = :descricao, 
		espessura_mm = :espessura_mm, updated_at = NOW()";

		if ($this->__get("tamanho") > 0) {
			$query .= ", arquivo = :arquivo, extensao = :extensao, tamanho = :tamanho";
		}

		$query .= " where id=:id";

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':titulo', $this->__get('titulo'));
		$stmt->bindValue(':id_categoria', $this->__get('id_categoria'));
		$stmt->bindValue(':descricao', $this->__get('descricao'));
		$stmt->bindValue(':espessura_mm', $this->__get('espessura_mm'));

		if ($this->__get("tamanho") > 0) {
			$stmt->bindValue(':arquivo', $this->__get('arquivo'));
			$stmt->bindValue(':extensao', $this->__get('extensao'));
			$stmt->bindValue(':tamanho', $this->__get('tamanho'));

			removerArquivoServidorPorId("arquivos", $this->__get('id'));
		}

		$stmt->bindValue(':id', $this->__get('id'));

		$stmt->execute();

		return $this;
	}


	public function getImagensArquivoPorId($id)
	{
		// dd($this);
		$query = "select id, id_arquivo, nome, created_at from imagens where id_arquivo = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}






























	public function getTotalCategorias()
	{
		$query = "select count(id) as qtdeCategorias from categorias";

		return $this->db->query($query)->fetchObject()->qtdeUsuarios;
	}

	public function deletarArquivo($id)
	{
		removerImagemServidorPorId("imagens", $id);

		$query = "delete from imagens where id_arquivo = :id_arquivo";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_arquivo', $id);
		$stmt->execute();

		// $query = "delete from usuarios where id = :id_usuario";
		$query = "delete from arquivos where id = :id_arquivo";

		removerArquivoServidorPorId("arquivos", $id);

		// $query = "update categorias set ativo = 0, deleted_at = NOW() where id = :id_categoria";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_arquivo', $id);
		$stmt->execute();

		return true;
	}
}
