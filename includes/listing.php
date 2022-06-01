<?php

$msg = '';

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'success':
            $msg = '<div class="alert alert-success mt-5">Ação executada com sucesso!</div>';
            break;
        case 'error':
            $msg = '<div class="alert alert-danger mt-5">ERROR: Ação não executada!</div>';
            break;
    }
}

$results = '';
foreach ($vagas as $vaga) {
    $results .= '<tr class="text-center">
        <td>' . $vaga->id . '</td>
        <td>' . $vaga->titulo . '</td>
        <td>' . $vaga->descricao . '</td>
        <td>' . ($vaga->ativo == 's' ? 'Ativo' : 'Inativo') . '</td>
        <td>' . date('d/m/Y à\s H:i:s', strtotime($vaga->dia)) . '</td>
        <td>
            <a href="update.php?id=' . $vaga->id . '" style=text-decoration:none>
                <button type="button" class="btn btn-primary">Editar</button>
            </a>
            <a href="delete.php?id=' . $vaga->id . '" style=text-decoration:none>
                <button type="button" class="btn btn-danger">Excluir</button>
            </a>
        </td>
    </tr>';
}

$results = strlen($results) ? $results :
    '<tr>
        <td colspan="6" class="text-center">Nenhuma vaga encontrada!</td>
    </tr>'
?>

<main>
    <section>
        <a href="register.php"><button class="btn btn-success">Nova Vaga</button></a>
    </section>

    <section>
        <table class="table bg-light mt-5">
            <thead>
                <tr class="text-center">
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?= $results ?>
            </tbody>
        </table>

        <?= $msg ?>
    </section>
</main>