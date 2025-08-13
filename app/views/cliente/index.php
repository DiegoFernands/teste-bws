<div class="card card-body mb-3">
    <div class="row mb-3">
        <div class="col-sm-12 col-md-6">
            <h1>Clientes</h1>
        </div>
        <div class="col-sm-12 col-md-6 text-sm-center text-md-right">
            <a href="<?= URLROOT; ?>/cliente/adicionar" class="btn btn-primary">
                <i class="fas fa-plus btn-adicionar"></i> Adicionar Cliente
            </a>
        </div>
    </div>

    <div class="card card-body mb-3">
        <div class="row">
            <div class="col-md-12">
                <form action="<?= URLROOT; ?>/cliente/index" method="get" class="form-inline">
                    <div class="form-group mb-2">
                        <label for="search" class="sr-only">Pesquisar por Nome</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Pesquisar por nome...">
                    </div>
                    <button type="submit" class="btn btn-secondary mb-2 ml-2">Pesquisar</button>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Renda</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes_lista as $cliente) : ?>
                <tr>
                    <td><?= htmlspecialchars($cliente->nome); ?></td>
                    <td>
                        <?php
                            $renda = (float) $cliente->renda_familiar;
                            if ($renda <= 980.00) {
                                $classe_badge = 'badge-a';
                            } elseif ($renda > 980.00 && $renda <= 2500.00) {
                                $classe_badge = 'badge-b';
                            } else {
                                $classe_badge = 'badge-c';
                            }
                        ?>
                        <span class="<?= $classe_badge; ?>">R$ <?= number_format($renda, 2, ',', '.'); ?></span>
                    </td>
                    <td>
                        <a href="<?= URLROOT; ?>/cliente/editar/<?= $cliente->id; ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?= URLROOT; ?>/cliente/deletar/<?= $cliente->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este cliente?');">
                            <i class="fas fa-trash-alt"></i> Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>