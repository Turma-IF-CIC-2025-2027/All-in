<?php
function processarImagemUpload(): ?string {

    if (!isset($_FILES['perg_imagem']) || $_FILES['perg_imagem']['error'] === UPLOAD_ERR_NO_FILE) return null;

    $file = $_FILES['perg_imagem'];
    $upload_dir = './uploads/';
    $max_size = 2097152;
    $allowed_exts = ['jpg', 'jpeg', 'png'];

    if ($file['error'] !== UPLOAD_ERR_OK) die("Erro no upload. Código: {$file['error']}");

    if ($file['size'] > $max_size) die("Ficheiro muito grande! Máximo 2MB.");

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed_exts, true)) die("Formato inválido. Use JPG, JPEG ou PNG.");

    $new_name = time() . '_' . basename($file['name']);
    $image_path = $upload_dir . $new_name;

    if (!move_uploaded_file($file['tmp_name'], $image_path)) die("Erro ao mover o ficheiro para {$upload_dir}.");

    return $image_path;
}
?>