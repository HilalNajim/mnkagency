<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$prenom  = trim(strip_tags($_POST['prenom']  ?? ''));
$nom     = trim(strip_tags($_POST['nom']     ?? ''));
$email   = trim(strip_tags($_POST['email']   ?? ''));
$projet  = trim(strip_tags($_POST['projet']  ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

if (!$prenom || !$nom || !$email || !$message) {
    echo json_encode(['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide.']);
    exit;
}

$to      = 'contact@mnk-agency.com';
$subject = "Nouveau contact MNK Agency — $prenom $nom";

$body  = "Nouveau message depuis le formulaire de contact.\n\n";
$body .= "Prénom  : $prenom\n";
$body .= "Nom     : $nom\n";
$body .= "Email   : $email\n";
$body .= "Projet  : " . ($projet ?: 'Non précisé') . "\n\n";
$body .= "Message :\n$message\n";

$headers  = "From: contact@mnk-agency.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = mail($to, $subject, $body, $headers);

echo json_encode([
    'success' => $sent,
    'message' => $sent ? 'Message envoyé.' : 'Échec de l\'envoi. Contactez-nous directement.'
]);
