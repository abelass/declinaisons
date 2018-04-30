<?php
/**
 * Utilisations de pipelines par Déclinaisons Prix
 *
 * @plugin     Déclinaisons Prix
 * @copyright  2012 - 2018
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Promotions_commandes\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION'))
	return;


/**
 * Modifie le résultat de compilation d’un squelette
 *
 * @pipeline recuperer_fond
 *
 * @param array $flux
 *        	Données du pipeline
 * @return array
 */
function declinaisons_recuperer_fond($flux) {
	$fond = $flux['args']['fond'];
	$contexte = $flux['args']['contexte'];

	// inclure le champ déclinaison
	if ($fond == 'formulaires/prix') {
		include_spip('inc/config');
		$afficher_prix = recuperer_fond('formulaires/inc-prix_affichage', $contexte);
		$declinaison_champs = recuperer_fond('formulaires/inc-prix_champ', $contexte);

		$patterns = array(
			'/<!--fini champs!-->/',
			'/<div class="liste prix">(.*?)<\/div>/ims'
		);
		$replacements = array(
			'<!--fini champs!-->' . $declinaison_champs,
			$afficher_prix
		);
		$rep = preg_replace($patterns, $replacements, $flux['data']['texte'], 1);

		$flux['data']['texte'] = $rep;
	}

	return $flux;
}

/**
 * Modifier le tableau retourné par la fonction charger d’un formulaire CVT.
 *
 * @pipeline formulaire_charger
 *
 * @param array $flux
 *        	Données du pipeline
 * @return array
 */
function declinaisons_formulaire_charger($flux) {
	$form = $flux['args']['form'];

	// cré un contact si pas encore existant
	if ($form == 'prix') {
		$flux['data']['_hidden'] .= '<input type="hidden" name="objet_titre" value="declinaison">';
	}
	return ($flux);
}

/**
 * Declare l'object pour le Plugin shop https://github.com/abelass/shop.
 *
 * @pipeline shop_objets
 *
 * @param array $flux
 *        	Données du pipeline
 * @return array
 */
function declinaisons_shop_objets($flux) {
	$flux['data']['declinaisons'] = array(
		'action' => 'declinaisons',
		'nom_action' => _T('declinaison:titre_declinaisons'),
		'icone' => 'declinaisons-16.png'
	);

	return $flux;
}
