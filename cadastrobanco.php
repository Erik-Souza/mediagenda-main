<?php
    require_once("conexao.php");// importar o conexao.php para esta página
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    //zerar as sessões:
    session_start();
    $_SESSION["cod_usuario"] = "";
    
    //conferir se o usuário está preenchido
    //conferir se a senha está preenchida
    if(strlen($usuario) > 0 && strlen($senha) > 0){
        
        // 1. Prepara a query
        $sql = "SELECT * FROM usuario WHERE username = '$usuario'";
        
        // 2. EXECUTAR A QUERY PRIMEIRO (Cria a variável $result)
        $result = mysqli_query($conexao_bd, $sql); 

        // 3. --- CÓDIGO DE DIAGNÓSTICO (RAIO-X) ---
        if (!$result) {
            die("🚨 Erro do MySQL: " . mysqli_error($conexao_bd));
        }
        echo "<h3>Raio-X do Banco:</h3>";
        echo "🔍 Query gerada: " . $sql . "<br>";
        echo "📊 Linhas encontradas: " . mysqli_num_rows($result) . "<hr>";
        // ---------------------------------------
        
        // 4. Continua com a leitura dos dados
        if($consulta = mysqli_fetch_assoc($result)){ 
            $cod_usuario = $consulta['cod_usuario'];
            $nome        = $consulta['nome'];
            $password    = $consulta['pass'];
            
            if(
                strtoupper(ltrim(rtrim($senha))) == 
                strtoupper(ltrim(rtrim($password)))
            ){
                //usuário autenticado!
                $_SESSION["cod_usuario"] = $cod_usuario;
                header("location:principal.php");
            }else{
                //usuário não autenticado
                header("location:index.php");
            }
        }else{
            echo "‼ Não achei o usuário!!!";
        }
    }else{
        echo "Não achei o usuário!!!";
    }
?>