<?php

  session_start();

  include_once("connection.php");
  include_once("url.php");

  $data = $_POST;
  // MODIFICAÇÔES NO BANCO
  if(!empty($data)) {
    // Criar contato
    if($data["type"] === "create") {

      $name = $data["name"];
      $phone = $data["phone"];
      $observations = $data["observations"];

      $query = "INSERT INTO contacts (name, phone, observations) VALUES (:name, :phone, :observations)";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":name", $name);
      $stmt->bindParam(":phone", $phone);
      $stmt->bindParam(":observations", $observations);

      try {

        $stmt->execute();
        $_SESSION["msg"] = "Contato criado com sucesso";
    
      } catch(PDOException $e) {
        // erro na conexao
        $error = $e->getMessage();
        echo "Erro: $error";
      }

    } else if($data["type"] === "edit") {

      $name = $data["name"];
      $phone = $data["phone"];
      $observations = $data["observations"];
      $id = $data["id"];

      echo "é";

      $query = "UPDATE contacts
                SET name = :name, phone = :phone, observations = :observations
                where id = :id";
      $stmt = $conn->prepare($query);

      $stmt->bindParam(":name", $name);
      $stmt->bindParam(":phone", $phone);
      $stmt->bindParam(":observations", $observations);
      $stmt->bindParam(":id", $id);

      try {

        $stmt->execute();
        $_SESSION["msg"] = "Contato editado com sucesso";
    
      } catch(PDOException $e) {
        // erro na conexao
        $error = $e->getMessage();
        echo "Erro: $error";
      }

    } else if($data["type"] == "delete") {

      $id = $data["id"];

      $query = "DELETE FROM contacts where id = :id";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":id", $id);


      try {

        $stmt->execute();
        $_SESSION["msg"] = "Contato deletado com sucesso";
    
      } catch(PDOException $e) {
        // erro na conexao
        $error = $e->getMessage();
        echo "Erro: $error";
      }

    }


      // Redirect HOME
      header("Location:" . $BASE_URL . "../index.php");
    // SELEÇÃO DE DADOS
  } else {
    
    $id;

    if(!empty($_GET)) {
      $id = $_GET['id'];
    }
    // Retorna o dado de um contato
    if(!empty($id)) {

      $query = "SELECT * FROM contacts WHERE id = :id";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":id", $id);

      $stmt->execute();

      $contact = $stmt->fetch();

      if ($contact == '') {
        $_SESSION["msg"] = "Este contato nao existe";
        echo "<script>alert('1')</script>";
      }
      
    } else {

          // Retorna todos os contatos
          $contacts = [];
          $query = "SELECT * FROM contacts";

          $stmt = $conn->prepare($query);
        
          $stmt->execute();
        
          $contacts = $stmt->fetchAll();
      }

  }

  // FECHAR CONEXAO

  $conn = null;