# Tema WordPress — Aline Brito · Design com Alma

Tema de portfólio com **home estática** e seção **Projetos dinâmica** gerenciada pelo painel do WordPress.

## Instalação

1. Compacte a pasta `aline-wp-theme` em um arquivo `.zip` (já fornecido).
2. No painel do WordPress: **Aparência → Temas → Adicionar novo → Enviar tema** e selecione o `.zip`.
3. Clique em **Ativar**.
4. Vá em **Configurações → Links permanentes** e clique em **Salvar** (sem mudar nada) — isso garante que as URLs `/projetos/...` funcionem.
5. Em **Configurações → Leitura**, confirme que "A sua página inicial mostra" está como **As suas últimas publicações** (a `front-page.php` do tema é carregada automaticamente).

## Cadastrando um projeto (destaque da home)

No menu lateral do admin aparece **Projetos**. Clique em **Adicionar novo** e preencha:

- **Título** — nome do projeto (ex.: "Diário do Participante").
- **Editor (blocos)** — todo o corpo do case: problema, pesquisa, processo, solução, resultados, citação, etc. Use blocos nativos do WordPress (parágrafo, título, lista, imagem, citação, colunas).
- **Imagem destacada** — capa usada tanto no card da home quanto no topo do case.
- **Resumo (Excerpt)** — opcional, usado se o campo "Subtítulo" estiver vazio.
- **Atributos da página → Ordem** — define a ordem dos cards na home (menor número aparece primeiro).

Na caixa **Detalhes do projeto** (abaixo do editor):

| Campo | Exemplo |
|---|---|
| Subtítulo | "App para registro de sintomas no estudo clínico da vacina contra HPV..." |
| Tag | "Saúde · Mobile" |
| Número | "01" |
| Cliente | "Instituto Butantan" |
| Papel | "UX Research & Product Design" |
| Contexto | "Vacina HPV — Fase clínica" |
| Plataforma | "App · Dashboard" |
| ID do próximo projeto | ID numérico do próximo case (opcional) |

Clique em **Publicar**. O projeto aparece automaticamente na seção "Projetos" da home e fica acessível em `/projetos/slug-do-projeto/`.

## Estrutura

```
aline-wp-theme/
├── style.css              # Cabeçalho oficial do tema
├── functions.php          # CPT "projeto", meta box, enqueue, helpers
├── header.php / footer.php
├── front-page.php         # Home (conteúdo estático + grid dinâmico)
├── single-projeto.php     # Detalhe de cada case
├── index.php              # Fallback
└── assets/
    ├── styles.css
    ├── logo.svg
    ├── aline-photo.png
    ├── project-1.jpg
    └── project-2.jpg
```

## Personalização rápida

- **Foto e logo**: substitua `assets/aline-photo.png` e `assets/logo.svg`.
- **Textos da home (hero, sobre, contato, resultados)**: edite `front-page.php`.
- **Cores e tipografia**: tokens no topo de `assets/styles.css` (`:root { --bg, --fg, --primary, ... }`).
