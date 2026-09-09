# SDP - Sistema de Protocolos e Requerimentos

Sistema do IFBA Campus Seabra para solicitar requerimentos academicos e administrativos sem uso de papel.

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Node.js](https://img.shields.io/badge/Node.js-20+-339933?style=for-the-badge&logo=node.js&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-6.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Status](https://img.shields.io/badge/Status-Em%20Desenvolvimento-orange?style=for-the-badge)

<p align="center">
  <b>Geração, Assinatura Digital e Despacho Automatizado de Requerimentos por E-mail</b><br>
  <i>Instituto Federal de Educação, Ciência e Tecnologia da Bahia (IFBA) — Campus Seabra</i>
</p>

</div>
## O que o sistema faz

- Autentica alunos e servidores com matricula e senha do SUAP.
- Permite login local de administradores por e-mail e senha.
- Exibe requerimentos organizados por setor, como CORES e COTEP.
- Recebe justificativas e anexos enviados pelo aluno.
- Gera um PDF do requerimento.
- Envia o PDF e os anexos por e-mail ao aluno e ao setor responsavel.
- Mantem o historico dos requerimentos no banco de dados.

## Fluxo principal

```text
Login -> Escolha do requerimento -> Justificativa e anexos
     -> Validacao -> Registro no banco -> PDF e e-mails
```


## Requisitos

- PHP 8.3 ou superior
- Composer
- Node.js 20 ou superior e npm
- MySQL/MariaDB ou SQLite
- Acesso ao SUAP para autenticacao de alunos e servidores

## Instalacao rapida

Na raiz do projeto, execute:

```bash
composer run setup
```

Esse comando instala as dependencias, cria o arquivo `.env`, gera a chave da aplicacao, executa as migrations e compila os assets.

Para iniciar o ambiente de desenvolvimento:

```bash
composer run dev
```

O comando inicia o servidor Laravel, o worker de filas e o Vite.

## Configuracao

Copie `.env.example` para `.env` caso o arquivo ainda nao exista:

```bash
cp .env.example .env
php artisan key:generate
```

Configure principalmente:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=SDP
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log
MAIL_FROM_ADDRESS="protocolos.seabra@ifba.edu.br"
MAIL_FROM_NAME="SDP - IFBA Seabra"

SUAP_BASE_URL="https://suap.ifba.edu.br"
SUAP_API_URL="https://suap.ifba.edu.br/api/v2"
```

Para envio real de e-mails, altere `MAIL_MAILER` e informe os dados do servidor SMTP.

## Comandos uteis

```bash
# Rodar migrations
php artisan migrate

# Executar os testes
composer run test

# Gerar os assets para producao
npm run build
```

## Rotas principais

| Metodo | Rota | Funcao |
|---|---|---|
| GET | `/login` | Tela de login |
| POST | `/login` | Autenticacao |
| POST | `/logout` | Encerramento da sessao |
| GET | `/admin/dashboard` | Painel administrativo protegido por papel |
| GET | `/requerimentos/aluno` | Painel do aluno |
| GET | `/requerimentos/aluno/novo` | Novo requerimento |
| POST | `/requerimentos/aluno/enviar-email` | Envio do requerimento |
| GET | `/requerimentos/aluno/meusRequerimentos` | Historico do aluno |
| GET | `/requerimentos/servidor` | Painel do servidor |
| GET | `/requerimentos/gerar-pdf` | Geracao do PDF |

## Estrutura essencial

```text
app/
  Http/Controllers/          Controllers da aplicacao
  Mail/                       E-mails enviados pelo sistema
  Models/                     Usuarios e requerimentos
  Services/                   Integracao e sincronizacao com o SUAP
config/
  modelos_requerimentos.php  Catalogo de requerimentos
  setores.php                Destinatarios por setor
database/migrations/          Estrutura do banco de dados
resources/views/              Telas, e-mails e template do PDF
routes/web.php                Rotas web
scraper/                      Integracao alternativa com Playwright
```

## Configuracao dos requerimentos

Os modelos disponiveis ficam em `config/modelos_requerimentos.php` e os destinatarios dos setores em `config/setores.php`.
