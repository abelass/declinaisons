<?php
/**
 * Plugin Déclinaisons Produit
 * (c) 2012 Rainer Müller
 * Licence GNU/GPL
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


function shop_declinaisons_recuperer_fond($flux){
    $fond=$flux['args']['fond'];
    $contexte=$flux['args']['contexte'];

    // inclure le champ déclinaison
    if ($fond == 'formulaires/prix'){
        include_spip('inc/config');
        $afficher_prix =recuperer_fond('formulaires/inc-prix_affichage',$contexte);
        $declinaison_champs=recuperer_fond('formulaires/inc-prix_champ',$contexte);
        
        $patterns = array('/<!--fini champs!-->/','/<div class="liste prix">(.*?)<\/div>/ims');
        $replacements = array('<!--fini champs!-->'.$declinaison_champs,$afficher_prix);                        
       $rep= preg_replace($patterns,$replacements,$flux['data']['texte'],1);
        
        $flux['data']['texte'] = $rep;
        }
        
    
    return $flux;
}

function shop_declinaisons_post_insertion($flux){
    // Après insertion d'une commande "encours" et s'il y a un panier en cours
    if (
        $flux['args']['table'] == 'spip_commandes'
        and ($id_commande = intval($flux['args']['id_objet'])) > 0
        and $flux['data']['statut'] == 'encours'
    ){
        // On récupère le contenu du panier
        $details = sql_allfetsel(
            '*',
            'spip_commandes_details',
            'id_commande = '.$id_commande
        );
        
        // On rajoute le détail
        if ($details){
            foreach($details as $emplette){
                $id_declinaison=sql_getfetsel('id_declinaison','spip_prix_objets','id_prix='.$emplette['id_objet']);
                sql_updateq(
                    'spip_commandes_details',
                    array(
                        'descriptif' => recuperer_fond('formulaires/inc-panier-description-emplette',array('id_objet'=>$emplette['id_objet'])),
                    ),
                    'id_commandes_detail='.$emplette['id_commandes_detail']
                );
            }
        }
    }
    
    return $flux;
}
?>