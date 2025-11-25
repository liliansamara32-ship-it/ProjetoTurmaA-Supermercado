<?php
/**
 * Arquivo de entrada principal da aplicação.
 *
 * Este arquivo é responsável por:
 * - Carregar as dependências do Composer.
 * - Definir a URL base da aplicação dinamicamente.
 * - Inicializar o roteador.
 * - Definir as rotas da aplicação (públicas, de login e administrativas).
 * - Despachar a requisição para o controlador e método apropriados.
 */

// Carrega as dependências do Composer (autoload).
require "vendor/autoload.php";

session_start();

$db = new GrupoA\Supermercado\Model\Database();

// Determina o protocolo (HTTP ou HTTPS) da requisição atual.
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
// Obtém o host da requisição (ex: localhost, www.example.com).
$host = $_SERVER['HTTP_HOST'];
// Obtém o nome do script atual (index.php).
$scriptName = $_SERVER['SCRIPT_NAME'];
// Calcula o caminho base da aplicação, removendo o nome do script.
$basePath = str_replace(basename($scriptName), '', $scriptName);
// Constrói a URL base completa da aplicação.
$URL = "{$protocol}://{$host}{$basePath}";

// Cria o roteador utilizando a URL base definida.
$roteador = new CoffeeCode\Router\Router($URL);

// Informa o namespace padrão onde os controladores da aplicação se encontram.
$roteador->namespace("GrupoA\Supermercado\Controller");

// === Definição das Rotas ===

// Grupo de rotas públicas (sem prefixo).
$roteador->group(null);
// Rota para a página principal.
$roteador->get("/", "Principal:paginaPrincipal");

$roteador->get("/checkout", "Checkout:paginaCheckout");
$roteador->post("/checkout", "Checkout:finalizarPedido");

$roteador->get("/registro", "Registro:paginaRegistro");
$roteador->post('/novoUsuario', "Registro:novoUsuario");

// === Área administrativa ===
// Grupo de rotas relacionadas ao login.
$roteador->group("login");
$roteador->get("/", "Login:formularioLogin");
$roteador->post("/autenticar", "Login:autenticar");
// Exemplo de rota de login (a ser implementada ou expandida).
// $roteador->get("/entrar", "Login:mostrarFormulario");
// $roteador->post("/autenticar", "Login:autenticar");
// $roteador->get("/sair", "Login:logout");

// Grupo de rotas administrativas.
$roteador->group("admin");
// Rota para o formulário de edição de produto.
$roteador->get("/produto/{id}/editar", "Admin:formularioEditarProduto");
// Rota para processar a edição de produto.
$roteador->post("/produto/editar", "Admin:editarProduto");


// Despacha a requisição atual para a rota correspondente.
$roteador->dispatch();