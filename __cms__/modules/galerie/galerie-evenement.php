<?php

function action($chemin, $action, $paramètres) {
	if ($action == "anuler") {
		return redirect($chemin);
	} else if ($action == "nouvelle_page") {
		// Créer la nouvelle page avec les valeurs par défaut.
		// return Redirect vers cette nouvelle page.
	} else if ($action == "supprimer") {
		// Supprimer cette page.
		// return Redirect vers la page parente.
	} else {
		if (is_set($paramètres["titre"])) {
			// renomer la page
		}
		if (is_set($paramètres["description"])) {
			// set_prop($chemin, "description", $paramètres["description"]);
		}
/*		if (is_set($paramètres[""])) {
		}*/
		
		if (is_set($paramètres["vue"])) {
			self::vue($chemin, $paramètres["vue"]);
		} else {
			self::vue($chemin);
		}
	}
}

function vue($chemin, $vue = "normal") {
	if ($vue == "normal") {
        $ret = '';
		if (vérifier_permission($chemin, "set_prop", get_utilisateur())) {
			// afficher le <input type="text" /> du titre
			// afficher le textarea de la description
		} else {
			$ret .= "<h1>" . get_prop($chemin, "titre") . "</h1>";
			$ret .= "<p>" . get_prop($chemin, "description") . "</p>";
		}
		if (vérifier_permission($chemin, "nouvelle_page", get_utilisateur())) {
			// afficher le lien "Nouvelle image"
		}
		if (vérifier_permission($chemin, "supprimer", get_utilisateur())) {
			// afficher le lien "Supprimer"
		}
        $ret .= '<ul class="galerie evenement">';
        foreach (stockage::liste_enfants($chemin) as $k) {
            $ret .= '<li><a href="' . chemin::vers_url($k) . '">' . modules::vue($k, 'miniature') . '</a></li>'; // TODO : escape l'url !
        }
        $ret .= '</ul>';
		return "Vue normale de la page.";
	} else if ($vue == "miniature") {
			$enfants = stockage::liste_enfants($chemin);
			if (is_set($enfants[0])) return modules::vue($enfants[0], 'miniature');
			else return "Aucune<br/>photo";
	}
}

?>