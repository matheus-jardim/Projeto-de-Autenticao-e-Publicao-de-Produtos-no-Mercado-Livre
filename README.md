# Projeto de Autenticação e Publicação de Produtos no Mercado Livre

Este é um projeto de autenticação para integração com a API do Mercado Livre. Ele permite que os usuários autentiquem sua conta do Mercado Livre e realizem ações autorizadas em nome do usuário autenticado, como a publicação de produtos.

## Resumo

Este projeto de autenticação permite que os usuários autentiquem sua conta do Mercado Livre e realizem ações autorizadas em nome do usuário autenticado, como a publicação de produtos.

## Requisitos

- PHP 7.0 ou superior
- MySQL
- Extensão PDO habilitada no PHP

## Configuração

1. Faça o download ou clone este repositório em um servidor local ou em seu ambiente de hospedagem.
2. Caso não tenha um banco de dados criado, o sistema irá criar automaticamente um novo banco de dados e uma tabela para você.
3. Abra o arquivo `auth/mercadolivre/config.php` e configure as seguintes variáveis de acordo com o seu ambiente:
   - `$base`: URL base do projeto.
   - `$clientId`: ID do cliente fornecido pelo Mercado Livre.
   - `$clientSecret`: Chave secreta do cliente fornecida pelo Mercado Livre.
   - `$redirectUri`: URL de redirecionamento após a autenticação.
   - `$db_host`: Nome do host do banco de dados.
   - `$db_user`: Nome de usuário do banco de dados.
   - `$db_pass`: Senha do banco de dados.
   - `$db_name`: Nome do banco de dados criado para o projeto.
4. Salve o arquivo `config.php`.

## Utilização

1. Acesse o projeto em seu navegador e você será automaticamente redirecionado para a página de autenticação do Mercado Livre.
2. Faça login em sua conta do Mercado Livre, se necessário.
3. Após a autenticação bem-sucedida, você será redirecionado de volta para o projeto e poderá realizar ações autorizadas em nome do usuário autenticado, como a publicação de produtos.

4. **Criação do Aplicativo no Mercado Livre (caso não tenha um aplicativo criado):**

   - Acesse a página de desenvolvedores do Mercado Livre.
   - Faça login em sua conta do Mercado Livre ou crie uma nova conta, se necessário.
   - No painel do desenvolvedor, clique em "Minhas Aplicações" ou "Meus Aplicativos".
   - Clique no botão "Criar Aplicativo" ou "Novo Aplicativo".
   - Preencha as informações solicitadas, como nome do aplicativo, descrição, categoria, etc.
   - Configure as permissões necessárias para o aplicativo, de acordo com as funcionalidades que você pretende utilizar.
   - Após a criação do aplicativo, você receberá o ID do cliente e a chave secreta necessários para configurar o projeto.
   - Com o aplicativo criado e as credenciais em mãos, você pode configurar o arquivo `config.php` do projeto com as informações fornecidas pelo Mercado Livre.

5. **Preenchimento do formulário (index.php):**

   - Ao preencher o formulário, certifique-se de preencher o título do produto, pois a categoria será identificada automaticamente.
   - Além do título, o preenchimento dos campos de preço e estoque também é obrigatório.

**Observação:** No arquivo `form_action.php`, foram inseridos atributos genéricos no `$productData`, como `condition`, `sale_terms` e `pictures`, para não ser necessário preencher vários campos, já que se trata apenas de uma demonstração. Além disso, nos `attributes`, foram inseridos `BRAND`, `EAN`, `MODEL` e `COLOR` para ilustrar a estrutura de atributos do produto.

## Arquivos e Diretórios Principais

- `auth/mercadolivre/config.php`: Arquivo de configuração do projeto.
- `auth/mercadolivre/challenge.php`: Gera um desafio de autenticação e armazena as informações na sessão.
- `auth/mercadolivre/auth.php`: Inicia o processo de autenticação com o Mercado Livre.
- `auth/mercadolivre/models/User.php`: Classe que representa um usuário autenticado.
- `auth/mercadolivre/models/UserDAO.php`: Interface do Data Access Object (DAO) para manipulação dos dados do usuário.
- `auth/mercadolivre/models/UserDAOMysql.php`: Implementação do DAO para MySQL.
- `auth/mercadolivre/callback.php`: Página de callback após a autenticação do Mercado Livre.

## Considerações Finais

Este projeto de autenticação para integração com a API do Mercado Livre fornece uma base sólida para você desenvolver recursos adicionais e realizar operações na plataforma do Mercado Livre em nome dos usuários autenticados. Sinta-se à vontade para explorar, modificar e aprimorar o projeto de acordo com suas necessidades.

