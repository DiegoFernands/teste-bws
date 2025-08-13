<?php
require_once 'config/database.php';
require_once 'app/models/Database.php';
require_once 'app/models/Cliente.php';

// Pega a URL e a divide em partes
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Define o controlador padrão
$controlador_nome = isset($url[0]) && !empty($url[0]) ? ucwords($url[0]) . 'Controller' : 'ClienteController';

// Define o método padrão
$metodo_nome = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';

// Define os parâmetros
$parametros = $url ? array_slice($url, 2) : [];

// Incluir o controlador
if (file_exists('app/controllers/' . $controlador_nome . '.php')) {
    require_once 'app/controllers/' . $controlador_nome . '.php';
    // Instanciar o controlador
    $controlador = new $controlador_nome;

    // Checar se o método existe
    if (method_exists($controlador, $metodo_nome)) {
        // Chamar o método com os parâmetros
        call_user_func_array([$controlador, $metodo_nome], $parametros);
    } else {
        // Método não encontrado
        die('Método não existe.');
    }
} else {
    // Controlador não encontrado
    die('Controlador não existe.');
}
?>