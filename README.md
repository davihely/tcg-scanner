# TCG Scanner

Aplicação web que identifica cartas de **Pokémon TCG** pela câmera do celular ou do computador. O usuário fotografa a carta e o back-end envia a imagem para a **API do Google Gemini**, que devolve o nome, o número na coleção e a raridade. A página inicial lista as coleções oficiais do jogo usando a **API pública TCGdex**.

Feito com **Laravel** no back-end e **Vue 3 + Inertia** no front-end.

> Projeto em desenvolvimento.

## Funcionalidades

- **Scanner pela câmera**: acessa a câmera traseira do dispositivo pelo navegador (`getUserMedia`), captura a foto e mostra a pré-visualização antes de enviar.
- **Identificação com IA**: o Laravel envia a imagem em base64 para o modelo `gemini-2.5-flash`, com um prompt que exige uma resposta em JSON com `nome`, `numero` (ex.: `170/165`) e `raridade` (Full Art, Holo, Reverse Holo etc.).
- **Listagem de coleções**: a página inicial consome a TCGdex (`/v2/pt/sets`) e exibe as coleções em português com seus logos.
- **Tratamento de erros**: falhas na API externa são registradas no log do Laravel e o usuário recebe uma mensagem amigável.
- Autenticação completa com Laravel Breeze (login, cadastro e perfil).

## Como funciona

```
Navegador (Vue)                Laravel                         Google Gemini
──────────────                 ───────                         ─────────────
captura a foto  ── base64 ──▶  POST /process-card
                               valida a imagem
                               monta o prompt   ── imagem ──▶  analisa a carta
                               lê o JSON        ◀── JSON ────  nome, número, raridade
exibe o resultado ◀── JSON ──
```

## Tecnologias

| Camada | Tecnologias |
|---|---|
| Back-end | PHP 8.3+, Laravel 13, HTTP Client do Laravel |
| Front-end | Vue 3, Inertia.js, Vite, Tailwind CSS, Axios |
| IA | Google Gemini (`gemini-2.5-flash`), entrada de imagem |
| Dados externos | TCGdex API |
| Banco de dados | SQLite |

## Como rodar localmente

Pré-requisitos: PHP 8.3+, Composer, Node.js 18+ e uma chave gratuita da API do Gemini ([Google AI Studio](https://aistudio.google.com/app/apikey)).

```bash
# 1. Clonar e instalar dependências
git clone https://github.com/davihely/tcg-scanner.git
cd tcg-scanner
composer install
npm install

# 2. Configurar o ambiente
cp .env.example .env
php artisan key:generate
echo "GEMINI_API_KEY=sua_chave_aqui" >> .env

# 3. Banco de dados (SQLite)
touch database/database.sqlite
php artisan migrate

# 4. Subir a aplicação (em dois terminais)
php artisan serve
npm run dev
```

Acesse `http://localhost:8000` para ver as coleções e `http://localhost:8000/scanner` para escanear uma carta.

> O navegador só libera a câmera em `localhost` ou em sites com HTTPS.

## Próximos passos

- Salvar as cartas identificadas em uma coleção pessoal por usuário.
- Cruzar o resultado da IA com a TCGdex para trazer imagem oficial e preço.

## Autor

**Davi Hely**: desenvolvedor full stack (PHP, Laravel, Vue.js)

- [LinkedIn](https://www.linkedin.com/in/davihely/)
- [Portfólio](https://davihely.github.io/profile-page/)
