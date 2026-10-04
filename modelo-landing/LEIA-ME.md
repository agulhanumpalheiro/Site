# Modelo de landing page (WordPress)

Um único ficheiro, `landing.html`, para colar no WordPress. Não precisa de plugins.

## Estrutura

1. Cabeçalho: logótipo + **botão 1**
2. Primeira dobra: etiqueta, título, subtítulo, 3 benefícios, **botão 2**, imagem
3. Benefícios + testemunho + **botão 3** (secção opcional, pode apagar-se)
4. Formulário: nome, telefone, email e consentimento
5. Rodapé: contactos, NIF, política de privacidade, livro de reclamações

Os três botões levam ao formulário.

## Como usar num cliente novo

1. Abrir `landing.html` e copiar tudo.
2. Trocar todos os textos entre `[colchetes]`.
3. Em **CORES DA MARCA** (no início), trocar as cores pelas da marca do cliente.
4. Em **CONFIGURAÇÃO** (no fim):
   - `email`: o email que recebe os contactos. Enquanto estiver vazio, o formulário fica em modo de teste e não envia nada.
   - `origem`: o nome da página, para saber de onde veio cada contacto.
   - `pixelId`: o ID do Pixel da Meta. Deixar vazio se o WordPress já tiver o Pixel instalado (por plugin), porque o evento **Lead** é enviado na mesma.
   - `whatsapp`: o número com indicativo (ex.: `351912345678`). Vazio esconde o botão.
   - `paginaObrigado`: o endereço de uma página de obrigado (ex.: `/obrigado/`). Vazio mostra a mensagem de sucesso no próprio formulário.
   - `crm`: `true` só nas landing pages da Agulha num Palheiro, para o contacto entrar no CRM.
5. No WordPress: criar a página, escolher o modelo **sem cabeçalho nem rodapé do tema** (por exemplo "Elementor Canvas" ou "Página em branco"), juntar um bloco **HTML personalizado** e colar.

## Primeiro envio

Os emails são enviados pelo serviço gratuito FormSubmit. No **primeiro** envio de cada email novo, o FormSubmit manda uma mensagem a pedir para confirmar. Basta clicar em "Activate Form" uma vez. A partir daí os contactos chegam normalmente. Faça sempre um envio de teste antes de lançar a campanha.

## Imagens

Para pôr uma fotografia, carregá-la na Biblioteca de Multimédia do WordPress, copiar o link e trocar o texto da caixa da imagem por:

```html
<img src="LINK-DA-IMAGEM" alt="Descrição da imagem">
```
