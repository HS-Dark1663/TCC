<?php

namespace App\Models;

use Core\Model\Model;

class ImagemModel extends Model
{

	private $id;
	private $id_arquivo;
	private $nome;
	private $created_at;


	public function __get($atributo)
	{
		return $this->$atributo;
	}

	public function __set($atributo, $valor)
	{
		$this->$atributo = $valor;
	}


	//salvar
	public function salvar()
	{
		$query = "insert into imagens (id_arquivo, nome) values (:id_arquivo, :nome)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_arquivo', $this->__get('id_arquivo'));
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->execute();

		return $this;
	}

	public function excluirImagem($id)
	{

		$imagem_deletar = $this->getImagemPorId($id);
		// dd($imagem_deletar);

		if (removerArquivoServidor('imagens', $imagem_deletar['nome'])) {

			$query = "delete from imagens where id = :id";
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(':id', $imagem_deletar['id']);
			$stmt->execute();

			return true;
		} else {
			return false;
		}
	}


	public function getTotalImagemPorId($id)
	{
		$query = "select count(id) from imagens where id_arquivo = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $id);
		return $stmt->execute();

		
	}


	//recuperar uma categoria por id
	public function getImagemPorId($id = null)
	{
		if ($id == null) {
			$id = $this->__get('id');
		}

		$query = "select id, id_arquivo, nome, created_at from imagens where id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getImagemPorIdArquivo($id = null)
	{
		if ($id == null) {
			$id = $this->__get('id');
		}

		$query = "select id, id_arquivo, nome, created_at from imagens where id_arquivo = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

	//recuperar um categoria por e-mail
	public function getArquivoPorNome()
	{
		$query = "select id, titulo from arquivos where titulo = :titulo";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':titulo', $this->__get('titulo'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}


	public function getImagens($id_arquivo)
	{
		$sql = "select id, id_arquivo, nome, created_at from imagens where id_arquivo = :id_arquivo";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':id_arquivo', $id_arquivo);

		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_DEFAULT);
	}





	//recuperar uma categoria por id
	public function getCategoriaPorId()
	{
		$query = "select id, nome, id_usuario, ativo from categorias where id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}



























	public function getTotalCategorias()
	{
		$query = "select count(id) as qtdeCategorias from categorias";

		return $this->db->query($query)->fetchObject()->qtdeUsuarios;
	}

	public function deletarCategoria($id)
	{
		// $query = "delete from usuarios where id = :id_usuario";
		$query = "update categorias set ativo = 0, deleted_at = NOW() where id = :id_categoria";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_categoria', $id);
		$stmt->execute();

		return true;
	}



	//atualizar
	public function atualizar()
	{

		$query = "update categorias set nome = :nome, updated_at = NOW() where id=:id";

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':nome', $this->__get('nome'));

		$stmt->bindValue(':id', $this->__get('id'));

		$stmt->execute();

		return $this;
	}
}
