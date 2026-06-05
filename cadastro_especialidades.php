<?php
session_start();
require_once("conexao.php");

if(!isset($_SESSION['cod_usuario'])){
    header("Location: login.php");
    exit;
}

// Processamento do CRUD (POST e GET para exclusão)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    if ($acao === 'novo') {
        $nome = $_POST['nome'];
        $sql = "INSERT INTO especialidades (nome) VALUES ('$nome')";
        mysqli_query($conexao_bd, $sql);
    } elseif ($acao === 'editar') {
        $id = (int)$_POST['id'];
        $nome = $_POST['nome'];
        $sql = "UPDATE especialidades SET nome = '$nome' WHERE id = $id";
        mysqli_query($conexao_bd, $sql);
    }
    header("Location: cadastro_especialidades.php");
    exit;
}

if (isset($_GET['acao']) && $_GET['acao'] === 'excluir') {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM especialidades WHERE id = $id";
    mysqli_query($conexao_bd, $sql);
    header("Location: cadastro_especialidades.php");
    exit;
}

// Buscar especialidades cadastradas
$especialidades = [];
$sqlBusca = "SELECT * FROM especialidades ORDER BY nome ASC";
$result = mysqli_query($conexao_bd, $sqlBusca);
while ($row = mysqli_fetch_assoc($result)) {
    $especialidades[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>MediAgenda - Especialidades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --azul-primario: #0d6efd; --azul-escuro: #084298; --azul-claro: #e7f1ff; --cinza-fundo: #f5f7fa; --cinza-borda: #e3e6ea; --texto-escuro: #1f2d3d; --sidebar-larg: 250px; }
        body { background-color: var(--cinza-fundo); font-family: 'Segoe UI', Tahoma, sans-serif; }
        .navbar-topo { background: linear-gradient(90deg, var(--azul-primario) 0%, var(--azul-escuro) 100%); height: 60px; position: fixed; top: 0; left: 0; right: 0; z-index: 1030; color: white;}
        .sidebar { position: fixed; top: 60px; left: 0; width: var(--sidebar-larg); height: calc(100vh - 60px); background: #fff; border-right: 1px solid var(--cinza-borda); padding: 20px 0; }
        .sidebar .nav-link { color: var(--texto-escuro); padding: 12px 20px; display: flex; align-items: center; gap: 12px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.ativo { background: var(--azul-claro); border-left: 3px solid var(--azul-primario); color: var(--azul-escuro); font-weight: 600; }
        .conteudo-principal { margin-top: 60px; margin-left: var(--sidebar-larg); padding: 25px; }
        .card-pagina { background: #fff; border-radius: 12px; padding: 20px 24px; margin-bottom: 20px; border: 1px solid var(--cinza-borda); }
    </style>
</head>
<body>
    <nav class="navbar-topo d-flex align-items-center px-3">
        <h4><i class="fa-solid fa-stethoscope"></i> MediAgenda</h4>
    </nav>
    
    <aside class="sidebar" id="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="principal.php"><i class="fa-solid fa-calendar-days"></i> Calendário</a></li>
            <li class="nav-item"><a class="nav-link" href="cadastro_agendas.php"><i class="fa-solid fa-calendar-plus"></i> Agendamentos</a></li>
            <li class="nav-item"><a class="nav-link" href="cadastro_medicos.php"><i class="fa-solid fa-user-doctor"></i> Cadastro de Médicos</a></li>
            <li class="nav-item"><a class="nav-link ativo" href="cadastro_especialidades.php"><i class="fa-solid fa-list-check"></i> Cadastro de Especialidades</a></li>
        </ul>
    </aside>

    <main class="conteudo-principal">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="fa-solid fa-list-check"></i> Especialidades</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalFormEspecialidade" onclick="prepararNovo()"><i class="fa-solid fa-plus"></i> Nova Especialidade</button>
        </div>
        
        <div class="card-pagina">
            <table class="table table-hover">
                <thead style="background: var(--azul-claro);">
                    <tr><th>ID</th><th>Nome da Especialidade</th><th class="text-center">Ações</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($especialidades as $esp): ?>
                    <tr>
                        <td><?= $esp['id'] ?></td>
                        <td><?= htmlspecialchars($esp['nome']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" onclick="prepararEdicao(<?= $esp['id'] ?>, '<?= htmlspecialchars($esp['nome'], ENT_QUOTES) ?>')" data-bs-toggle="modal" data-bs-target="#modalFormEspecialidade"><i class="fa-solid fa-pen"></i></button>
                            <a href="cadastro_especialidades.php?acao=excluir&id=<?= $esp['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir especialidade?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div class="modal fade" id="modalFormEspecialidade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitulo">Nova Especialidade</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="cadastro_especialidades.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="acao" id="formAcao" value="novo">
                        <input type="hidden" name="id" id="formId" value="">
                        <label>Nome da Especialidade</label>
                        <input type="text" class="form-control" name="nome" id="formNome" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function prepararNovo() {
            document.getElementById('formAcao').value = 'novo';
            document.getElementById('formId').value = '';
            document.getElementById('formNome').value = '';
            document.getElementById('modalTitulo').innerText = 'Nova Especialidade';
        }
        function prepararEdicao(id, nome) {
            document.getElementById('formAcao').value = 'editar';
            document.getElementById('formId').value = id;
            document.getElementById('formNome').value = nome;
            document.getElementById('modalTitulo').innerText = 'Editar Especialidade';
        }
    </script>
</body>
</html><?php
session_start();
require_once("conexao.php");

if(!isset($_SESSION['cod_usuario'])){
    header("Location: login.php");
    exit;
}

// Processamento do CRUD (POST e GET para exclusão)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    if ($acao === 'novo') {
        $nome = $_POST['nome'];
        $sql = "INSERT INTO especialidades (nome) VALUES ('$nome')";
        mysqli_query($conexao_bd, $sql);
    } elseif ($acao === 'editar') {
        $id = (int)$_POST['id'];
        $nome = $_POST['nome'];
        $sql = "UPDATE especialidades SET nome = '$nome' WHERE id = $id";
        mysqli_query($conexao_bd, $sql);
    }
    header("Location: cadastro_especialidades.php");
    exit;
}

if (isset($_GET['acao']) && $_GET['acao'] === 'excluir') {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM especialidades WHERE id = $id";
    mysqli_query($conexao_bd, $sql);
    header("Location: cadastro_especialidades.php");
    exit;
}

// Buscar especialidades cadastradas
$especialidades = [];
$sqlBusca = "SELECT * FROM especialidades ORDER BY nome ASC";
$result = mysqli_query($conexao_bd, $sqlBusca);
while ($row = mysqli_fetch_assoc($result)) {
    $especialidades[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>MediAgenda - Especialidades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --azul-primario: #0d6efd; --azul-escuro: #084298; --azul-claro: #e7f1ff; --cinza-fundo: #f5f7fa; --cinza-borda: #e3e6ea; --texto-escuro: #1f2d3d; --sidebar-larg: 250px; }
        body { background-color: var(--cinza-fundo); font-family: 'Segoe UI', Tahoma, sans-serif; }
        .navbar-topo { background: linear-gradient(90deg, var(--azul-primario) 0%, var(--azul-escuro) 100%); height: 60px; position: fixed; top: 0; left: 0; right: 0; z-index: 1030; color: white;}
        .sidebar { position: fixed; top: 60px; left: 0; width: var(--sidebar-larg); height: calc(100vh - 60px); background: #fff; border-right: 1px solid var(--cinza-borda); padding: 20px 0; }
        .sidebar .nav-link { color: var(--texto-escuro); padding: 12px 20px; display: flex; align-items: center; gap: 12px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.ativo { background: var(--azul-claro); border-left: 3px solid var(--azul-primario); color: var(--azul-escuro); font-weight: 600; }
        .conteudo-principal { margin-top: 60px; margin-left: var(--sidebar-larg); padding: 25px; }
        .card-pagina { background: #fff; border-radius: 12px; padding: 20px 24px; margin-bottom: 20px; border: 1px solid var(--cinza-borda); }
    </style>
</head>
<body>
    <nav class="navbar-topo d-flex align-items-center px-3">
        <h4><i class="fa-solid fa-stethoscope"></i> MediAgenda</h4>
    </nav>
    
    <aside class="sidebar" id="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="principal.php"><i class="fa-solid fa-calendar-days"></i> Calendário</a></li>
            <li class="nav-item"><a class="nav-link" href="cadastro_agendas.php"><i class="fa-solid fa-calendar-plus"></i> Agendamentos</a></li>
            <li class="nav-item"><a class="nav-link" href="cadastro_medicos.php"><i class="fa-solid fa-user-doctor"></i> Cadastro de Médicos</a></li>
            <li class="nav-item"><a class="nav-link ativo" href="cadastro_especialidades.php"><i class="fa-solid fa-list-check"></i> Cadastro de Especialidades</a></li>
        </ul>
    </aside>

    <main class="conteudo-principal">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="fa-solid fa-list-check"></i> Especialidades</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalFormEspecialidade" onclick="prepararNovo()"><i class="fa-solid fa-plus"></i> Nova Especialidade</button>
        </div>
        
        <div class="card-pagina">
            <table class="table table-hover">
                <thead style="background: var(--azul-claro);">
                    <tr><th>ID</th><th>Nome da Especialidade</th><th class="text-center">Ações</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($especialidades as $esp): ?>
                    <tr>
                        <td><?= $esp['id'] ?></td>
                        <td><?= htmlspecialchars($esp['nome']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" onclick="prepararEdicao(<?= $esp['id'] ?>, '<?= htmlspecialchars($esp['nome'], ENT_QUOTES) ?>')" data-bs-toggle="modal" data-bs-target="#modalFormEspecialidade"><i class="fa-solid fa-pen"></i></button>
                            <a href="cadastro_especialidades.php?acao=excluir&id=<?= $esp['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir especialidade?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div class="modal fade" id="modalFormEspecialidade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitulo">Nova Especialidade</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="cadastro_especialidades.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="acao" id="formAcao" value="novo">
                        <input type="hidden" name="id" id="formId" value="">
                        <label>Nome da Especialidade</label>
                        <input type="text" class="form-control" name="nome" id="formNome" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function prepararNovo() {
            document.getElementById('formAcao').value = 'novo';
            document.getElementById('formId').value = '';
            document.getElementById('formNome').value = '';
            document.getElementById('modalTitulo').innerText = 'Nova Especialidade';
        }
        function prepararEdicao(id, nome) {
            document.getElementById('formAcao').value = 'editar';
            document.getElementById('formId').value = id;
            document.getElementById('formNome').value = nome;
            document.getElementById('modalTitulo').innerText = 'Editar Especialidade';
        }
    </script>
</body>
</html>