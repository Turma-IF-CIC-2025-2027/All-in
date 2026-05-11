<?php

require_once 'db_connect.php';

if(isset($_GET['id_disciplina'])){

    $id_disciplina = intval($_GET['id_disciplina']);

    $sql = "
        SELECT id, nome
        FROM tb_themes
        WHERE disciplina_id = $id_disciplina
        ORDER BY nome ASC
    ";

    $result = mysqli_query($conn, $sql);

    $temas = [];

    while($row = mysqli_fetch_assoc($result)){
        $temas[] = $row;
    }

    echo json_encode($temas);
}
?>