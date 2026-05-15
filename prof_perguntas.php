<?php

require_once 'session.php';
require_once 'db_connect.php';

include 'header.php';

// Buscar disciplinas
$sql_disc = "
    SELECT id, nome
    FROM tb_disciplines
    ORDER BY nome ASC
";

$result_disc = mysqli_query($conn, $sql_disc);

// Verificar erros
if (!$result_disc) {
    die("Erro ao carregar disciplinas.");
}

?>

<div class="container mt-5 mb-5">

    <div class="card shadow-lg border-0">

        <!-- HEADER -->
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">
                Criar Nova Pergunta
            </h2>
        </div>

        <!-- BODY -->
        <div class="card-body">

            <form
                action="upload_pergunta.php"
                method="POST"
                enctype="multipart/form-data"
            >

                <!-- DISCIPLINA -->
                <div class="mb-4">

                    <label
                        for="id_disciplina"
                        class="form-label fw-bold"
                    >
                        Disciplina
                    </label>

                    <select
                        name="id_disciplina"
                        id="id_disciplina"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Selecionar Disciplina --
                        </option>

                        <?php
                        while($disc = mysqli_fetch_assoc($result_disc)){
                        ?>

                            <option value="<?php echo $disc['id']; ?>">
                                <?php echo $disc['nome']; ?>
                            </option>

                        <?php
                        }
                        ?>

                    </select>

                </div>

                <!-- TEMA -->
                <div class="mb-4">

                    <label
                        for="id_tema"
                        class="form-label fw-bold"
                    >
                        Tema
                    </label>

                    <select
                        name="id_tema"
                        id="id_tema"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Selecionar Tema --
                        </option>

                    </select>

                </div>

                <!-- ENUNCIADO -->
                <div class="mb-4">

                    <label
                        for="perg_texto"
                        class="form-label fw-bold"
                    >
                        Enunciado da Pergunta
                    </label>

                    <textarea
                        name="perg_texto"
                        id="perg_texto"
                        class="form-control"
                        rows="4"
                        placeholder="Escreva aqui a pergunta..."
                        required
                    ></textarea>

                </div>

                <!-- OPÇÕES -->
                <div class="row">

                    <!-- A -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Opção A
                        </label>

                        <input
                            type="text"
                            name="op_a"
                            class="form-control"
                            placeholder="Resposta A"
                            required
                        >

                    </div>

                    <!-- B -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Opção B
                        </label>

                        <input
                            type="text"
                            name="op_b"
                            class="form-control"
                            placeholder="Resposta B"
                            required
                        >

                    </div>

                </div>

                <div class="row">

                    <!-- C -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Opção C
                        </label>

                        <input
                            type="text"
                            name="op_c"
                            class="form-control"
                            placeholder="Resposta C"
                            required
                        >

                    </div>

                    <!-- D -->
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Opção D
                        </label>

                        <input
                            type="text"
                            name="op_d"
                            class="form-control"
                            placeholder="Resposta D"
                            required
                        >

                    </div>

                </div>

                <!-- RESPOSTA CORRETA -->
                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Resposta Correta
                    </label>

                    <div>

                        <div class="form-check form-check-inline">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="resp_correta"
                                value="A"
                                required
                            >

                            <label class="form-check-label">
                                A
                            </label>

                        </div>

                        <div class="form-check form-check-inline">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="resp_correta"
                                value="B"
                            >

                            <label class="form-check-label">
                                B
                            </label>

                        </div>

                        <div class="form-check form-check-inline">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="resp_correta"
                                value="C"
                            >

                            <label class="form-check-label">
                                C
                            </label>

                        </div>

                        <div class="form-check form-check-inline">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="resp_correta"
                                value="D"
                            >

                            <label class="form-check-label">
                                D
                            </label>

                        </div>

                    </div>

                </div>

                <!-- DIFICULDADE -->
                <div class="mb-4">

                    <label
                        for="perg_dificuldade"
                        class="form-label fw-bold"
                    >
                        Dificuldade
                    </label>

                    <select
                        name="perg_dificuldade"
                        id="perg_dificuldade"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Selecionar Dificuldade --
                        </option>

                        <option value="1">
                            1 - Muito Fácil
                        </option>

                        <option value="2">
                            2 - Fácil
                        </option>

                        <option value="3">
                            3 - Médio
                        </option>

                        <option value="4">
                            4 - Difícil
                        </option>

                        <option value="5">
                            5 - Muito Difícil
                        </option>

                    </select>

                </div>

                <!-- IMAGEM -->
                <div class="mb-4">

                    <label
                        for="perg_imagem"
                        class="form-label fw-bold"
                    >
                        Imagem da Pergunta (Opcional)
                    </label>

                    <input
                        type="file"
                        name="perg_imagem"
                        id="perg_imagem"
                        class="form-control"
                        accept="image/*"
                    >

                </div>

                <!-- BOTÃO -->
                <div class="d-grid">

                    <button
                        type="submit"
                        class="btn btn-success btn-lg"
                    >
                        Guardar Pergunta
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- AJAX TEMAS -->
<script>

document
.getElementById('id_disciplina')
.addEventListener('change', function(){

    let idDisciplina = this.value;

    let temaSelect =
        document.getElementById('id_tema');

    temaSelect.innerHTML =
        '<option>A carregar temas...</option>';

    if(idDisciplina !== ''){

        fetch(
            'get_temas.php?id_disciplina='
            + idDisciplina
        )

        .then(response => response.json())

        .then(data => {

            temaSelect.innerHTML =
                '<option value="">-- Selecionar Tema --</option>';

            data.forEach(tema => {

                temaSelect.innerHTML += `
                    <option value="${tema.id}">
                        ${tema.nome}
                    </option>
                `;

            });

        })

        .catch(error => {

            temaSelect.innerHTML =
                '<option value="">Erro ao carregar temas</option>';

        });

    }

    else{

        temaSelect.innerHTML =
            '<option value="">-- Selecione uma disciplina primeiro --</option>';

    }

});

</script>

<?php include 'footer.php'; ?>
