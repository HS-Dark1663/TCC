<?php

namespace App\database;

use PDO;
use PDOException;

class Connection
{

    //static não precisa criar uma classe de Connectio para
    //usar o método getDb, nesse casso podemos importar a 
    //classe (use nome da classe) e usar o nome da classe seguido de 
    //dois pontos (Connection::getDb)
    public static function getDb()
    {
        # Informações sobre o banco de dados:
        $host         =   "localhost";
        $db_name      =   "cncverse"; 
        $user         =   "root";
        $pass         =   "root"; //S3cr3tA! FTP 875801S3cr3tA!
        $charset      =   "utf8";
        $db_driver    =   "mysql";
        $port         =   3306;

        # Informações sobre o sistema:
        $sistema_titulo = "MVC";
        $sistema_email = "fabioeduardofaria@gmail.com";

        // try {
            $conn = new PDO(
                $db_driver.
                ':host=' . $host . 
                ';dbname=' . $db_name . 
                ';port=' . $port . 
                ';charset=' . $charset,
                $user,
                $pass
            );

            return $conn;
        // } catch (PDOException $e) {
        //     //.. tratar de alguma forma ..//
        //     # Envia um e-mail para o e-mail oficial do sistema, em caso de erro de conexão.
        //     // mail($sistema_email, "PDOException em $sistema_titulo", $e->getMessage());
        //     # Então não carrega nada mais da página.
        //     die("Connection Error: " . $e->getMessage());
        // }
    }
}
