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

// Classe principale del plugin
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
     * Oggetto handler
     */
    public $handler;
    
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
     * Costruttore della classe
     */
    private function __construct() {
        // Inclusione dei file base
        $this->include_files();
        
        // Inizializzazione degli oggetti
        $this->init_objects();
        
        // Registrazione dei hook
        $this->register_hooks();
        
        // Avvio del plugin
        $this->avvia();
    }
    
    /**
     * Include i file necessari
     */
    private function include_files() {
        try {
            require_once GIF_PLUGIN_DIR . 'includes/class-gif-loader.php';
            require_once GIF_PLUGIN_DIR . 'includes/class-gif-database.php';
            require_once GIF_PLUGIN_DIR . 'includes/class-gif-admin.php';
            require_once GIF_PLUGIN_DIR . 'includes/class-gif-ajax.php';
            require_once GIF_PLUGIN_DIR . 'includes/class-gif-settings.php';
            
            // Inclusione condizionale per il handler
            if (file_exists(GIF_PLUGIN_DIR . 'includes/class-gif-handler.php')) {
                require_once GIF_PLUGIN_DIR . 'includes/class-gif-handler.php';
            }
        } catch (Exception $e) {
            // Log dell'errore
            error_log('Errore nel caricamento dei file del plugin Gestione Incassi Fiere: ' . $e->getMessage());
        }
    }
    
    /**
     * Inizializza gli oggetti
     */
    private function init_objects() {
        try {
            $this->loader = new GIF_Loader();
            $this->database = new GIF_Database();
            $this->admin = new GIF_Admin($this->database);
            $this->ajax = new GIF_Ajax($this->database);
            $this->settings = new GIF_Settings();
            
            // Inizializzazione condizionale per il handler
            if (class_exists('GIF_Handler')) {
                $this->handler = new GIF_Handler($this->database);
            }
        } catch (Exception $e) {
            // Log dell'errore
            error_log('Errore nell\'inizializzazione degli oggetti del plugin Gestione Incassi Fiere: ' . $e->getMessage());
        }
    }
    
    /**
     * Registra gli hook
     */
    private function register_hooks() {
        // Registrazione dei hook di attivazione e disattivazione
        register_activation_hook(GIF_PLUGIN_FILE, array($this->database, 'attivazione_plugin'));
        register_deactivation_hook(GIF_PLUGIN_FILE, array($this->database, 'disattivazione_plugin'));
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

// Avvio del plugin in un try-catch per evitare errori fatali
try {
    gif_avvia_plugin();
} catch (Exception $e) {
    // Log dell'errore
    error_log('Errore nell\'avvio del plugin Gestione Incassi Fiere: ' . $e->getMessage());
    
    // Mostra un messaggio di errore nell'area admin
    function gif_error_notice() {
        echo '<div class="error"><p><strong>Errore nel plugin Gestione Incassi Fiere:</strong> Si è verificato un problema durante l\'inizializzazione del plugin. Controlla il log degli errori per maggiori dettagli.</p></div>';
    }
    add_action('admin_notices', 'gif_error_notice');
}