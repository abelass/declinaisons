<?php

if (!defined("_ECRIRE_INC_VERSION")) return;


function formulaires_prix_declinaison_charger_dist($id_objet,$objet='article'){
    include_spip('inc/config');

	$devises_dispos =lire_config('shop/devises');
	
	
	// Devise par défaut si rien configuré
	if(!$devises_dispos)$devises_dispos=array('0'=>'EUR');
	$devises_choisis =array();	
	$prix_choisis =array();		
	$d=sql_select('code_devise,objet,id_objet,prix_ht,id_prix,id_declinaison','spip_prix','id_objet='.$id_objet.' AND objet ='.sql_quote($objet));
	
	//établit les devises diponible moins ceux déjà utilisés
		
	while($row=sql_fetch($d)){
		//$devises_choisis[$row['code_devise']] = $row['code_devise'];

		$prix_choisis[]=$row;
			
		}
		
	
		
	$devises = array_diff($devises_dispos,$devises_choisis);
	
		$valeurs = array(
		'prix_choisis'=>$prix_choisis,
	    'id_declinaison'=>'',
		'devises'=>$devises,	
		'code_devise'=>'',
		'prix_ht'=>'',									
		);

	return $valeurs;			
}


function formulaires_prix_declinaison_verifier_dist($id_objet,$objet='article'){
    $valeurs=array();
	foreach(array('prix','code_devise') as $obligatoire)
	
	if (!_request($obligatoire)) $valeurs[$obligatoire] = _T('encheres:champ_obligatoire');	
		
    return $valeurs; // si c'est vide, traiter sera appele, sinon le formulaire sera resoumis
}


function formulaires_prix_declinaison_traiter_dist($id_objet,$objet='article'){
	$valeurs=array(
		'id_objet'=>$id_objet,
		'objet'=>$objet,	
		'prix_ht' => _request('prix'),
		'code_devise' => _request('code_devise'),
        'id_declinaison' => _request('id_declinaison'),		
		);
	$id_prix=sql_insertq('spip_prix', $valeurs);
    return $valeurs;
}

?>