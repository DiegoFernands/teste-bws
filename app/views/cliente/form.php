<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2><?= ($acao == 'adicionar') ? 'Adicionar Cliente' : 'Editar Cliente'; ?></h2>
            <p><?= ($acao == 'adicionar') ? 'Preencha o formulário para cadastrar um novo cliente' : 'Altere os dados do cliente e salve'; ?></p>
            <form action="<?= ($acao == 'adicionar') ? URLROOT . '/cliente/adicionar' : URLROOT . '/cliente/editar/' . $id; ?>" method="post">
                <div class="form-group">
                    <label for="nome">Nome: <sup>*</sup></label>
                    <input type="text" name="nome" id="nome" class="form-control form-control-lg" maxlength="150" required value="<?= htmlspecialchars($nome_cliente); ?>">
                </div>
                <div class="form-group">
                    <label for="cpf">CPF: <sup>*</sup></label>
                    <input type="text" name="cpf" id="cpf" class="form-control form-control-lg" maxlength="11" minlength="11" pattern="[0-9]{11}" title="Somente 11 dígitos" required value="<?= htmlspecialchars($cpf_cliente); ?>">
                    <?php if (!empty($erro_cpf_formato)) : ?>
                        <div class="alert alert-danger mt-2"><?= $erro_cpf_formato; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($erro_cpf_existente)) : ?>
                        <div class="alert alert-danger mt-2"><?= $erro_cpf_existente; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento: <sup>*</sup></label>
                    <input type="date" name="data_nascimento" id="data_nascimento" class="form-control form-control-lg" required max="<?= date('Y-m-d'); ?>" value="<?= htmlspecialchars($data_nascimento_cliente); ?>">
                </div>
                <?php if ($acao == 'adicionar') : ?>
                <div class="form-group">
                    <label for="data_cadastro">Data de Cadastro:</label>
                    <input type="date" name="data_cadastro" id="data_cadastro" class="form-control form-control-lg" readonly value="<?= date('Y-m-d'); ?>">
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="renda_familiar">Renda Familiar:</label>
                    <input type="number" name="renda_familiar" id="renda_familiar" class="form-control form-control-lg" min="0" step="0.01" value="<?= htmlspecialchars($renda_familiar_cliente); ?>">
                </div>

                <div class="row">
                    <div class="col">
                        <input type="submit" value="Salvar" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="<?= URLROOT; ?>/cliente/index" class="btn btn-secondary btn-block">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>