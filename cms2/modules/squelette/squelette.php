<?php

class mSquelette extends Page {
	// Trouver un moyen pour que mSquelette soit appellé après avoir généré la page, pour qu'il puisse l'emballer.
	
	protected static $ressources_dynamiques = array("c_css_principal text/css");
	public function res_c_css_principal() {
		// mettre bout à bout tous les CSS ?
		niy("res_c_css_principal");
	}
}

?>