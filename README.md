# Evolução do Sistema MediAgenda

## Descrição da Aplicação
O MediAgenda é uma aplicação web desenvolvida em PHP destinada ao gerenciamento e agendamento de consultas médicas. O sistema facilita o processo clínico ao registrar pacientes, horários, e integrar diretamente médicos e suas respectivas especialidades.

## Funcionalidades Implementadas
- **Autenticação:** Sistema de Login e Logout para usuários da clínica.
- **Painel Principal:** Visualização de calendário com todos os agendamentos cadastrados.
- **Módulo de Especialidades:** CRUD completo (Cadastro, Edição, Listagem, Exclusão).
- **Módulo de Médicos:** CRUD completo de médicos e amarração das especialidades em banco de dados real.
- **Módulo de Agendamentos:** Visualização de consultas marcadas (CRUD de base implementado).
- **Ajuste de Navegação:** Transição responsiva e ajustada via barra lateral.

## Tecnologias Utilizadas
- **Backend:** PHP 7/8
- **Banco de Dados:** MySQL / MariaDB (Script de tabelas interligadas)
- **Frontend:** HTML5, CSS3, JavaScript
- **Frameworks de Interface:** Bootstrap 5, FontAwesome (Ícones), SweetAlert2 (Notificações)

## Instruções Básicas de Execução
1. Realize o clone deste repositório na pasta do seu servidor Apache local (Ex: `htdocs` no XAMPP).
2. Abra o SGBD (ex: MySQL Workbench ou phpMyAdmin) e execute o script `script.sql` (ou equivalente disponibilizado) para estruturar o banco `labdbprog2`.
3. Verifique o arquivo `conexao.php` e altere a senha e a porta do MySQL de acordo com sua máquina local.
4. Acesse via navegador `http://localhost/mediagenda/index.php`.
5. Utilize os usuários padrões criados no script (Ex: `aluno` / senha: `123456`).

## Integrantes do Grupo
- Danylo Henrique de Castro Silva
- Erik Souza Lopes
- Eduardo Reis Russi Souza
