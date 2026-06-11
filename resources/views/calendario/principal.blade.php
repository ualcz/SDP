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
        let calendar;

        document.addEventListener('DOMContentLoaded', function() {

            calendar = new FullCalendar.Calendar(
                document.getElementById('calendar'), {
                    locale: 'pt-br',

                    initialView: 'dayGridMonth',

                    editable: true,

                    selectable: true,

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
                        novoEvento(info.dateStr);
                    },

                    eventClick: function(info) {
                        editarEvento(info.event);
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

        descricao: info.event.extendedProps.descricao

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

        function novoEvento(data) {

            document.getElementById('tituloModal').innerText = 'Novo Evento';

            document.getElementById('formEvento').reset();

            document.getElementById('evento_id').value = '';

            document.getElementById('data_inicio').value = data;

            document.getElementById('btnExcluir').style.display = 'none';

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

            document.getElementById('btnExcluir').style.display = 'inline';

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

                        descricao: document.getElementById('descricao').value
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
    </script>

</body>

</html>