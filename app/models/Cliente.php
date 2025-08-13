<?php
require_once 'Database.php';

class Cliente {
    private $db_cliente;

    public function __construct() {
        $this->db_cliente = new Database;
    }

    // Método para listar todos os clientes
    public function listar_clientes() {
        $this->db_cliente->query('SELECT * FROM clientes ORDER BY nome ASC');
        return $this->db_cliente->resultSet();
    }

    // Método para inserir um novo cliente
    public function inserir_cliente($dados_cliente) {
        $this->db_cliente->query('INSERT INTO clientes (nome, cpf, data_nascimento, data_cadastro, renda_familiar) VALUES (:nome, :cpf, :data_nascimento, :data_cadastro, :renda_familiar)');
        
        // Bind dos valores
        $this->db_cliente->bind(':nome', $dados_cliente['nome_cliente']);
        $this->db_cliente->bind(':cpf', $dados_cliente['cpf_cliente']);
        $this->db_cliente->bind(':data_nascimento', $dados_cliente['data_nascimento_cliente']);
        $this->db_cliente->bind(':data_cadastro', date('Y-m-d'));
        $this->db_cliente->bind(':renda_familiar', empty($dados_cliente['renda_familiar_cliente']) ? null : $dados_cliente['renda_familiar_cliente']);

        if ($this->db_cliente->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Método para encontrar um cliente por ID
    public function encontrar_cliente_por_id($id_cliente) {
        $this->db_cliente->query('SELECT * FROM clientes WHERE id = :id');
        $this->db_cliente->bind(':id', $id_cliente);
        return $this->db_cliente->single();
    }

    // Método para atualizar um cliente
    public function atualizar_cliente($dados_cliente) {
        $this->db_cliente->query('UPDATE clientes SET nome = :nome, cpf = :cpf, data_nascimento = :data_nascimento, renda_familiar = :renda_familiar WHERE id = :id');
        
        // Bind dos valores
        $this->db_cliente->bind(':id', $dados_cliente['id']);
        $this->db_cliente->bind(':nome', $dados_cliente['nome_cliente']);
        $this->db_cliente->bind(':cpf', $dados_cliente['cpf_cliente']);
        $this->db_cliente->bind(':data_nascimento', $dados_cliente['data_nascimento_cliente']);
        $this->db_cliente->bind(':renda_familiar', empty($dados_cliente['renda_familiar_cliente']) ? null : $dados_cliente['renda_familiar_cliente']);

        if ($this->db_cliente->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Método para deletar um cliente
    public function deletar_cliente($id_cliente) {
        $this->db_cliente->query('DELETE FROM clientes WHERE id = :id');
        $this->db_cliente->bind(':id', $id_cliente);

        // Executar
        if ($this->db_cliente->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Método para encontrar cliente por CPF (para validação)
    public function encontrar_cliente_por_cpf($cpf_cliente) {
        $this->db_cliente->query('SELECT * FROM clientes WHERE cpf = :cpf');
        $this->db_cliente->bind(':cpf', $cpf_cliente);
        $this->db_cliente->single();

        // Checar se o CPF já existe
        if ($this->db_cliente->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Método para pesquisar clientes por nome
    public function pesquisar_clientes($nome_cliente) {
        $this->db_cliente->query('SELECT * FROM clientes WHERE nome LIKE :nome ORDER BY nome ASC');
        $this->db_cliente->bind(':nome', '%' . $nome_cliente . '%');
        return $this->db_cliente->resultSet();
    }

    public function get_clientes_maiores_18_acima_media($periodo = null) {
        $sql = "SELECT COUNT(*) AS total FROM clientes WHERE DATEDIFF(CURDATE(), data_nascimento) / 365.25 >= 18 AND renda_familiar > (SELECT AVG(renda_familiar) FROM clientes WHERE renda_familiar IS NOT NULL)";

        // Adiciona o filtro de período
        if ($periodo) {
            $sql .= " AND data_cadastro >= :periodo_inicio";
        }

        $this->db_cliente->query($sql);

        if ($periodo) {
            $this->db_cliente->bind(':periodo_inicio', $periodo);
        }

        return $this->db_cliente->single()->total;
    }

    // Método para obter a contagem de clientes por classe
    public function get_clientes_por_classe($periodo = null) {
        $sql_a = "SELECT COUNT(*) AS total_a FROM clientes WHERE renda_familiar <= 980.00 OR renda_familiar IS NULL";
        $sql_b = "SELECT COUNT(*) AS total_b FROM clientes WHERE renda_familiar > 980.00 AND renda_familiar <= 2500.00";
        $sql_c = "SELECT COUNT(*) AS total_c FROM clientes WHERE renda_familiar > 2500.00";

        // Adiciona o filtro de período
        if ($periodo) {
            $sql_a .= " AND data_cadastro >= :periodo_inicio";
            $sql_b .= " AND data_cadastro >= :periodo_inicio";
            $sql_c .= " AND data_cadastro >= :periodo_inicio";
        }

        // Consulta Classe A
        $this->db_cliente->query($sql_a);
        if ($periodo) {
            $this->db_cliente->bind(':periodo_inicio', $periodo);
        }
        $total_a = $this->db_cliente->single()->total_a;

        // Consulta Classe B
        $this->db_cliente->query($sql_b);
        if ($periodo) {
            $this->db_cliente->bind(':periodo_inicio', $periodo);
        }
        $total_b = $this->db_cliente->single()->total_b;

        // Consulta Classe C
        $this->db_cliente->query($sql_c);
        if ($periodo) {
            $this->db_cliente->bind(':periodo_inicio', $periodo);
        }
        $total_c = $this->db_cliente->single()->total_c;

        return [
            'classe_a' => $total_a,
            'classe_b' => $total_b,
            'classe_c' => $total_c
        ];
    }
}