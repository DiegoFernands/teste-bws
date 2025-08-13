<?php
class ClienteController {
    private $cliente_model;

    public function __construct() {
        $this->cliente_model = new Cliente();
    }

    public function index() {
        // Pega o termo de pesquisa do GET se existir
        $termo_pesquisa = isset($_GET['search']) ? $_GET['search'] : '';

        if (!empty($termo_pesquisa)) {
            $clientes = $this->cliente_model->pesquisar_clientes($termo_pesquisa);
        } else {
            $clientes = $this->cliente_model->listar_clientes();
        }

        $dados = [
            'clientes_lista' => $clientes
        ];

        $this->carregar_view('cliente/index', $dados);
    }

    public function adicionar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST);

            $dados = [
                'nome_cliente' => trim($_POST['nome']),
                'cpf_cliente' => trim($_POST['cpf']),
                'data_nascimento_cliente' => trim($_POST['data_nascimento']),
                'renda_familiar_cliente' => trim($_POST['renda_familiar']),
                'acao' => 'adicionar',
                'erro_cpf_formato' => '',
                'erro_cpf_existente' => ''
            ];
            
            // Validação de formato do CPF
            if (!$this->validar_cpf($dados['cpf_cliente'])) {
                $dados['erro_cpf_formato'] = 'O CPF inserido não é válido.';
                $this->carregar_view('cliente/form', $dados);
                return;
            }

            // Validação de CPF existente
            if ($this->cliente_model->encontrar_cliente_por_cpf($dados['cpf_cliente'])) {
                $dados['erro_cpf_existente'] = 'Este CPF já está cadastrado.';
                $this->carregar_view('cliente/form', $dados);
                return;
            }

            // Se passar nas duas validações, insere o cliente
            if ($this->cliente_model->inserir_cliente($dados)) {
                header('Location: ' . URLROOT . '/cliente/index');
            } else {
                die('Algo deu errado ao inserir o cliente.');
            }
        } else {
            $dados = [
                'nome_cliente' => '',
                'cpf_cliente' => '',
                'data_nascimento_cliente' => '',
                'renda_familiar_cliente' => '',
                'acao' => 'adicionar',
                'erro_cpf_formato' => '',
                'erro_cpf_existente' => ''
            ];
            $this->carregar_view('cliente/form', $dados);
        }
    }

    public function editar($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST);

            $dados = [
                'id' => $id,
                'nome_cliente' => trim($_POST['nome']),
                'cpf_cliente' => trim($_POST['cpf']),
                'data_nascimento_cliente' => trim($_POST['data_nascimento']),
                'renda_familiar_cliente' => trim($_POST['renda_familiar'])
            ];
            
            $cliente_atual = $this->cliente_model->encontrar_cliente_por_id($id);
            if ($cliente_atual->cpf != $dados['cpf_cliente'] && $this->cliente_model->encontrar_cliente_por_cpf($dados['cpf_cliente'])) {
                die('Erro: CPF já cadastrado para outro cliente!');
            }

            if ($this->cliente_model->atualizar_cliente($dados)) {
                header('Location: ' . URLROOT . '/cliente/index');
            } else {
                die('Algo deu errado ao atualizar o cliente.');
            }
        } else {
            $cliente = $this->cliente_model->encontrar_cliente_por_id($id);
            $dados = [
                'id' => $id,
                'nome_cliente' => $cliente->nome,
                'cpf_cliente' => $cliente->cpf,
                'data_nascimento_cliente' => $cliente->data_nascimento,
                'renda_familiar_cliente' => $cliente->renda_familiar,
                'acao' => 'editar'
            ];
            $this->carregar_view('cliente/form', $dados);
        }
    }
    public function deletar($id) {
        if ($this->cliente_model->deletar_cliente($id)) {
            header('Location: ' . URLROOT . '/cliente/index');
        } else {
            die('Algo deu errado ao deletar o cliente.');
        }
    }

    public function carregar_view($view_caminho, $dados_view = []) {
        extract($dados_view);
        
        // Caminho  para inclusão do header
        require_once __DIR__ . '/../views/header.php';

        $caminho_view_completo = __DIR__ . '/../views/' . $view_caminho . '.php';
        if (file_exists($caminho_view_completo)) {
            require_once $caminho_view_completo;
        } else {
            die('A view não existe: ' . $caminho_view_completo);
        }
        
        // Caminho  para inclusão do footer
        require_once __DIR__ . '/../views/footer.php';
    }

    // Método que gera o relátorio
    public function relatorios() {
        $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'mes';
        $data_inicio_periodo = '';

        switch ($periodo) {
            case 'hoje':
                $data_inicio_periodo = date('Y-m-d');
                break;
            case 'semana':
                $data_inicio_periodo = date('Y-m-d', strtotime('last monday'));
                break;
            case 'mes':
            default:
                $data_inicio_periodo = date('Y-m-01');
                break;
        }

        $clientes_maiores_18 = $this->cliente_model->get_clientes_maiores_18_acima_media($data_inicio_periodo);
        $clientes_por_classe = $this->cliente_model->get_clientes_por_classe($data_inicio_periodo);

        $dados = [
            'total_maiores_18' => $clientes_maiores_18,
            'total_classe_a' => $clientes_por_classe['classe_a'],
            'total_classe_b' => $clientes_por_classe['classe_b'],
            'total_classe_c' => $clientes_por_classe['classe_c'],
            'periodo_selecionado' => $periodo
        ];

        $this->carregar_view('cliente/relatorios', $dados);
    }

    // Função tirada de um projeto
    public function validar_cpf($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', (string) $cpf);
        
        // Verifica se a string tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }
        
        // Verifica se todos os dígitos são iguais (ex: 111.111.111-11)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        // Calcula os dígitos verificadores para checar se o CPF é válido
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}