# teste-bws
Teste bws

# Sistema de Clientes

Este é um sistema de gerenciamento de clientes simples, desenvolvido em PHP com o padrão de arquitetura MVC (Model-View-Controller). O sistema permite realizar operações de CRUD (Create, Read, Update, Delete) de clientes e visualizar relatórios básicos sobre a base de dados.

## Funcionalidades

* **CRUD de Clientes**: Cadastrar, listar, editar e excluir clientes.
* **Validação de CPF**: O sistema verifica se o formato do CPF é válido e se ele já existe na base de dados.
* **Página de Relatórios**: Exibe cards com dados resumidos sobre a base de clientes, como:
    * Total de clientes maiores de 18 anos com renda acima da média.
    * Contagem de clientes por classe de renda (A, B, C).
    * Total de cadastros realizados no período selecionado (hoje, esta semana, este mês).
* **Interface Responsiva**: O layout é construído com Bootstrap 4, sendo adaptável a diferentes tamanhos de tela.

## Tecnologias Utilizadas

* **Backend**: PHP 7+
* **Banco de Dados**: MySQL
* **Framework/Arquitetura**: Padrão MVC
* **Frontend**: HTML5, CSS3, Bootstrap
* **Servidor Web**: Apache (via XAMPP)

## Pré-requisitos

Para rodar este projeto, você precisa ter o **XAMPP** instalado e configurado em sua máquina. O XAMPP inclui o Apache (servidor web), o MySQL (banco de dados) e o PHP, tudo o que você precisa.

* [Download do XAMPP](https://www.apachefriends.org/pt_br/index.html)

## Como Fazer o Projeto Funcionar

Siga os passos abaixo para configurar e executar o projeto em seu ambiente local.

### 1. Configuração do Banco de Dados

1.  Abra o painel de controle do XAMPP e inicie os módulos **Apache** e **MySQL**.
2.  Acesse o phpMyAdmin em seu navegador: `http://localhost/phpmyadmin`.
3.  Clique em **"Novo"** no menu lateral esquerdo para criar um novo banco de dados.
4.  Nomeie o banco de dados como `clientes_db` e clique em "Criar".
5.  Selecione o banco de dados `clientes_db` que você acabou de criar.
6.  Vá para a aba **"SQL"** e execute o seguinte script para criar a tabela `clientes`:

    ```sql
    CREATE TABLE `clientes` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nome` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
      `cpf` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
      `data_nascimento` date NOT NULL,
      `data_cadastro` date NOT NULL,
      `renda_familiar` decimal(10,2) DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `cpf` (`cpf`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ```

### 2. Configuração do Projeto

1.  **Copie a pasta do projeto (`sistema_clientes`)** para o diretório `htdocs` do seu XAMPP. O caminho padrão é `C:\xampp\htdocs\` no Windows ou `/Applications/XAMPP/htdocs/` no macOS.
2.  **Abra o arquivo de configuração** em `sistema_clientes/config/database.php`.
3.  **Edite as constantes de conexão** com seu usuário e senha do MySQL, se forem diferentes do padrão do XAMPP (`root` sem senha).

    ```php
    <?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root'); // Geralmente é 'root' no XAMPP
    define('DB_PASS', '');    // Geralmente é vazio no XAMPP
    define('DB_NAME', 'clientes_db');
    define('URLROOT', 'http://localhost/sistema_clientes'); // Altere se o nome da pasta for diferente
    ?>
    ```

### 3. Habilitando o Mod_Rewrite

Para que o roteamento de URLs amigáveis funcione, o módulo `mod_rewrite` do Apache precisa estar ativado. No XAMPP, ele já vem ativado, mas é sempre bom verificar. Certifique-se também de que o arquivo `.htaccess` está na pasta raiz do projeto.

### 4. Acessando o Sistema

Com o Apache e o MySQL rodando no XAMPP, você pode acessar a aplicação em seu navegador:

`http://localhost/sistema_clientes/`

A página inicial exibirá a lista de clientes. A partir daí, você pode navegar pelas funcionalidades de CRUD e relatórios.

---

Se precisar de qualquer outra ajuda ou ajuste, é só me dizer!