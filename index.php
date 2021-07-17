<?php
include_once('conexao.php');
include_once('Produto.php');
session_start();
$edicao = false;
$edicao_id;
if (!isset($banco)) {
    $banco = new Banco();
    error_log(print_r("abriu conexão de banco", TRUE));
}



if (isset($_POST['acao']) && $_POST['acao'] != '') {
    error_log($_POST['acao']);
    processarAcao($_POST['acao']);
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body class="page">
    <h1 style="color: white;text-align:center;">produto</h1>
    <div class="container">

        <form class="formulario" action="" method="post">
            <?php
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
            ?>
            <div class="campo">
                <label for="nome">nome</label>
                <input type="text" name="nome" id="nome" value="<?php echo (getvalor($edicao_id, 1)); ?>">
            </div>
            <div class="campo">
                <label for="id">id</label>
                <input type="number" name="id" id="id" value="<?php echo (getvalor($edicao_id, 0)); ?>">
            </div>
            <?php
            if (!$edicao) {
                echo " <input type='submit' name='acao' value='Salvar'>";
            } else {
                echo " <input type='submit' name='acao' value='Atualizar'>";
                echo " <input type='submit' name='acao' value='cancelar'>";
            } ?>


        </form>
    </div>
    <div class="container">
        <?php
        global $banco;
        if (isset($banco)) {
            $res = $banco->mysqli->query("SELECT * FROM produtos");
            if ($res) {

                $produtos = $res->fetch_all(MYSQLI_ASSOC);
                mysqli_free_result($res);
                if (count($produtos) > 0) {
                    echo "<table class=\"resultados\">";
                    echo "<tr> <th>nome</th><th>id</th><th>x</th><th>x</th></tr>";
                    foreach ($produtos as $produto) {
                        echo "<tr><form method='POST'>";
                        echo " <input type='hidden' name='editado' value='" . $produto['id'] . "' >";
                        echo ("<td>" . $produto['nome'] . "</td><td> " . $produto['id'] . "</td>");
                        echo "<td><input type='submit' name='acao' value='Apagar'>";
                        echo "<td><input type='submit' name='acao' value='Editar'>";
                        echo ("</form></tr>");
                    }
                    echo "</table>";
                } else {
                    echo "<p class=\"resultados\">sem registros<p>";
                }
            }
        }
        ?>
    </div>
</body>

</html>
<?php
if (isset($banco)) {
    $banco->mysqli->close();
    unset($banco);
    error_log(print_r("fechou conexão de banco", TRUE));
}
function processarAcao($acao)
{
    switch ($acao) {
        case 'Atualizar':
            if (verificaForm()) atualizar();
            break;
        case 'Salvar':
            if (verificaForm()) inserir();
            break;
        case 'Editar':
            error_log($_POST['editado']);
            global $edicao_id, $edicao;
            $edicao = true;
            $edicao_id = $_POST['editado'];
            $_SESSION['id_editar'] = $_POST['editado'];
            break;
        case 'Apagar':
            apagar();
        case 'Cancelar':
            break;
    }
}
function inserir()
{
    global $banco;
    if (isset($_POST['nome']) && isset($_POST['id'])) {
        $prod = new Produto($_POST['id'], $_POST['nome']);
        $stm = $banco->mysqli->prepare("INSERT INTO produtos (nome,id) VALUES( ?,? ) ");
        if ($stm != false) {
            $stm->bind_param("si", $_POST['nome'], $_POST['id']);
            $stm->execute();
            if ($stm->error != '') {
                $_SESSION['error'] = $stm->error;
            }
        } else {
            $_SESSION['error'] = "erro de banco";
        }
    }
}
function atualizar()
{
    global $banco, $edicao_id;
    if (isset($_POST['nome']) && isset($_POST['id'])) {
        $prod = new Produto($_POST['id'], $_POST['nome']);
        $stm = $banco->mysqli->prepare("UPDATE produtos set nome=?,id=? WHERE id = ?");
        $stm->bind_param("sii", $_POST['nome'], $_POST['id'], $_SESSION['id_editar']);
        $stm->execute();
        if ($stm->error != '') {
            $_SESSION['error'] = $stm->error;
        }
    }
    $edicao = false;
}

function getvalor($id, $col)
{
    global $banco, $edicao;
    if ($edicao) {
        return $banco->buscaCol($id, $col);
    } else return "";
}
function apagar()
{
    global $banco;
    $banco->deletar($_POST['editado']);
}
function verificaForm()
{
    return ((isset($_POST['nome']) && $_POST['nome'] != "") &&
        (isset($_POST['id']) && $_POST['id'] != ""));
}
