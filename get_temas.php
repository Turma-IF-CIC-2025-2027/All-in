<?php

require_once 'db_connect.php';

if(isset($_GET['id_disciplina'])){

    $id_disciplina = intval($_GET['id_disciplina']);

    $sql = "
        SELECT id, nome
        FROM tb_themes
        WHERE disciplina_id = ?
        ORDER BY nome ASC
    ";

    $stmt = $conn->prepare($sql);

    if(!$stmt){
        die("Erro na preparação da query.");
    }

    $stmt->bind_param("i", $id_disciplina);

    $stmt->execute();

    $result = $stmt->get_result();

    $temas = [];

    while($row = $result->fetch_assoc()){
        $temas[] = $row;
    }

    header('Content-Type: application/json');

    echo json_encode($temas);

    $stmt->close();
}
?>
