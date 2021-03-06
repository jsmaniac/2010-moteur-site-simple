<?php

class ArticlesIndex {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "nouvelle_page") {
			$np = Stockage::nouvelle_page($chemin, "Nouvel article", "articles-article");
			Stockage::set_prop($np, "proprietaire", Authentification::get_utilisateur());
			Stockage::set_prop($np, "titre", "Nouvel article");
			Stockage::set_prop($np, "contenu", "Bla bla bla.");
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
				$ret .= '<form class="articles infos" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<h2><input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" /></h2>';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "description"), "description");
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= '<h2>' . Stockage::get_prop($chemin, "titre") . '</h2>';
				$ret .= '<p class="articles index description affichage">' . Stockage::get_prop($chemin, "description") . '</p>';
			}
			
			$ret .= '<div class="articles liste-articles index">';
			$ret .= '<ul>';
			
			if (Permissions::vérifier_permission($chemin, "nouvelle_page", Authentification::get_utilisateur())) {
				$ret .= '<li>';
				$ret .= '<div class="titre">';
				
				$ret .= '<form class="articles nouvelle_page" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<p>';
				$ret .= '<input type="hidden" name="action" value="nouvelle_page"/>';
				$ret .= '<input type="submit" value="Nouvel article"/>';
				$ret .= '</p>';
				$ret .= '</form>';
				
				$ret .= '</div>';
				$ret .= '</li>';
			}
			
			foreach (Stockage::liste_enfants($chemin) as $k) { // TODO : trier par numéro !
				$mini = Modules::vue($k, 'miniature');
				$ret .= '<li>';
				// TODO : mettre une ancre "#message<numéro>"
				$ret .= '<a href="' . $k->get_url() . '">'; // TODO : escape l'url !
				$ret .= '<span class="titre">';
				$ret .= $mini->titre;
				$ret .= '</span>';
				$ret .= '<p class="contenu">';
				$ret .= $mini->contenu;
				$ret .= '</p>';
				$ret .= '</a>';
				$ret .= '</li>';
			}
			
			$ret .= '</ul>';
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		}
	}
}

Modules::enregister_module("ArticlesIndex", "articles-index", "vue", "titre description");

?>
