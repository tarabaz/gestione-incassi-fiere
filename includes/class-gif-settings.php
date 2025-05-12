<?php
/**
 * Classe per la gestione delle impostazioni
 *
 * @since 2.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class GIF_Settings {
    
    /**
     * Costruttore
     */
    public function __construct() {
        // Per ora questa classe è un semplice placeholder per futuri sviluppi
        // La gestione delle impostazioni attualmente avviene tramite la classe GIF_Database
    }
    
    /**
     * Ottieni temi colore disponibili
     */
    public static function get_temi_colore() {
        return array(
            'blue' => __('Blu', 'gestione-incassi-fiere'),
            'green' => __('Verde', 'gestione-incassi-fiere'),
            'purple' => __('Viola', 'gestione-incassi-fiere'),
            'orange' => __('Arancione', 'gestione-incassi-fiere'),
            'red' => __('Rosso', 'gestione-incassi-fiere')
        );
    }
}