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
    position: relative;
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

.lista-eventos-semana {
    margin-top: 15px;
    padding: 10px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
}

.lista-eventos-semana h2 {
    margin-bottom: 10px;
    font-size: 17px;
    text-align: center;
}

.evento-semana {
    padding: 8px 10px;
    margin-bottom: 6px;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 3px solid #2c7be5;
}

.evento-semana:last-child {
    margin-bottom: 0;
}

.evento-titulo {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 6px;
}

.evento-informacoes {
    display: flex;
    flex-wrap: wrap;
    gap: 5px 12px;
}

.evento-info {
    display: flex;
    flex-direction: column;
    gap: 1px;
    min-width: 80px;
}

.evento-label {
    font-size: 9px;
    font-weight: 600;
    text-transform: uppercase;
    color: #777;
}

.navegacao-semana {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 12px;
}

.navegacao-semana button {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
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
.navbar {
    max-width: 1200px;
    margin: 0 auto 20px auto;

    background: white;

    padding: 12px 20px;

    border-radius: 15px;

    box-shadow: 0 4px 20px rgba(0, 0, 0, .08);

    display: flex;

    align-items: center;

    justify-content: space-between;
}

.navbar-esquerda {
    display: flex;

    align-items: center;

    gap: 5px;
}

.nav-botao {
    text-decoration: none;

    color: #475569;

    padding: 10px 15px;

    border-radius: 10px;

    font-size: 14px;

    transition: .2s;
}

.nav-botao:hover {
    background: #F1F5F9;

    color: #1E293B;
}

.nav-botao.ativo {
    background: #E2E8F0;

    color: #1E293B;

    font-weight: bold;
}

.navbar-direita {
    display: flex;

    align-items: center;
}

.usuario-logado {
    display: flex;

    flex-direction: column;

    align-items: flex-end;

    color: #334155;
}

.usuario-logado strong {
    font-size: 15px;
}

.usuario-logado span {
    margin-top: 3px;

    font-size: 13px;

    color: #64748B;
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
            background: #eba4a4;
        }
    </style>
</head>

<body>

@include('layouts.navegacao')

<div class="container">

        <h1>Calendário Acadêmico</h1>

@if ($ehProfessor)

    <div style="margin-bottom: 20px;">

        <label for="selectTurma">
            <strong>Selecione a turma:</strong>
        </label>

        <select id="selectTurma">

            <option value="">
                Selecione uma turma
            </option>

            @foreach ($turmas as $turma)

                <option value="{{ $turma }}">
                    {{ $turma }}
                </option>

            @endforeach

        </select>

    </div>

@endif

<div id="calendar"></div>

<div id="listaEventosSemana" class="lista-eventos-semana">

    <h2 id="tituloSemanaEventos">Eventos da semana</h2>

    <div id="eventosSemana">
        <p>Nenhum evento disponível.</p>
    </div>

    <div class="navegacao-semana">
        <button type="button" id="btnSemanaAnterior">
            Semana anterior
        </button>

        <button type="button" id="btnProximaSemana">
            Próxima semana
        </button>
    </div>

</div>

</div>

    </div>

    @include('calendario.modal-evento')
    <script>

const PODE_GERENCIAR =
    @json($podeGerenciarEventos);

const PODE_EXCLUIR =
    @json($podeExcluirEventos);

const OFERTAS =
    @json($ofertas);

const EH_PROFESSOR =
    @json($ehProfessor);

const EH_REPRESENTANTE =
    @json($ehRepresentante);

</script>

    <script>
        let calendar;
        let turmaSelecionada = '';

document.addEventListener('DOMContentLoaded', function() {

    if (EH_REPRESENTANTE) {
        carregarProfessores();
    }

    carregarOfertas();

    function atualizarListaEventosSemana() {

const dataAtual = calendar.getDate();

const inicioSemana = new Date(dataAtual);
inicioSemana.setDate(
    dataAtual.getDate() - dataAtual.getDay()
);

const fimSemana = new Date(inicioSemana);
fimSemana.setDate(
    inicioSemana.getDate() + 6
);

const eventos = calendar.getEvents().filter(evento => {

    if (!evento.start) {
        return false;
    }

    const dataEvento = new Date(evento.start);

    return (
        dataEvento >= inicioSemana &&
        dataEvento <= fimSemana
    );
});

const container = document.getElementById('eventosSemana');
const titulo = document.getElementById('tituloSemanaEventos');

const opcoesData = {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
};

titulo.textContent =
    'Eventos de ' +
    inicioSemana.toLocaleDateString('pt-BR', opcoesData) +
    ' a ' +
    fimSemana.toLocaleDateString('pt-BR', opcoesData);

container.innerHTML = '';

if (eventos.length === 0) {

    container.innerHTML =
        '<p>Nenhum evento disponível nesta semana.</p>';

    return;
}

eventos.sort((a, b) => {
    return a.start - b.start;
});

eventos.forEach(evento => {

    const div = document.createElement('div');

    div.classList.add('evento-semana');

    const disciplina =
        evento.extendedProps.disciplina || 'Não informada';

    const professor =
        evento.extendedProps.professor || 'Não informado';

    const data =
        evento.start.toLocaleDateString(
            'pt-BR',
            opcoesData
        );

        div.innerHTML = `
    <div class="evento-titulo">
        ${evento.title}
    </div>

    <div class="evento-informacoes">

        <div class="evento-info">
            <span class="evento-label">Disciplina</span>
            <span>${disciplina}</span>
        </div>

        <div class="evento-info">
            <span class="evento-label">Data</span>
            <span>${data}</span>
        </div>

        <div class="evento-info">
            <span class="evento-label">Professor</span>
            <span>${professor}</span>
        </div>

    </div>
`;
    container.appendChild(div);
});
}

    calendar = new FullCalendar.Calendar(
        document.getElementById('calendar'),
        {

            locale: 'pt-br',

            initialView: 'dayGridMonth',



            /*
            |--------------------------------------------------------------
            | Não permitimos arrastar eventos do professor.
            | A edição será feita pelo modal.
            |--------------------------------------------------------------
            */

            editable:
                PODE_GERENCIAR && !EH_PROFESSOR,

            selectable:
                PODE_GERENCIAR,

            headerToolbar: {

                left: 'prev,next today',

                center: 'title',

                right:
                    'dayGridMonth,timeGridWeek,listMonth'
            },

            buttonText: {

                today: 'Hoje',

                month: 'Mês',

                week: 'Semana',

                list: 'Lista'
            },

            /*
            |--------------------------------------------------------------
            | A URL será montada dinamicamente.
            |--------------------------------------------------------------
            */

            events: function(
                fetchInfo,
                successCallback,
                failureCallback
            ) {

                let url = '/eventos';

                if (
                    EH_PROFESSOR &&
                    turmaSelecionada
                ) {

                    url +=
                        '?turma_codigo=' +
                        encodeURIComponent(
                            turmaSelecionada
                        );
                }

                fetch(url)

                    .then(response =>
                        response.json()
                    )

                    .then(data =>
                        successCallback(data)
                    )

                    .catch(error => {

                        console.error(error);

                        failureCallback(error);
                    });
            },

            eventsSet: function() {
    atualizarListaEventosSemana();
},

            /*
            |--------------------------------------------------------------------------
            | Clique em uma data
            |--------------------------------------------------------------------------
            */

            dateClick: function(info) {

if (!PODE_GERENCIAR) {
    return;
}

/*
|--------------------------------------------------------------
| Professor precisa selecionar turma
|--------------------------------------------------------------
*/

if (
    EH_PROFESSOR &&
    !turmaSelecionada
) {
    alert(
        'Selecione uma turma antes de criar um evento.'
    );

    return;
}

/*
|--------------------------------------------------------------
| Verifica se já existem 3 eventos nesse dia
|--------------------------------------------------------------
*/

const eventosDoDia = calendar.getEvents().filter(evento => {

    return evento.startStr.substring(0, 10) === info.dateStr;

});

if (eventosDoDia.length >= 3) {

    alert(
        'Não é possível criar um evento neste dia, pois esta turma já possui 3 avaliações marcadas para essa data.'
    );

    return;
}

/*
|--------------------------------------------------------------
| Abre o formulário
|--------------------------------------------------------------
*/

novoEvento(info.dateStr);
},


            /*
            |--------------------------------------------------------------------------
            | Clique em evento
            |--------------------------------------------------------------------------
            */

            eventClick: function(info) {

                console.log(
                    'EVENTO CLICADO'
                );

                console.log(
                    info.event
                );

                console.log(
                    info.event.extendedProps
                );


                /*
                |--------------------------------------------------------------
                | Se o usuário puder editar ESTE evento
                |--------------------------------------------------------------
                */

                if (
                    info.event.extendedProps.pode_editar
                ) {

                    editarEvento(
                        info.event
                    );

                } else {

                    abrirVisualizacao(
                        info.event
                    );
                }
            },


            /*
            |--------------------------------------------------------------------------
            | Arrastar evento
            |--------------------------------------------------------------------------
            */

            eventDrop: async function(info) {

                await fetch(
                    '/eventos/' +
                    info.event.id,
                    {

                        method: 'PUT',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .content
                        },

                        body: JSON.stringify({

                            titulo:
                                info.event.title,

                            tipo:
                                info.event.extendedProps.tipo,

                            data_inicio:
                                info.event.startStr,

                            hora_inicio:
                                info.event.extendedProps.hora_inicio,

                            hora_fim:
                                info.event.extendedProps.hora_fim,

                            descricao:
                                info.event.extendedProps.descricao,

                            disciplina_professor_id:
                                info.event.extendedProps
                                    .disciplina_professor_id
                        })
                    }
                );

            }
        }
    );

    document.getElementById('btnProximaSemana')
    .addEventListener('click', function() {

        calendar.next();
        atualizarListaEventosSemana();
    });


document.getElementById('btnSemanaAnterior')
    .addEventListener('click', function() {

        calendar.prev();
        atualizarListaEventosSemana();
    });

    let semanaEventos = 0;

    console.log(
        "Calendário iniciado"
    );

    calendar.render();


    /*
    |--------------------------------------------------------------------------
    | Mudança de turma
    |--------------------------------------------------------------------------
    */

    const selectTurma =
        document.getElementById(
            'selectTurma'
        );

const selectProfessor =
    document.getElementById(
        'professor_id'
    );


if (selectProfessor) {

    selectProfessor.addEventListener(
        'change',
        function() {

            carregarOfertas();

        }
    );
}


    if (selectTurma) {

        selectTurma.addEventListener(
            'change',
            function() {

                turmaSelecionada =
                    this.value;

                /*
                |--------------------------------------------------------------
                | Atualiza as disciplinas disponíveis
                |--------------------------------------------------------------
                */

                carregarOfertas();


                /*
                |--------------------------------------------------------------
                | Recarrega eventos da turma
                |--------------------------------------------------------------
                */

                calendar.refetchEvents();
            }
        );
    }

});

        function abrirModal() {
            document.getElementById('modalEvento').style.display = 'flex';
        }

        function fecharModal() {
            document.getElementById('modalEvento').style.display = 'none';
        }
function carregarOfertas() {

    const selectDisciplina =
        document.getElementById(
            'disciplina_professor_id'
        );

    const selectProfessor =
        document.getElementById(
            'professor_id'
        );


    if (!selectDisciplina) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Limpa o select de disciplinas
    |--------------------------------------------------------------------------
    */

    selectDisciplina.innerHTML = '';


    /*
    |--------------------------------------------------------------------------
    | PROFESSOR
    |--------------------------------------------------------------------------
    */

    if (EH_PROFESSOR) {

        if (!turmaSelecionada) {

            const option =
                document.createElement(
                    'option'
                );

            option.value = '';

            option.text =
                'Selecione uma turma primeiro';

            selectDisciplina.appendChild(
                option
            );

            return;
        }


        /*
        |----------------------------------------------------------------------
        | Somente disciplinas do professor
        | na turma selecionada
        |----------------------------------------------------------------------
        */

        OFERTAS
            .filter(function(oferta) {

                return oferta.turma_codigo
                    === turmaSelecionada;

            })
            .forEach(function(oferta) {

                const option =
                    document.createElement(
                        'option'
                    );

                option.value =
                    oferta.id;

                option.text =
                    oferta.disciplina.nome;

                selectDisciplina.appendChild(
                    option
                );
            });

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | REPRESENTANTE
    |--------------------------------------------------------------------------
    */

    if (EH_REPRESENTANTE) {

        /*
        |----------------------------------------------------------------------
        | Ainda não selecionou professor
        |----------------------------------------------------------------------
        */

        if (
            !selectProfessor ||
            !selectProfessor.value
        ) {

            const option =
                document.createElement(
                    'option'
                );

            option.value = '';

            option.text =
                'Selecione um professor primeiro';

            selectDisciplina.appendChild(
                option
            );

            return;
        }


        /*
        |----------------------------------------------------------------------
        | Professor selecionado
        |----------------------------------------------------------------------
        */

        const professorId =
            selectProfessor.value;


        OFERTAS
            .filter(function(oferta) {

                return String(
                    oferta.professor_id
                ) === String(
                    professorId
                );

            })
            .forEach(function(oferta) {

                const option =
                    document.createElement(
                        'option'
                    );

                option.value =
                    oferta.id;

                option.text =
                    oferta.disciplina.nome;

                selectDisciplina.appendChild(
                    option
                );
            });
    }
}



function carregarProfessores() {

    const select =
        document.getElementById(
            'professor_id'
        );


    if (!select) {
        return;
    }


    select.innerHTML = '';


    const professores = [];


    /*
    |--------------------------------------------------------------------------
    | Percorre as ofertas e cria uma lista de professores únicos
    |--------------------------------------------------------------------------
    */

    OFERTAS.forEach(function(oferta) {

        if (!oferta.professor) {
            return;
        }


        const jaExiste =
            professores.some(function(professor) {

                return String(professor.id)
                    === String(oferta.professor_id);

            });


        if (!jaExiste) {

            professores.push({

                id:
                    oferta.professor_id,

                nome:
                    oferta.professor.nome

            });
        }
    });


    /*
    |--------------------------------------------------------------------------
    | Adiciona os professores ao select
    |--------------------------------------------------------------------------
    */

    professores.forEach(function(professor) {

        const option =
            document.createElement(
                'option'
            );

        option.value =
            professor.id;

        option.text =
            professor.nome;

        select.appendChild(
            option
        );
    });
}

        function novoEvento(data){
            const hoje =
                new Date()
                    .toISOString()
                    .split('T')[0];

            if (data < hoje) {

            alert(
                'Não é possível criar eventos em datas passadas.'
            );

            return;
    }
            
    if (
        EH_PROFESSOR &&
        !turmaSelecionada
    ) {

        alert(
            'Selecione uma turma antes de criar um evento.'
        );

        return;
    }


    document.getElementById(
        'tituloModal'
    ).innerText =
        'Novo Evento';


    document.getElementById(
        'formEvento'
    ).reset();

    if (EH_REPRESENTANTE) {
     carregarProfessores();

}
carregarOfertas();


    document.getElementById(
        'evento_id'
    ).value = '';


    document.getElementById(
        'data_inicio'
    ).value = data;


    carregarOfertas();


    document.getElementById(
        'btnExcluir'
    ).style.display =
        'none';


    document.getElementById(
        'btnSalvar'
    ).style.display =
        PODE_GERENCIAR
            ? 'inline'
            : 'none';


    abrirModal();
}

        function editarEvento(evento)
{
    document.getElementById(
        'tituloModal'
    ).innerText =
        'Editar Evento';


    document.getElementById(
        'evento_id'
    ).value =
        evento.id;


    document.getElementById(
        'titulo'
    ).value =
        evento.title;


    document.getElementById(
        'tipo'
    ).value =
        evento.extendedProps.tipo;


    document.getElementById(
        'data_inicio'
    ).value =
        evento.startStr;


    document.getElementById(
        'hora_inicio'
    ).value =
        evento.extendedProps.hora_inicio
        ?? '';


    document.getElementById(
        'hora_fim'
    ).value =
        evento.extendedProps.hora_fim
        ?? '';


    document.getElementById(
        'descricao'
    ).value =
        evento.extendedProps.descricao
        ?? '';


    /*
    |--------------------------------------------------------------------------
    | Professor
    |--------------------------------------------------------------------------
    */

    if (EH_PROFESSOR) {

        turmaSelecionada =
            evento.extendedProps.turma_codigo;

        const selectTurma =
            document.getElementById(
                'selectTurma'
            );

        if (selectTurma) {

            selectTurma.value =
                turmaSelecionada;
        }
    }

    /*
|--------------------------------------------------------------------------
| Representante
|--------------------------------------------------------------------------
*/

if (EH_REPRESENTANTE) {

    const selectProfessor =
        document.getElementById(
            'professor_id'
        );

    if (selectProfessor) {

        selectProfessor.value =
            evento.extendedProps.professor_id;
    }
}
    carregarOfertas();


    document.getElementById(
        'disciplina_professor_id'
    ).value =
        evento.extendedProps
            .disciplina_professor_id;


    document.getElementById(
        'btnSalvar'
    ).style.display =
        'inline';


    document.getElementById(
    'btnExcluir').style.display =
    evento.extendedProps.pode_excluir
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

        const dados = {
            titulo: document.getElementById('titulo').value,
            tipo: document.getElementById('tipo').value,
            data_inicio: document.getElementById('data_inicio').value,
            hora_inicio: document.getElementById('hora_inicio').value,
            hora_fim: document.getElementById('hora_fim').value,
            descricao: document.getElementById('descricao').value,
            disciplina_professor_id:
                document.getElementById('disciplina_professor_id').value
        };

        try {

            const resposta = await fetch(url, {
                method: metodo,

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',

                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content
                },

                body: JSON.stringify(dados)
            });

            const resultado = await resposta.json();

            /*
             * Se o servidor recusou o evento
             */
            if (!resposta.ok) {

                if (resultado.limite_atingido) {

                    mostrarAlertaLimite(
                        resultado.mensagem
                    );

                    return;
                }

                alert(
                    resultado.mensagem ||
                    'Não foi possível salvar o evento.'
                );

                return;
            }

            /*
             * Só fecha o formulário se o evento
             * realmente tiver sido salvo.
             */
            fecharModal();

            calendar.refetchEvents();

        } catch (erro) {

            console.error(erro);

            alert(
                'Ocorreu um erro ao tentar salvar o evento. Verifique se as informações estão corretas.'
            );
        }
    });

        document.getElementById('btnExcluir')
    .addEventListener('click', async function() {

        console.log('BOTÃO EXCLUIR CLICADO');

        if (!confirm('Excluir evento?')) {
            return;
        }

        let id =
            document.getElementById('evento_id').value;

        console.log('ID DO EVENTO:', id);

        let resposta =
            await fetch('/eventos/' + id, {

                method: 'DELETE',

                headers: {
                    'X-CSRF-TOKEN':
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            .content
                }

            });

        console.log(
            'STATUS:',
            resposta.status
        );

        console.log(
            'RESPOSTA:',
            await resposta.text()
        );

        if (!resposta.ok) {

            alert(
                'Erro ao excluir evento. Status: '
                + resposta.status
            );

            return;
        }

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
                'viewCriador'
            ).innerText =
                evento.extendedProps.criador ?? '-';

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