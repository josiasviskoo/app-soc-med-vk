# Viskoo Approval WordPress Plugin

**Autor:** Josias Viskoo
**Site:** https://viskoo.com.br

Aplicação de aprovação de conteúdo para clientes exclusivos da Viskoo. O plugin utiliza o template `dashboard.html` como interface front-end e gerencia informações usando custom post types do WordPress.

## Principais funcionalidades

- Custom Post Types:
  - `viskoo_client` para cadastro de clientes (nome/subheadline exibidos no dashboard).
  - `viskoo_headline` para títulos/headlines que precisam ser aprovados.
  - `viskoo_content` para conteúdos multimídia (imagens, vídeos, carrossel, etc.).
- Status customizados: `pending`, `approved`, `rejected` (revisão).
- Meta boxes para sugerir data, escolher plataforma e formato.
- Front-end dashboard via shortcode `[viskoo_dashboard]` / página gerada automaticamente.
- Página de login customizada (`[viskoo_login]` / `/login-media`).
- Usuários de papel `viskoo_dashboard_admin` podem acessar o dashboard mas não o painel WP.
- Ações de aprovação/reprovação feitas via AJAX atualizam o post e permitem comentário de feedback.
- Cron job de auto-aprovação após 24 horas de status `pending`.
- Uso da estrutura visual do `dashboard.html` original, com filtros por data/status, contadores e cards.

## Instalação

1. Coloque o diretório `viskoo-approval` em `wp-content/plugins/`.
2. Ative o plugin no painel do WordPress.
3. Durante a ativação dois pages são criados automaticamente:
   - **Dashboard** (`/dashboard`) – contém `[viskoo_dashboard]`.
   - **Login Media** (`/login-media`) – contém `[viskoo_login]`.
4. Crie usuários com a role `Administrador da Dashboard` ou altere um existente.

## Uso

1. Acesse `/login-media`, efetue login com usuário de role `viskoo_dashboard_admin`.
2. Você será redirecionado ao `/dashboard` onde todos os conteúdos e headlines são listados.
3. Filtros, tempo de publicação e ações de aprovação/reprovação seguem o layout original.
4. No admin do WordPress, adicione novos conteúdos/headlines pela interface padrão ou use as futuras melhorias no dashboard.

> Observação: a área de administração do dashboard é independente do painel WP; usuários dessa role não conseguem entrar no `/wp-admin`.

## Extensões Futuras
- Modal para criação de novos itens diretamente no dashboard.
- Upload de mídias via frontend.
- Integração com REST API para consumo por outros sistemas.


---

Esse plugin foi gerado a partir do protótipo `dashboard.html` fornecido e adapta sua estrutura para funcionar como parte de um site WordPress.