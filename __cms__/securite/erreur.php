<?php

class Erreur {
	public static function fatale($message) {
		echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title>Erreur</title>
	</head>
	<body>
		<h1>Erreur</h1>
		<p>Désolé, une erreur est survenue. Contactez le créateur du site SVP :
		<a href="mailto:' . htmlspecialchars(Config::get('courriel_admin'))
		. '?subject=Erreur%20dans%20le%20programme%202010-moteur-site-simple&body='
		. htmlspecialchars(rawurlencode("Code de l'erreur : " . $message)) . '">'
		. htmlspecialchars(Config::get('courriel_admin'))
		. '</a>. Indiquez l\'erreur ci-dessous dans votre courriel.</p>
		<p><strong>' . htmlspecialchars($message) . '</strong></p>
	</body>
</html>';
		
		exit;
	}
}

?>