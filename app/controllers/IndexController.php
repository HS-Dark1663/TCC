<?php

namespace App\controllers;

//Recursos de nosso MVC
use Core\controller\Action;
use App\controllers\AuthController;

//Models utilizados
use App\models\ProdutoModel;
use App\models\InfoModel;
use Core\model\Container;

class IndexController extends Action
{

    public function index()
    {
        // AuthController::validaAutenticacao();

        $arquivo = Container::getModel("Arquivo");

        // Configurações da paginação
        $porPagina = 4;

        // Número de registros por página
        $paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página atual

        // Cálculo do offset
        $offset = ($paginaAtual - 1) * $porPagina;

        $arquivos = $arquivo->getArquivosComImagemPaginacao($offset, $porPagina);

        //total de registros
        $totalRegistros = count($arquivos);

        // Cálculo do total de páginas
        $totalPaginas = ceil($totalRegistros / $porPagina);

        $totalRegistros = count($arquivo->getArquivosComImagem());

        // Cálculo do total de páginas
        $totalPaginas = ceil($totalRegistros / $porPagina);

        // $arquivos = $arquivo->getArquivosComImagem();
        $this->view->dados = $arquivos;
        $this->view->totalPaginas = $totalPaginas;


        $this->view->title = "CNCVERSE - Home";

        $this->render("index", "template_front1");
    }

    public function login()
    {
        if (AuthController::esta_logado()) {
            header('Location: /admin');
        } else {
            $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
            $this->render("login", "template_front2");
        }
    }



    public function pesquisaSimples()
    {
        $pesquisa = isset($_POST['search']) ? $_POST['search'] : '';

        // dd($pesquisa);

        $arquivo = Container::getModel("Arquivo");
        // Configurações da paginação
        $porPagina = 4;
        // Número de registros por página
        $paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Página atual
        // Cálculo do offset
        $offset = ($paginaAtual - 1) * $porPagina;

        $arquivos = $arquivo->getArquivosPesquisaSimples($offset, $porPagina, $pesquisa);

        //total de registros
        $totalRegistros = count($arquivos);
        // Cálculo do total de páginas
        $totalPaginas = ceil($totalRegistros / $porPagina);
        $totalRegistros = count($arquivos);
        // Cálculo do total de páginas
        $totalPaginas = ceil($totalRegistros / $porPagina);
        // $arquivos = $arquivo->getArquivosComImagem();
        $this->view->dados = $arquivos;
        $this->view->totalPaginas = $totalPaginas;
        $this->view->title = "CNCVERSE - Home";

        $this->render("index", "template_front1");
    }


    public function categorias()
    {
        $categoria = Container::getModel("Categoria");
        $arquivo = Container::getModel("Arquivo");

        $arquivos = $arquivo->getArquivos();
        $this->view->arquivos = $arquivos;

        $categorias = $categoria->getCategorias();

        $this->view->dados = $categorias;
        $this->view->totalCategorias = count($categorias);

        $this->view->title = "CNCVERSE - Home";

        $this->render("categoria", "template_front1");
    }

    public function show()
    {
        $idArquivo = $_GET['ref'];

        $this->view->title = "CNCVerse - Arquivos";

        $arquivo = Container::getModel("Arquivo");
        $arquivos = $arquivo->getArquivoPorId($idArquivo);
        $this->view->dados = $arquivos;   
        
        // $this->view->totalDowns = $arquivo->totalDowns($idArquivo);

        $similares = $arquivo->getArquivosPorCategoria($this->view->dados['titulo'], $this->view->dados['id_categoria'], $this->view->dados['id'], 5);
        $this->view->similares = $similares;

        $imagem = Container::getModel("Imagem");
        $imagens = $imagem->getImagens($arquivos["id"]);
        $this->view->imagens = $imagens;

        $this->render("single", "template_front1");
    }
}
