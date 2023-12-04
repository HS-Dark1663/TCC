<?php

namespace App\controllers;

use Core\model\Container;
use Core\controller\Action;
use App\controllers\AuthController;
use App\Models\ArquivoModel;

class ImagemController extends Action
{
    public function excluir()
    {
        AuthController::validaAutenticacao();

        $id_imagem = isset($_GET['id']) ? $_GET['id'] : '';
        $id_arquivo = isset($_GET['ida']) ? $_GET['ida'] : '';

        $imagem = Container::getModel('Imagem');


        if ($imagem->excluirImagem($id_imagem)) {
            header("Location: /arquivo_editar?id=$id_arquivo");
        }
    }
}



// IndexController
