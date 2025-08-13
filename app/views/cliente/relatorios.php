<div class="row mb-3">
    <div class="col-md-12">
        <h1>Relatórios</h1>
    </div>
</div>

<div class="card card-body mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="float-right">
                <a href="<?= URLROOT; ?>/cliente/relatorios?periodo=hoje" class="btn btn-<?= ($periodo_selecionado == 'hoje') ? 'primary' : 'secondary'; ?>">Hoje</a>
                <a href="<?= URLROOT; ?>/cliente/relatorios?periodo=semana" class="btn btn-<?= ($periodo_selecionado == 'semana') ? 'primary' : 'secondary'; ?>">Esta Semana</a>
                <a href="<?= URLROOT; ?>/cliente/relatorios?periodo=mes" class="btn btn-<?= ($periodo_selecionado == 'mes') ? 'primary' : 'secondary'; ?>">Este Mês</a>
            </div>
            <h5>Período: <strong><?= ucfirst($periodo_selecionado); ?></strong></h5>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <h5 class="card-title">Clientes > 18 com renda acima da média</h5>
                <h1 class="display-4"><?= $total_maiores_18; ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <h5 class="card-title">Clientes por Classe</h5>
                <ul class="list-unstyled">
                    <li>
                        <span class="badge badge-c"><?= $total_classe_c; ?></span>
                        Clientes Classe C
                    </li>
                    <li>
                        <span class="badge badge-b"><?= $total_classe_b; ?></span>
                        Clientes Classe B
                    </li>
                    <li>
                        <span class="badge badge-a"><?= $total_classe_a; ?></span>
                        Clientes Classe A
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<a href="<?= URLROOT; ?>/cliente/index" class="btn btn-secondary mt-3">Voltar para Clientes</a>