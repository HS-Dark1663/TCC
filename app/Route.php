<?php

    namespace App;

    use Core\init\Bootstrap;

    class Route extends Bootstrap{
       
        protected function initRoutes() {
            //IndexController
            $routes['home'] =  array('route' => '/', 'controller' => 'indexController', 'action' => 'index');
            $routes['ver_vaga'] =  array('route' => '/ver_vaga', 'controller' => 'indexController', 'action' => 'ver_vaga');
            $routes['contato'] =  array('route' => '/contato', 'controller' => 'indexController', 'action' => 'contato');
            $routes['login'] = array('route' => '/login', 'controller' => 'IndexController', 'action' => 'login');
            $routes['pesquisar'] =  array('route' => '/pesquisar', 'controller' => 'indexController', 'action' => 'pesquisaSimples');
            $routes['categorias'] =  array('route' => '/categorias', 'controller' => 'indexController', 'action' => 'categorias');
            $routes['show'] =  array('route' => '/show', 'controller' => 'indexController', 'action' => 'show');

            //AdminController
            $routes['admin'] =  array('route' => '/admin', 'controller' => 'adminController', 'action' => 'index');
            $routes['dashboard'] = array('route' => '/dashboard', 'controller' => 'adminController', 'action' => 'dashboard');

            //UsuarioController
            $routes['usuario'] = array('route' => '/usuario', 'controller' => 'usuarioController', 'action' => 'index');
            $routes['novo_usuario'] = array('route' => '/novo_usuario', 'controller' => 'usuarioController', 'action' => 'cadastrar');
            $routes['salvar_usuario'] = array('route' => '/salvar_usuario', 'controller' => 'usuarioController', 'action' => 'salvar_usuario');
            $routes['usuario_excluir'] = array('route' => '/usuario_excluir', 'controller' => 'UsuarioController', 'action' => 'excluir');
            $routes['usuario_editar'] = array('route' => '/usuario_editar', 'controller' => 'UsuarioController', 'action' => 'editar');
            $routes['usuario_atualizar'] = array('route' => '/usuario_atualizar', 'controller' => 'UsuarioController', 'action' => 'atualizar');

            //AuthController
            $routes['sair'] = array('route' => '/sair', 'controller' => 'AuthController', 'action' => 'sair');   
            $routes['autenticar'] = array('route' => '/autenticar', 'controller' => 'AuthController', 'action' => 'autenticar');

            //CategoriaController
            $routes['categoria'] = array('route' => '/categoria', 'controller' => 'categoriaController', 'action' => 'index');
            $routes['nova_categoria'] = array('route' => '/nova_categoria', 'controller' => 'categoriaController', 'action' => 'cadastrar');
            $routes['salvar_categoria'] = array('route' => '/salvar_categoria', 'controller' => 'categoriaController', 'action' => 'salvar_categoria');
            $routes['categoria_excluir'] = array('route' => '/categoria_excluir', 'controller' => 'categoriaController', 'action' => 'excluir');
            $routes['categoria_editar'] = array('route' => '/categoria_editar', 'controller' => 'categoriaController', 'action' => 'editar');
            $routes['categoria_atualizar'] = array('route' => '/categoria_atualizar', 'controller' => 'categoriaController', 'action' => 'atualizar');

            //ArquivoController
            $routes['arquivo'] = array('route' => '/arquivo', 'controller' => 'arquivoController', 'action' => 'index');
            $routes['arquivo_show'] = array('route' => '/arquivo_show', 'controller' => 'arquivoController', 'action' => 'arquivo_show');
            $routes['novo_arquivo'] = array('route' => '/novo_arquivo', 'controller' => 'arquivoController', 'action' => 'cadastrar');
            $routes['salvar_arquivo'] = array('route' => '/salvar_arquivo', 'controller' => 'arquivoController', 'action' => 'salvar_arquivo');
            $routes['arquivo_excluir'] = array('route' => '/arquivo_excluir', 'controller' => 'arquivoController', 'action' => 'excluir');
            $routes['arquivo_editar'] = array('route' => '/arquivo_editar', 'controller' => 'arquivoController', 'action' => 'editar');
            $routes['arquivo_atualizar'] = array('route' => '/arquivo_atualizar', 'controller' => 'arquivoController', 'action' => 'atualizar');

            //ImagemController
            $routes['excluirImg'] = array('route' => '/excluirImg', 'controller' => 'ImagemController', 'action' => 'excluir');

            
            
            
            
            $this->setRoutes($routes);
        }

        
    }

?>