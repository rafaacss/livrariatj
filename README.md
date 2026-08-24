# Livraria

Aplicação web para gerenciamento de livros, autores e assuntos, desenvolvida com Symfony e Doctrine ORM.

O projeto possui cadastro e gerenciamento de livros, autores e assuntos, relacionamento entre livros e autores/assuntos, relatórios e geração de relatórios em PDF.

## Stack

- **PHP:** 8.4
- **Symfony:** 8.1
- **Doctrine ORM:** 3.6
- **Doctrine Migrations:** 4.x
- **Doctrine Fixtures:** 4.x
- **Twig:** 3.x
- **MySQL:** 8.0
- **Redis:** Alpine (infraestrutura disponível para cache/armazenamento em memória)
- **PHPUnit:** 13.x
- **Nginx:** Alpine
- **Docker / Docker Compose**

## Pré-requisitos

Para executar o projeto localmente, é necessário ter instalado:

- Git
- Docker
- Docker Compose

O PHP 8.4, Composer e as extensões necessárias são disponibilizados pelo container da aplicação. Portanto, não é necessário instalar PHP ou Composer diretamente no sistema operacional para executar o projeto através do Docker.

## Como executar o projeto

### 1. Clonar o repositório

Clone o projeto e entre na pasta:

```bash
git clone <URL_DO_REPOSITORIO>
cd livraria
```

### 2. Subir os containers

Na raiz do projeto, execute:

```bash
docker compose up -d --build
```

O Docker irá criar e iniciar os seguintes serviços:

- `app` — PHP 8.4-FPM, Composer e Xdebug
- `nginx` — servidor web
- `mysql` — MySQL 8.0
- `redis` — Redis

### 3. Instalar as dependências

O container possui um entrypoint que instala automaticamente as dependências do Composer quando a pasta `vendor` ainda não existe.

Também é possível executar manualmente:

```bash
docker compose exec app composer install
```

O `composer install` também executa os scripts automáticos do Symfony, incluindo:

- limpeza do cache;
- instalação dos assets.

### 4. Configuração do banco de dados

O projeto utiliza as seguintes configurações para o banco no ambiente Docker:

```text
Host: mysql
Porta: 3306
Banco: livrariatj
Usuário: livrariatj
Senha: livrariatj
```

A conexão utilizada pela aplicação é:

```text
mysql://livrariatj:livrariatj@mysql:3306/livrariatj?serverVersion=8.0&charset=utf8mb4
```

O banco de testes utilizado pelo PHPUnit é criado automaticamente pelo script:

```text
docker/mysql/init.sql
```

Esse script cria o banco:

```text
livrariatj_test
```

### 5. Executar as migrations

Após os containers estarem em execução, execute as migrations do ambiente de desenvolvimento:

```bash
docker compose exec app php bin/console doctrine:migrations:migrate --no-interaction
```

Para verificar o estado das migrations:

```bash
docker compose exec app php bin/console doctrine:migrations:status
```

### 6. Banco de testes

Os testes automatizados são executados com `APP_ENV=test`. Essa configuração é definida no `phpunit.dist.xml` e faz com que o Symfony utilize o arquivo `.env.test`.

O banco de testes possui configuração própria e deve ser preparado separadamente do banco de desenvolvimento.

Para criar o banco de testes, caso ele ainda não exista:

```bash
docker compose exec app php bin/console doctrine:database:create --env=test --if-not-exists
```

Para aplicar as migrations no banco de testes:

```bash
docker compose exec app php bin/console doctrine:migrations:migrate --env=test --no-interaction
```

Verifique o estado das migrations do ambiente de teste:

```bash
docker compose exec app php bin/console doctrine:migrations:status --env=test
```

> **Importante:** as migrations utilizadas no ambiente de teste são as mesmas versões armazenadas em `migrations/`. O ambiente `test` utiliza uma conexão de banco separada, definida pela configuração de teste. Não é necessário manter uma segunda pasta de migrations exclusivamente para testes.

### 7. Carregar as fixtures

Para criar a massa de dados inicial:

```bash
docker compose exec app php bin/console doctrine:fixtures:load --no-interaction
```

## Fluxo completo de instalação

Para configurar o projeto a partir de um clone limpo:

```bash
git clone <URL_DO_REPOSITORIO>
cd livraria

docker compose up -d --build

docker compose exec app composer install

docker compose exec app php bin/console doctrine:migrations:migrate --no-interaction

docker compose exec app php bin/console doctrine:fixtures:load --no-interaction
```

Depois, acesse:

```text
http://localhost
```

Por fim, execute os testes:

```bash
docker compose exec app php bin/phpunit
```

Esse procedimento garante que o banco seja recriado e que as migrations e fixtures sejam executadas de fato.

## Testes

O projeto utiliza PHPUnit para testes automatizados.

O ambiente de testes utiliza:

```text
APP_ENV=test
```

Essa configuração é definida no `phpunit.dist.xml`, portanto os testes executados pelo PHPUnit não utilizam o banco de desenvolvimento.

Antes da primeira execução dos testes em um ambiente novo, prepare o banco de testes:

```bash
docker compose exec app php bin/console doctrine:database:create --env=test --if-not-exists
docker compose exec app php bin/console doctrine:migrations:migrate --env=test --no-interaction
```

### Executar os testes com saída detalhada

```bash
docker compose exec app php bin/phpunit --testdox
```

## Principais funcionalidades

### Livros

- Listagem de livros
- Cadastro de livros
- Edição de livros
- Visualização dos detalhes
- Exclusão de livros
- Associação com autores
- Associação com assuntos

### Autores

- Listagem de autores
- Cadastro de autores
- Edição de autores
- Visualização dos detalhes
- Exclusão de autores
- Associação com livros

### Assuntos

- Listagem de assuntos
- Cadastro de assuntos
- Edição de assuntos
- Visualização dos detalhes
- Exclusão de assuntos
- Associação com livros

### Relatórios

O projeto possui relatórios relacionados aos livros e autores.

Também existe geração dos relatórios em PDF utilizando a biblioteca Dompdf.

## Estrutura do projeto

```text
livraria/
├── config/
│   └── ...                  # Configurações do Symfony
│
├── docker/
│   ├── mysql/
│   │   └── init.sql         # Inicialização do banco de testes
│   ├── nginx/
│   │   └── default.conf     # Configuração do Nginx
│   └── php/
│       ├── Dockerfile       # Imagem PHP 8.4
│       └── entrypoint.sh    # Inicialização do container
│
├── migrations/              # Migrations do Doctrine
│
├── public/
│   └── index.php            # Front controller
│
├── src/
│   ├── Controller/          # Controllers
│   ├── DataFixtures/        # Massa de dados
│   ├── Entity/              # Entidades Doctrine
│   └── Repository/          # Repositórios
│
├── templates/
│   ├── autor/               # Templates de autores
│   ├── assunto/             # Templates de assuntos
│   ├── livro/               # Templates de livros
│   ├── relatorio/           # Templates dos relatórios
│   └── bundles/              # Templates de páginas de erro
│
├── tests/
│   ├── Controller/          # Testes funcionais
│   └── bootstrap.php
│
├── docker-compose.yml
├── composer.json
├── composer.lock
├── phpunit.dist.xml
└── README.md
```

## Comandos úteis

### Acessar o container da aplicação

```bash
docker compose exec app bash
```

### Limpar o cache do Symfony

```bash
docker compose exec app php bin/console cache:clear
```

### Verificar as migrations

```bash
docker compose exec app php bin/console doctrine:migrations:status
```

### Validar o schema do Doctrine

```bash
docker compose exec app php bin/console doctrine:schema:validate
```

## Decisões técnicas

### Relatório baseado em View no banco

O relatório de livros utiliza uma **view no MySQL**, em vez de concentrar toda a montagem do relatório em uma query executada diretamente pela aplicação. A decisão mantém a consulta do relatório próxima aos dados e permite reutilizá-la de forma consistente, deixando o Symfony responsável por consumir o resultado e apresentar as informações.

### Symfony 8.1

Foi adotado o Symfony 8.1 em vez do Symfony 7.4 LTS para trabalhar com uma versão mais atual do framework e manter o projeto alinhado ao PHP 8.4 utilizado no ambiente. Como o projeto é um exercício/avaliação técnica e não depende de uma janela de suporte LTS de longo prazo, a escolha prioriza a versão atual e os recursos disponíveis.

### Fixtures para massa de dados

As fixtures foram mantidas separadas da lógica da aplicação para permitir reproduzir rapidamente um cenário conhecido de desenvolvimento e avaliação. Isso facilita a validação do relatório, dos relacionamentos e dos testes após um clone limpo.

### Testes unitários para regras isoladas

Regras que não dependem de banco ou HTTP devem ser testadas de forma unitária, mantendo os testes rápidos e determinísticos. O método `agruparPorAutor()` é um exemplo adequado porque recebe um array e devolve um array, permitindo validar inclusive o caso de um livro possuir múltiplos autores sem depender do Doctrine.

## Observação importante sobre o relatório

Um livro pode possuir **múltiplos autores**. Como o relatório relaciona livros e autores, o mesmo livro pode aparecer em mais de uma linha quando possui mais de um autor.

Consequentemente, **não se deve somar diretamente a coluna de valor do relatório para obter o valor total do acervo**. Um livro com dois autores, por exemplo, pode aparecer duas vezes e seu valor seria contabilizado duas vezes em uma soma simples.

Essa duplicidade é esperada pelo modelo do relatório e deve ser considerada ao interpretar os resultados. Para obter o valor real do acervo, é necessário considerar cada livro uma única vez.

### Redis

O Redis permanece disponível no `docker-compose` como serviço de infraestrutura preparado para cache e outras necessidades de armazenamento em memória. Atualmente ele não é uma dependência obrigatória da regra de negócio principal da aplicação; mantê-lo no ambiente deixa a infraestrutura preparada para uma futura utilização sem alterar a composição dos containers.

## O que ficou de fora

Os seguintes recursos não fazem parte do escopo principal da aplicação:

- Autenticação de usuários.
- API REST.
- Documentação OpenAPI/Swagger.
- Pipeline de CI/CD.
- Deploy automatizado em produção.
- Integrações com serviços externos.

Esses recursos podem ser adicionados posteriormente conforme a evolução do projeto.

## Licença

Projeto desenvolvido para fins de avaliação técnica.
