<?php
require 'config.php'; // Inclui o arquivo de configuração do banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Coletar os dados do formulário
  $nome_completo = htmlspecialchars($_POST['nome_completo']);
  $nome_artista = htmlspecialchars($_POST['nome_artista']);
  $documento = htmlspecialchars($_POST['documento']);
  $endereco = htmlspecialchars($_POST['endereco']);
  $cidade = htmlspecialchars($_POST['cidade']);
  $cep = htmlspecialchars($_POST['cep']);
  $telefone = htmlspecialchars($_POST['telefone']);
  $email = htmlspecialchars($_POST['email']);
  $youtube = htmlspecialchars($_POST['youtube']);
  $spotify = htmlspecialchars($_POST['spotify']);

  // Processar o upload da foto do documento
  if (isset($_FILES['foto_documento']) && $_FILES['foto_documento']['error'] == 0) {
    $foto_nome = $_FILES['foto_documento']['name'];
    $foto_temp = $_FILES['foto_documento']['tmp_name'];
    $foto_destino = "uploads/" . basename($foto_nome);

    // Move o arquivo para a pasta de uploads
    if (!move_uploaded_file($foto_temp, $foto_destino)) {
      die("Erro ao mover o arquivo enviado.");
    }
  } else {
    die("Erro no upload do arquivo.");
  }

  // Inserir os dados no banco de dados
  try {
    $sql = "INSERT INTO cadastros (nome_completo, nome_artista, documento, endereco, cidade, cep, telefone, email, youtube, spotify, foto_documento)
            VALUES (:nome_completo, :nome_artista, :documento, :endereco, :cidade, :cep, :telefone, :email, :youtube, :spotify, :foto_documento)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':nome_completo' => $nome_completo,
      ':nome_artista' => $nome_artista,
      ':documento' => $documento,
      ':endereco' => $endereco,
      ':cidade' => $cidade,
      ':cep' => $cep,
      ':telefone' => $telefone,
      ':email' => $email,
      ':youtube' => $youtube,
      ':spotify' => $spotify,
      ':foto_documento' => $foto_destino
    ]);

    echo "Cadastro salvo com sucesso!";
  } catch (PDOException $e) {
    die("Erro ao salvar o cadastro: " . $e->getMessage());
  }
}
?>