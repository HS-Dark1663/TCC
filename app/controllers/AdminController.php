<?php

namespace App\controllers;

//Recursos de nosso MVC
use Core\controller\Action;
use App\controllers\AuthController;

//Models utilizados
use Core\model\Container;

class AdminController extends Action
{
    public function index()
    {
        AuthController::validaAutenticacao();        

        $usuario = Container::getModel('Usuario');
        
        $this->view->qtdeUsuarios = $usuario->getTotalUsuarios();
        $this->view->title = "Admin - Home";

        $this->render("index", "template_admin");
    }

    public function dashboard()
    {
        AuthController::validaAutenticacao();

        $this->view->title = "Admin - Dashboard";

        $this->render("dashboard", "template_admin");
    }

    

    
}