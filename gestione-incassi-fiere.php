<?php
/**
 * Plugin Name: Gestione Incassi Fiere
 * Description: Plugin per la gestione e il calcolo degli incassi delle fiere con dettaglio delle spese e calcolo del guadagno netto.
 * Version: 2.0
 * Author: Claude
 * Text Domain: gestione-incassi-fiere
 */

// Protezione contro accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

// Definizione costanti del plugin
define('GIF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('GIF_PLUGIN_URL', plugin_dir_url(__FILE__));
define('GIF_PLUGIN_FILE', __FILE__);
define('GIF_PLUGIN_VERSION', '2.0');

/**
 * Modifiche al file principale gestione-incassi-fiere.php
 */

// Aggiungi queste righe per includere e inizializzare la classe GIF_Handler

require_once GIF_PLUGIN_DIR . 'includes/class-gif-loader.php';
require_once GIF_PLUGIN_DIR . 'includes/class-gif-admin.php';
require_once GIF_PLUGIN_DIR . 'includes/class-gif-database.php';
require_once GIF_PLUGIN_DIR . 'includes/class-gif-ajax.php';
require_once GIF_PLUGIN_DIR . 'includes/class-gif-settings.php';
require_once GIF_PLUGIN_DIR . 'includes/class-gif-handler.php'; // Aggiunto nuovo file

/**
 * Classe principale del plugin
 */
class Gestione_Incassi_Fiere {
    
    /**
     * Istanza singleton
     */
    private static $instance = null;
    
    /**
     * Oggetto loader
     */
    public $loader;
    
    /**
     * Oggetto admin
     */
    public $admin;
    
    /**
     * Oggetto database
     */
    public $database;
    
    /**
     * Oggetto ajax
     */
    public $ajax;
    
    /**
     * Oggetto settings
     */
    public $settings;
    
    /**
     * Ottieni l'istanza singleton
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
/**
 * Modifiche alla funzione __construct() della classe principale
 */
private function __construct() {
    // Inizializzazione degli oggetti
    $this->loader = new GIF_Loader();
    $this->database = new GIF_Database();
    $this->admin = new GIF_Admin($this->database);
    $this->ajax = new GIF_Ajax($this->database);
    $this->settings = new GIF_Settings();
    $this->handler = new GIF_Handler($this->database); // Aggiunta nuova istanza
    
    // Registrazione dei hook di attivazione e disattivazione
    register_activation_hook(GIF_PLUGIN_FILE, array($this->database, 'attivazione_plugin'));
    register_deactivation_hook(GIF_PLUGIN_FILE, array($this->database, 'disattivazione_plugin'));
    
    // Avvio del plugin
    $this->avvia();
}
    
    /**
     * Avvia il plugin
     */
    private function avvia() {
        // Registra gli hook per l'area admin
        $this->loader->add_action('admin_menu', $this->admin, 'aggiungi_menu_admin');
        $this->loader->add_action('admin_enqueue_scripts', $this->admin, 'registra_script_stili');
        
        // Registra gli hook per AJAX
        $this->loader->add_action('wp_ajax_salva_fiera', $this->ajax, 'ajax_salva_fiera');
        $this->loader->add_action('wp_ajax_elimina_fiera', $this->ajax, 'ajax_elimina_fiera');
        $this->loader->add_action('wp_ajax_salva_impostazioni', $this->ajax, 'ajax_salva_impostazioni');
        
        // Esegue gli hook registrati
        $this->loader->run();
    }
}

// Avvia il plugin
function gif_avvia_plugin() {
    return Gestione_Incassi_Fiere::get_instance();
}




// Avvio del plugin
gif_avvia_plugin();