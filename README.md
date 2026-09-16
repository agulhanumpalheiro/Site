# Site Agulha num Palheiro

agulhanumpalheiro.com — Hostinger, publicado por Git.

## Uma landing page por serviço

| Serviço | Endereço | Formulário identificado como |
|---|---|---|
| — (entrada) | / | Site geral (pop-up) |
| Gestão de Anúncios | /anuncios/ | Anúncios |
| Redes Sociais | /redes-sociais/ | Redes Sociais |
| Loja Online e Sites | /loja-online-site/ | Loja Online/Site |
| Prospeção Comercial | /prospecao/ | Prospeção Comercial |
| Consultoria (diagnóstico) | /consultoria/ | Consultoria |
| Landing geral | /contacto/ | Landing geral |
| Obrigado | /obrigado/ | — |
| Privacidade | /privacidade/ | — |

**Prospeção vs Consultoria:** /prospecao/ é execução (montamos e pomos a funcionar).
/consultoria/ é diagnóstico — analisamos tráfego, loja ou processo comercial, ou
ensinamos a analisar. Cada página aponta para a outra.

## Redirecionamentos

- /loja-online/ → /loja-online-site/
- /sites/ → /loja-online-site/
- /formacao/ → /consultoria/

## Formulários

`enviar.php` envia para fernanda@agulhanumpalheiro.com e redireciona para
/obrigado/?origem=... A página de obrigado dispara o evento Lead no Pixel
374238462144981 com o nome da origem.

**Importante:** manter a estrutura de pastas — cada página é um `index.html` na sua pasta.
