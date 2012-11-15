<?php

if (!defined("_ECRIRE_INC_VERSION")) return;


function formulaires_panier_declinaison_charger_dist($id_objet_produit,$objet_produit='article'){
    
   $sql=sql_select('*','spip_prix_objets','id_objet='.$id_objet_produit.' AND objet='.sql_quote($objet_produit));
   
   $declinaisons=array();
   
   while($data=sql_fetch($sql)){
       unset($data[titre]);
       if($data['prix_ht']!=0.00){
        $data['prix'] = $data['prix_ht'];          
        $data['taxe'] = _T('shop:prix_ht');
       }
       else{
         $data['prix'] = $data['prix']; 
         $data['taxe'] = _T('shop:prix');      
       }
           
       if($data['id_declinaison'])$data[titre]=sql_getfetsel('titre','spip_declinaisons','id_declinaison='.$data['id_declinaison']);
       $declinaisons[]=$data;
       
        }

   $valeurs=array(
    'objet'=>'prix',
    'id_objet'=>'',
    'declinaisons'=>$declinaisons,
    'id_prix_objet'=>'',
    'retour'=>'');
    

   

	return $valeurs;			
}

function formulaires_panier_declinaison_traiter_dist($id_objet,$objet='article'){
        
  refuser_traiter_formulaire_ajax();
  $retour=generer_action_auteur('remplir_panier','prix-'._request('id_prix_objet'),_request('retour'),true);
   header("location: $retour");

    return $valeurs;
}

?>