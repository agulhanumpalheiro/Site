<?php
// Recebe o briefing do cliente e envia-o por email, campo a campo.
$destino = "fernanda@agulhanumpalheiro.com";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: /briefing/");
  exit;
}

// Campo escondido: se vier preenchido, foi um robô.
if (!empty($_POST["website"])) {
  header("Location: /briefing/?ok=1");
  exit;
}

function limpa($v) {
  if (is_array($v)) return implode(", ", array_map("limpa", $v));
  return trim(strip_tags((string) $v));
}
function linha($v) {
  return str_replace(array("\r", "\n", "%0a", "%0d"), " ", limpa($v));
}

$empresa = linha($_POST["empresa"] ?? "");
$email   = linha($_POST["email"] ?? "");
if ($empresa === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header("Location: /briefing/?erro=1");
  exit;
}

// Ordem e nomes das perguntas no email.
$perguntas = array(
  "1. Empresa" => array(
    "empresa" => "Empresa", "responsavel" => "Responsável", "email" => "Email", "telefone" => "Telefone",
    "site" => "Site", "instagram" => "Instagram", "facebook" => "Facebook", "nif" => "NIF", "morada" => "Morada",
  ),
  "2. Negócio" => array(
    "descricao" => "O que faz", "servicos" => "Produtos/serviços principais", "preco" => "Preço médio",
    "zona" => "Zona onde trabalha", "diferenca" => "O que a distingue", "concorrentes" => "Concorrentes",
  ),
  "3. Cliente ideal" => array(
    "cliente" => "Quem é", "problema" => "Problema que resolve", "objecoes" => "Dúvidas antes de comprar",
    "porque" => "Porque escolhem a empresa",
  ),
  "4. Objetivo" => array(
    "objetivo" => "Objetivo principal", "oferta" => "Oferta/promoção", "orcamento" => "Orçamento mensal em anúncios",
    "inicio" => "Quando quer começar", "servicos_pedidos" => "Serviços pretendidos",
  ),
  "5. Marca" => array(
    "cores" => "Cores", "tom" => "Tom de comunicação", "ficheiros" => "Logótipo e fotos (link)",
    "referencias" => "Marcas que admira", "evitar" => "O que não quer",
  ),
  "6. Provas" => array(
    "testemunhos" => "Testemunhos", "numeros" => "Números e resultados",
  ),
  "7. Acessos" => array(
    "acessos" => "Acessos já dados", "acessos_notas" => "Notas sobre acessos",
  ),
  "8. Notas" => array(
    "notas" => "Observações",
  ),
);

$corpo = "Chegou um briefing novo de " . $empresa . ".\n";
foreach ($perguntas as $titulo => $campos) {
  $corpo .= "\n==== " . $titulo . " ====\n";
  foreach ($campos as $chave => $rotulo) {
    $valor = limpa($_POST[$chave] ?? "");
    $corpo .= $rotulo . ": " . ($valor !== "" ? $valor : "-") . "\n";
  }
}
$corpo .= "\nEnviado em " . date("d/m/Y H:i") . "\n";

$headers  = "From: Briefing Agulha num Palheiro <no-reply@agulhanumpalheiro.com>\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$assunto = "=?UTF-8?B?" . base64_encode("Briefing: " . $empresa) . "?=";
$ok = mail($destino, $assunto, $corpo, $headers);

header("Location: /briefing/?" . ($ok ? "ok=1" : "erro=2"));
exit;
