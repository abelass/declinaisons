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


?>