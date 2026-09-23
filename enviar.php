<?php
$destino = "fernanda@agulhanumpalheiro.com";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: /");
  exit;
}

function limpa($v) {
  return trim(str_replace(array("\r", "\n", "%0a", "%0d"), " ", strip_tags((string) $v)));
}

$nome     = limpa($_POST["nome"] ?? "");
$empresa  = limpa($_POST["empresa"] ?? "");
$email    = limpa($_POST["email"] ?? "");
$telefone = limpa($_POST["telefone"] ?? "");
$origem   = limpa($_POST["origem"] ?? "Site");

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $telefone === "") {
  header("Location: /?erro=1");
  exit;
}

$assunto = "Novo contacto do site (" . $origem . ")";

$corpo  = "Chegou um novo contacto pelo site.\n\n";
$corpo .= "Nome: "     . ($nome !== "" ? $nome : "(nao indicado)") . "\n";
if ($empresa !== "") $corpo .= "Empresa: " . $empresa . "\n";
$corpo .= "Email: "    . $email . "\n";
$corpo .= "Telefone: " . $telefone . "\n";
$corpo .= "Pagina: "   . $origem . "\n";
$corpo .= "Data: "     . date("d/m/Y H:i") . "\n";

$headers  = "From: Site Agulha num Palheiro <no-reply@agulhanumpalheiro.com>\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

mail($destino, $assunto, $corpo, $headers);

header("Location: /obrigado/?origem=" . urlencode($origem));
exit;
