<div id="modalEvento" class="modal">

    <div class="modal-content">

        <h2 id="tituloModal">Novo Evento</h2>

        <form id="formEvento">

            <input type="hidden" id="evento_id">

            <label>Título</label>

            <input type="text" id="titulo" required>

            <label>Tipo</label>

            <select id="tipo" required>
                <option value="prova">Prova</option>
                <option value="trabalho">Trabalho</option>
                <option value="seminario">Seminário</option>
                <option value="reuniao">Reunião</option>
                <option value="outro">Outro</option>
            </select>

            <label>Data</label>

            <input type="date" id="data_inicio" required>

            <label>Hora inicial</label>

            <input type="time" id="hora_inicio">

            <label>Hora final</label>

            <input type="time" id="hora_fim">
           <label>Disciplina</label>

<select id="disciplina_professor_id" required>

    <option value="56">
        Teste
    </option>

</select>

<label>Descrição</label>

<textarea id="descricao"></textarea>

            <div class="botoes-modal">

                <button type="button" id="btnExcluir">
                    Excluir
                </button>

                <button type="button" onclick="fecharModal()">
                    Cancelar
                </button>

                <button type="submit" id="btnSalvar">
                Salvar
                </button>

            </div>

        </form>

    </div>

</div>