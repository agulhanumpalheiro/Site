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

// --- API de Conversões da Meta (Lead pelo servidor) ---
$eventId = bin2hex(random_bytes(8));
$tokenFile = __DIR__ . "/capi-config.php";
if (is_file($tokenFile)) {
  $token = include $tokenFile;
  if (is_string($token) && $token !== "" && strpos($token, "COLA_AQUI") === false) {
    $tel = preg_replace('/\D/', '', $telefone);
    if (strlen($tel) === 9) $tel = "351" . $tel;
    $userData = array(
      "em" => array(hash("sha256", strtolower(trim($email)))),
      "ph" => array(hash("sha256", $tel)),
      "client_ip_address" => $_SERVER["REMOTE_ADDR"] ?? "",
      "client_user_agent" => $_SERVER["HTTP_USER_AGENT"] ?? "",
    );
    if ($nome !== "") {
      $partes = preg_split('/\s+/', trim($nome));
      $userData["fn"] = array(hash("sha256", mb_strtolower($partes[0], "UTF-8")));
      if (count($partes) > 1) $userData["ln"] = array(hash("sha256", mb_strtolower(end($partes), "UTF-8")));
    }
    if (!empty($_COOKIE["_fbp"])) $userData["fbp"] = $_COOKIE["_fbp"];
    if (!empty($_COOKIE["_fbc"])) $userData["fbc"] = $_COOKIE["_fbc"];

    // Lead e Registo concluído (CompleteRegistration), com o mesmo event_id do Pixel na página /obrigado/.
    $eventos = array();
    foreach (array("Lead", "CompleteRegistration") as $nomeEvento) {
      $eventos[] = array(
        "event_name" => $nomeEvento,
        "event_time" => time(),
        "event_id" => $eventId,
        "action_source" => "website",
        "event_source_url" => $_SERVER["HTTP_REFERER"] ?? "https://agulhanumpalheiro.com/",
        "user_data" => $userData,
        "custom_data" => array("content_name" => $origem),
      );
    }
    $payload = array("data" => $eventos);

    $ch = curl_init("https://graph.facebook.com/v26.0/374238462144981/events?access_token=" . urlencode($token));
    curl_setopt_array($ch, array(
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => json_encode($payload),
      CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 5,
    ));
    curl_exec($ch);
    curl_close($ch);
  }
}

header("Location: /obrigado/?origem=" . urlencode($origem) . (isset($eventId) ? "&eid=" . $eventId : ""));
exit;
