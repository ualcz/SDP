<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Calendário Acadêmico</title>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>

    <style>
        body {
            margin: 0;
            padding: 40px;
            font-family: Arial, sans-serif;
            background: #F8FAFC;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
        }

        h1 {
            color: #334155;
        }

        #calendar {
            min-height: 700px;
        }

        /* Modal */
        .modal {
    display: none;

    position: fixed;

    inset: 0;

    background: rgba(0, 0, 0, .4);

    justify-content: center;

    align-items: center;

    z-index: 9999;
}

.modal-content {
    background: white;

    width: 450px;

    padding: 25px;

    border-radius: 20px;

    max-height: 90vh;

    overflow-y: auto;

    position: relative;
}

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        textarea {
            height: 90px;
        }

        .botoes-modal {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        #btnExcluir {
            background: #FCA5A5;
        }
    </style>
</head>

<body>

    <div class="container">

        <h1>Calendário Acadêmico</h1>

        <div id="calendar"></div>

    </div>

    @include('calendario.modal-evento')
    <script>

const PODE_GERENCIAR =
    @json($podeGerenciarEventos);

const PODE_EXCLUIR =
    @json($podeExcluirEventos);

const OFERTAS =
    @json($ofertas);

</script>

    <script>
        let calendar;

        document.addEventListener('DOMContentLoaded', function() {
        carregarOfertas();
            calendar = new FullCalendar.Calendar(
                document.getElementById('calendar'), {
                    locale: 'pt-br',

                    initialView: 'dayGridMonth',

                    editable: PODE_GERENCIAR,

                    selectable: PODE_GERENCIAR,

                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listMonth'
                    },

                    buttonText: {
                        today: 'Hoje',
                        month: 'Mês',
                        week: 'Semana',
                        list: 'Lista'
                    },

                    events: '/eventos',

                    dateClick: function(info) {

                     if (!PODE_GERENCIAR) {
                            return;
                        }

                    novoEvento(info.dateStr);
                    },
                    eventClick: function(info) {
        console.log('EVENTO CLICADOo');
        console.log(info.event);
        console.log(info.event.extendedProps);

        if (PODE_GERENCIAR) {

            editarEvento(info.event);

        } else {

            abrirVisualizacao(info.event);
    }
},

                    eventDrop: async function(info){

await fetch('/eventos/' + info.event.id, {

    method: 'PUT',

    headers: {
        'Content-Type': 'application/json',

        'X-CSRF-TOKEN':
            document
            .querySelector('meta[name="csrf-token"]')
            .content
    },

    body: JSON.stringify({

    titulo: info.event.title,

    tipo: info.event.extendedProps.tipo,

    data_inicio: info.event.startStr,

    hora_inicio: info.event.extendedProps.hora_inicio,

    hora_fim: info.event.extendedProps.hora_fim,

    descricao: info.event.extendedProps.descricao,

    disciplina_professor_id:
        info.event.extendedProps
            .disciplina_professor_id
})

});

}
                }
            );

            console.log("Calendário iniciado");

            calendar.render();

        });

        function abrirModal() {
            document.getElementById('modalEvento').style.display = 'flex';
        }

        function fecharModal() {
            document.getElementById('modalEvento').style.display = 'none';
        }
        function carregarOfertas() {
            let select =
            document.getElementById(
            'disciplina_professor_id'
        );

    select.innerHTML = '';

    OFERTAS.forEach(function(oferta){

        let option =
            document.createElement('option');

        option.value = oferta.id;

        option.text =
            oferta.disciplina.nome;

        select.appendChild(option);
    });
}

        function novoEvento(data) {

            document.getElementById('tituloModal').innerText = 'Novo Evento';

            document.getElementById('formEvento').reset();

            document.getElementById('evento_id').value = '';

            document.getElementById('data_inicio').value = data;

            document.getElementById('btnExcluir').style.display = 'none';
            document.getElementById('btnSalvar').style.display = PODE_GERENCIAR ? 'inline' : 'none';

            abrirModal();
        }

        function editarEvento(evento) {

            document.getElementById('tituloModal').innerText = 'Editar Evento';

            document.getElementById('evento_id').value = evento.id;

            document.getElementById('titulo').value = evento.title;

            document.getElementById('tipo').value = evento.extendedProps.tipo;

            document.getElementById('data_inicio').value = evento.startStr;

            document.getElementById('hora_inicio').value =
                evento.extendedProps.hora_inicio ?? '';

            document.getElementById('hora_fim').value =
                evento.extendedProps.hora_fim ?? '';

            document.getElementById('descricao').value =
                evento.extendedProps.descricao ?? '';

            document.getElementById('disciplina_professor_id').value =
                evento.extendedProps
                 .disciplina_professor_id;

            document.getElementById('btnSalvar')
            .style.display = PODE_GERENCIAR ? 'inline' : 'none';

            document.getElementById('btnExcluir')
    .style.display =
        PODE_EXCLUIR
            ? 'inline'
            : 'none';

            abrirModal();
        }

        document.getElementById('formEvento')
            .addEventListener('submit', async function(e) {

                e.preventDefault();

                let id = document.getElementById('evento_id').value;

                let url = '/eventos';

                let metodo = 'POST';

                if (id) {
                    url = '/eventos/' + id;
                    metodo = 'PUT';
                }

                await fetch(url, {
                    method: metodo,

                    headers: {
                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .content
                    },

    body: JSON.stringify({

        titulo: document.getElementById('titulo').value,

        tipo: document.getElementById('tipo').value,

        data_inicio: document.getElementById('data_inicio').value,

        hora_inicio: document.getElementById('hora_inicio').value,

        hora_fim: document.getElementById('hora_fim').value,

        descricao: document.getElementById('descricao').value,

        disciplina_professor_id:
            document.getElementById(
                'disciplina_professor_id'
            ).value
})
                });

                fecharModal();

                calendar.refetchEvents();

            });

        document.getElementById('btnExcluir')
            .addEventListener('click', async function() {

                if (!confirm('Excluir evento?')) {
                    return;
                }

                let id = document.getElementById('evento_id').value;

                await fetch('/eventos/' + id, {

                    method: 'DELETE',

                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .content
                    }

                });

                fecharModal();

                calendar.refetchEvents();

            });
        function abrirVisualizacao(evento)
        {
            document.getElementById(
                'viewTitulo'
            ).innerText = evento.title;

            document.getElementById(
                'viewDisciplina'
            ).innerText =
                evento.extendedProps.disciplina ?? '-';

            document.getElementById(
                'viewProfessor'
            ).innerText =
                evento.extendedProps.professor ?? '-';

            document.getElementById(
                'viewData'
            ).innerText =
                evento.extendedProps.data_inicio ?? '-';

            document.getElementById(
                'viewHorario'
            ).innerText =
                (evento.extendedProps.hora_inicio ?? '')
                + ' - ' +
                (evento.extendedProps.hora_fim ?? '');

            document.getElementById(
                'viewTipo'
            ).innerText =
                evento.extendedProps.tipo ?? '-';

            document.getElementById(
                'viewDescricao'
            ).innerText =
                evento.extendedProps.descricao ?? '-';

            document.getElementById(
                'modalVisualizar'
            ).style.display = 'flex';
        }

        function fecharVisualizacao()
        {
            document.getElementById(
                'modalVisualizar'
            ).style.display = 'none';
        }
        
    </script>

</body>

</html>