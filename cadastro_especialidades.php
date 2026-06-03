<?php
include 'conexao.php';

// -----------------------------------------
// LÓGICA DE EXCLUSÃO (DELETE)
// -----------------------------------------
if (isset($_GET['deletar'])) {
    $id_para_deletar = $_GET['deletar'];
    $sql_deletar = "DELETE FROM especialidades WHERE id = $id_para_deletar";
    if (mysqli_query($conexao_bd, $sql_deletar)) {
        echo "<script>alert('Especialidade excluída com sucesso!'); window.location.href='cadastro_especialidades.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir: " . mysqli_error($conexao_bd) . "');</script>";
    }
}

// -----------------------------------------
// PREPARAÇÃO PARA A EDIÇÃO (Carregar dados no form)
// -----------------------------------------
$id_edicao = "";
$nome_edicao = "";
$modo_edicao = false;

if (isset($_GET['editar'])) {
    $modo_edicao = true;
    $id_edicao = $_GET['editar'];
    $sql_busca = "SELECT nome FROM especialidades WHERE id = $id_edicao";
    $resultado_busca = mysqli_query($conexao_bd, $sql_busca);
    if ($linha_busca = mysqli_fetch_assoc($resultado_busca)) {
        $nome_edicao = $linha_busca['nome'];
    }
}

// -----------------------------------------
// LÓGICA DE CADASTRO (CREATE) E EDIÇÃO (UPDATE)
// -----------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salvar'])) {
    $nome_especialidade = $_POST['nome_especialidade'];
    
    try {
        if (isset($_POST['id_especialidade']) && !empty($_POST['id_especialidade'])) {
            // É UMA EDIÇÃO (UPDATE)
            $id_atualizar = $_POST['id_especialidade'];
            $sql_salvar = "UPDATE especialidades SET nome = '$nome_especialidade' WHERE id = $id_atualizar";
            $msg_sucesso = "Especialidade atualizada com sucesso!";
        } else {
            // É UM CADASTRO NOVO (INSERT)
            $sql_salvar = "INSERT INTO especialidades (nome) VALUES ('$nome_especialidade')";
            $msg_sucesso = "Especialidade cadastrada com sucesso!";
        }

        if (mysqli_query($conexao_bd, $sql_salvar)) {
            echo "<script>alert('$msg_sucesso'); window.location.href='cadastro_especialidades.php';</script>";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $mensagem = "<div class='alert alert-warning'>Atenção: Esta especialidade já está cadastrada no sistema!</div>";
        } else {
            $mensagem = "<div class='alert alert-danger'>Erro no banco de dados: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Especialidades - MediAgenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2><?php echo $modo_edicao ? "Editar Especialidade" : "Gerenciar Especialidades"; ?></h2>
    
    <div class="mb-3">
        <a href="principal.php" class="btn btn-secondary btn-sm">Voltar ao Menu</a>
    </div>

    <?php if(isset($mensagem)) echo $mensagem; ?>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="id_especialidade" value="<?php echo $id_edicao; ?>">
                
                <div class="mb-3">
                    <label for="nome_especialidade" class="form-label">Nome da Especialidade</label>
                    <input type="text" class="form-control" id="nome_especialidade" name="nome_especialidade" value="<?php echo $nome_edicao; ?>" required>
                </div>
                <button type="submit" name="salvar" class="btn btn-primary">
                    <?php echo $modo_edicao ? "Atualizar Especialidade" : "Salvar Especialidade"; ?>
                </button>
                <?php if($modo_edicao): ?>
                    <a href="cadastro_especialidades.php" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <h3>Especialidades Cadastradas</h3>
    <table class="table table-striped border">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_listar = "SELECT * FROM especialidades";
            $resultado = mysqli_query($conexao_bd, $sql_listar);

            if (mysqli_num_rows($resultado) > 0) {
                while($linha = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td>" . $linha['id'] . "</td>";
                    echo "<td>" . $linha['nome'] . "</td>";
                    echo "<td>
                        <a href='cadastro_especialidades.php?editar=" . $linha['id'] . "' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='cadastro_especialidades.php?deletar=" . $linha['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir esta especialidade?\")'>Excluir</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3' class='text-center'>Nenhuma especialidade cadastrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>