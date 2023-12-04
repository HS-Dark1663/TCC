<?php

namespace App\controllers;

use Core\model\Container;
use Core\controller\Action;
use App\controllers\AuthController;

class UsuarioController extends Action
{
    public function index()
    {
        AuthController::validaAutenticacao();

        $usuario = Container::getModel("Usuario");
        $usuarios = $usuario->getUsuarios();
        $this->view->dados = $usuarios;

        $this->render("index", "template_admin");
    }

    public function cadastrar()
    {
        AuthController::validaAutenticacao();

        $this->render("cadastrar", "template_admin");
    }

    public function salvar_usuario()
    {
        AuthController::validaAutenticacao();
        //receber dados do formulario via post 
        //faz a instancia de produto com a conexão com banco de dados
        $usuario = Container::getModel('Usuario');

        // upload($_FILES['file_imagem'], "usuario");

        //seta os dados do form nos atributos da classe Usuário
        $usuario->__set('nome', isset($_POST['nome']) ? $_POST['nome'] : "");
        $usuario->__set('sobrenome', isset($_POST['sobrenome']) ? $_POST['sobrenome'] : "");
        $usuario->__set('email', isset($_POST['email']) ? $_POST['email'] : "");
        $usuario->__set('senha', isset($_POST['senha']) ? md5($_POST['senha']) : "");
        $usuario->__set('tipo', isset($_POST['tipo']) ? $_POST['tipo'] : "");

        if ($usuario->validarCadastro()) {
            //SUCCESS ao validar cadastro
            if (count($usuario->getUsuarioPorEmail()) == 0) {
                $usuario->__set('imagem', $_FILES['file_imagem']['size'] > 0 ? upload($_FILES['file_imagem'], "usuario") : "");
                $usuario->salvar();

                //podemos criar um atributo generico no objeto pois estamos herdando de action o view
                $this->view->status = array(
                    "status" => "SUCCESS",
                    "msg"    => "Cadastro realizado com sucesso"
                );

                $this->render("cadastrar", "template_admin");
            } else {
                //caso retornar 1 linha na query - indica que ja esta cadastrado no banco de dados
                $this->view->status = array(
                    "status" => "ERROR",
                    "msg"    => "Erro ao cadastrar usuário, e-mail já existe no banco de dados"
                );

                $this->view->tempUsuario = array(
                    "nome"      => isset($_POST['nome']) ? $_POST['nome'] : "",
                    "sobrenome" => isset($_POST['sobrenome']) ? $_POST['sobrenome'] : "",
                    "email"     => isset($_POST['email']) ? $_POST['email'] : "",
                    "senha"     => isset($_POST['senha']) ? $_POST['senha'] : "",
                    "tipo"     => isset($_POST['tipo']) ? $_POST['tipo'] : "",
                );

               

                $this->render("cadastrar", "template_admin");
            }
        } else {
            //erro na digitação < que 3 caracteres
            //armazena os dados para recarregar o form

            $this->view->tempUsuario = array(
                "nome" => isset($_POST['nome']) ? $_POST['nome'] : "",
                "sobrenome"    => isset($_POST['sobrenome']) ? $_POST['sobrenome'] : "",
                "email"    => isset($_POST['email']) ? $_POST['email'] : "",
                "senha"    => isset($_POST['senha']) ? $_POST['senha'] : "",
                "tipo"    => isset($_POST['tipo']) ? $_POST['tipo'] : "",
            );

            // dd($this->view->tempUsuario);

            $this->view->status = array(
                "status" => "ERROR",
                "msg"    => "Erro ao cadastrar usuário, verifique os campos digitados e tente novamente"
            );
            $this->render("cadastrar", "template_admin");
        }
    }

    public function atualizar()
    {
        AuthController::validaAutenticacao();

  

        //faz a instancia de produto com a conexão com banco de dados
        $usuario = Container::getModel('Usuario');

        $senha = isset($_POST['senha']) ? $_POST['senha'] : "";
        $senha_atual = isset($_POST['senha_atual']) ? $_POST['senha_atual'] : "";

        $email = isset($_POST['email']) ? $_POST['email'] : "";
        $email_atual = isset($_POST['email_atual']) ? $_POST['email_atual'] : "";


        //seta os dados do form nos atributos da classe Usuário
        $usuario->__set('id', isset($_POST['id']) ? $_POST['id'] : "");
        $usuario->__set('nome', isset($_POST['nome']) ? $_POST['nome'] : "");
        $usuario->__set('sobrenome', isset($_POST['sobrenome']) ? $_POST['sobrenome'] : "");
        $usuario->__set('email',  $email);
        $usuario->__set('tipo', isset($_POST['tipo']) ? $_POST['tipo'] : "");

        date_default_timezone_set('America/Cuiaba');
        $usuario->__set('updated_at', date('Y-m-d H:i:s'));


        if ($senha === '') {
            $usuario->__set('senha', $senha_atual);
        } else {
            $usuario->__set('senha', md5($senha));
        }

        if ($usuario->validarCadastro()) {
            //SUCCESS ao validar cadastro

            if (count($usuario->getUsuarioPorEmail()) == 0 || $email === $email_atual) {
                $usuario->__set('imagem', $_FILES['file_imagem']['size'] > 0 ? upload($_FILES['file_imagem'], "usuario") : "");
                $usuario->atualizar();

                //podemos criar um atributo generico no objeto pois estamos herdando de action o view
                $this->view->status = array(
                    "status" => "SUCCESS",
                    "msg"    => "Usuário atulizado com sucesso"
                );

                $usuarios = $usuario->getUsuarioPorId();
                $this->view->dados = $usuarios;

                $this->render("editar", "template_admin");
            } else {
                //caso retornar 1 linha na query - indica que ja esta cadastrado no banco de dados
                $this->view->status = array(
                    "status" => "ERROR",
                    "msg"    => "Erro ao cadastraeditarr usuário, e-mail já existe no banco de dados"
                );

                $this->view->tempUsuario = array(
                    "nome"      => isset($_POST['nome']) ? $_POST['nome'] : "",
                    "sobrenome" => isset($_POST['sobrenome']) ? $_POST['sobrenome'] : "",
                    "email"     => isset($_POST['email']) ? $_POST['email'] : "",
                    "senha"     => isset($_POST['senha']) ? $_POST['senha'] : "",
                    "tipo"     => isset($_POST['tipo']) ? $_POST['tipo'] : "",
                );

                $this->render("editar", "template_admin");
            }
        } else {
            //erro na digitação < que 3 caracteres
            //armazena os dados para recarregar o form

            $this->view->tempUsuario = array(
                "nome" => isset($_POST['nome']) ? $_POST['nome'] : "",
                "sobrenome"    => isset($_POST['sobrenome']) ? $_POST['sobrenome'] : "",
                "email"    => isset($_POST['email']) ? $_POST['email'] : "",
                "senha"    => isset($_POST['senha']) ? $_POST['senha'] : "",
                "tipo"    => isset($_POST['tipo']) ? $_POST['tipo'] : "",
            );

            // dd($this->view->tempUsuario);

            $this->view->status = array(
                "status" => "ERROR",
                "msg"    => "Erro ao editar usuário, verifique os campos digitados e tente novamente"
            );
            $this->render("editar", "template_admin");
        }
    }

    public function editar()
    {
        AuthController::validaAutenticacao();

        $usuario = Container::getModel('Usuario');

        //seta o id no atributos id da classe Usuário
        $usuario->__set('id', isset($_GET['id']) ? $_GET['id'] : "");

        $usuarios = $usuario->getUsuarioPorId();
        $this->view->dados = $usuarios;

        $this->render("editar", "template_admin");
    }

    public function excluir()
    {
        AuthController::validaAutenticacao();       

        $id_usuario = isset($_GET['id']) ? $_GET['id'] : '';

        $usuario = Container::getModel('Usuario');
        $usuario->deletarUsuario($id_usuario);

        // $this->index();
        header("Location: /usuario");
    }
}



// IndexController
