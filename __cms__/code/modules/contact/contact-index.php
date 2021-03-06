<?php

class ContactIndex {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "nouvelle_page") {
			// SECURITE : On ne doit PAS pouvoir modifier dernier_numero arbitrairement
			// CONCURENCE : Faire un lock quelque part...
			$numéro_contact = 1 + Stockage::get_prop($chemin, "dernier_numero");
			Stockage::set_prop($chemin, "dernier_numero", $numéro_contact);
			$np = Stockage::nouvelle_page($chemin, "Contact" . $numéro_contact, "contact-contact");
			Stockage::set_prop($np, "proprietaire", Authentification::get_utilisateur());
			Stockage::set_prop($np, "nom", "Dupondt");
			Stockage::set_prop($np, "prenom", "Jean");
			Stockage::set_prop($np, "description", "");
			enregistrer_nouveaute($np);
			return new Page($np, '', "redirect");
		} else {
			if (isset($paramètres["description"])) {
				Stockage::set_prop($chemin, "description", $paramètres["description"]);
			}
			
			if (isset($paramètres["titre"])) {
				Stockage::set_prop($chemin, "titre", $paramètres["titre"]);
			}
			
			if (isset($paramètres["vue"])) {
				return self::vue($chemin, $paramètres["vue"]);
			} else {
				return self::vue($chemin);
			}
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			$ret = '';
			
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<form class="contact infos" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<h2><input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" /></h2>';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "description"), "description");
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= '<h2>' . Stockage::get_prop($chemin, "titre") . '</h2>';
				$ret .= '<p class="contact index description affichage">' . Stockage::get_prop($chemin, "description") . '</p>';
			}
			
			$ret .= '<div class="contact liste-contacts index">';
			$ret .= '<ul>';
			
			if (Permissions::vérifier_permission($chemin, "nouvelle_page", Authentification::get_utilisateur())) {
				$ret .= '<li>';
				$ret .= '<div class="titre">';
				
				$ret .= '<form class="contact nouvelle_page" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<p>';
				$ret .= '<input type="hidden" name="action" value="nouvelle_page"/>';
				$ret .= '<input type="submit" value="Nouveau contact"/>';
				$ret .= '</p>';
				$ret .= '</form>';
				
				$ret .= '</div>';
				$ret .= '</li>';
			}
			
	        foreach (stockage::liste_enfants($chemin) as $k) {
	            $ret .= '<li>' . Modules::vue($k)->contenu . '</li>';
	        }
			
			$ret .= '</ul>';
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		}
	}
}

Modules::enregister_module("ContactIndex", "contact-index", "vue", "titre description");

?>
