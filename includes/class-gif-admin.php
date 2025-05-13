<?php
/**
 * Classe per la gestione dell'area amministrativa
 *
 * @since 2.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class GIF_Admin {
    
    /**
     * Oggetto database
     */
    private $db;
    
    /**
     * Costruttore
     */
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Registrazione script e stili
     */
 /**
 * Modifica il metodo registra_script_stili nella classe GIF_Admin
 */
/**
 * Versione corretta del metodo registra_script_stili()
 * Sostituisci l'intero metodo nella classe GIF_Admin
 */
public function registra_script_stili($hook) {
    // Registra script solo nelle pagine del nostro plugin
    if (strpos($hook, 'gestione-incassi-fiere') === false) {
        return;
    }
    
    // Ottieni impostazioni per tema colore
    $tema_colore = $this->db->get_impostazione('tema_colore') ?: 'blue';
    
    // Registrazione stili
    wp_enqueue_style('gif-dashicons', admin_url('css/dashicons.min.css'));
    wp_enqueue_style('gif-admin-style', GIF_PLUGIN_URL . 'assets/css/admin.css', array(), GIF_PLUGIN_VERSION);
    wp_enqueue_style('gif-theme-style', GIF_PLUGIN_URL . 'assets/css/theme-' . $tema_colore . '.css', array('gif-admin-style'), GIF_PLUGIN_VERSION);
    
    // jQuery UI Datepicker
    wp_enqueue_style('jquery-ui-style', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_script('jquery-ui-datepicker');
    
    // Chart.js
    wp_enqueue_script('gif-chartjs', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js', array(), '3.7.0', true);
    
    // Sweet Alert 2
    wp_enqueue_style('gif-sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');
    wp_enqueue_script('gif-sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', array('jquery'), '11.0.0', true);
    
    // DataTables
    wp_enqueue_style('gif-datatables', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css');
    wp_enqueue_style('gif-datatables-responsive', 'https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css');
    
    wp_enqueue_script('gif-datatables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', array('jquery'), '1.11.5', true);
    wp_enqueue_script('gif-datatables-responsive', 'https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js', array('gif-datatables'), '2.2.9', true);
    
    // Prova a caricare i plugin Buttons solo se strettamente necessario
    if (isset($_GET['page']) && $_GET['page'] === 'gestione-incassi-fiere-elenco') {
        wp_enqueue_style('gif-datatables-buttons', 'https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css');
        wp_enqueue_script('gif-datatables-buttons', 'https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js', array('gif-datatables'), '2.2.2', true);
        wp_enqueue_script('gif-datatables-buttons-html5', 'https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js', array('gif-datatables-buttons'), '2.2.2', true);
        wp_enqueue_script('gif-datatables-buttons-print', 'https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js', array('gif-datatables-buttons'), '2.2.2', true);
    }
    
    // Registrazione script principale
    wp_enqueue_script('gif-admin-script', GIF_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'jquery-ui-datepicker', 'gif-sweetalert2', 'gif-datatables'), GIF_PLUGIN_VERSION, true);
    
    // Localizzazione per JavaScript
    $localizzazione = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('gif_nonce'),
        'tema_colore' => $tema_colore,
        'impostazioni' => $this->db->get_tutte_impostazioni(),
        'testi' => array(
            'conferma_eliminazione' => __('Sei sicuro di voler eliminare questa fiera?', 'gestione-incassi-fiere'),
            'conferma_si' => __('Sì, elimina', 'gestione-incassi-fiere'),
            'conferma_no' => __('Annulla', 'gestione-incassi-fiere'),
            'eliminazione_successo' => __('Fiera eliminata con successo!', 'gestione-incassi-fiere'),
            'eliminazione_errore' => __('Errore durante l\'eliminazione', 'gestione-incassi-fiere'),
            'salvataggio_successo' => __('Dati salvati con successo!', 'gestione-incassi-fiere'),
            'salvataggio_errore' => __('Errore durante il salvataggio', 'gestione-incassi-fiere')
        ),
        'formati' => array(
            'valuta' => $this->db->get_impostazione('valuta') ?: '€'
        )
    );
    
    wp_localize_script('gif-admin-script', 'gif_vars', $localizzazione);
}

    /**
     * Aggiunta menu nell'area amministrativa
     */
	 
	/**
	* Pagina vuota per l'eliminazione
	*/
	public function dummy_page() {
		// Non fa nulla, serve solo per il routing
	}	 
	 
    public function aggiungi_menu_admin() {
        // Menu principale
        add_menu_page(
            __('Gestione Incassi Fiere', 'gestione-incassi-fiere'),
            __('Incassi Fiere', 'gestione-incassi-fiere'),
            'manage_options',
            'gestione-incassi-fiere',
            array($this, 'pagina_principale'),
            'dashicons-chart-area',
            30
        );
		
        // Pagina nascosta per l'eliminazione
		add_submenu_page(
			null, // Non mostrarla nel menu
			__('Elimina Fiera', 'gestione-incassi-fiere'),
			__('Elimina Fiera', 'gestione-incassi-fiere'),
			'manage_options',
			'gestione-incassi-fiere-elimina',
			array($this, 'dummy_page')
		);
		
        // Sottomenu
        add_submenu_page(
            'gestione-incassi-fiere',
            __('Dashboard', 'gestione-incassi-fiere'),
            __('Dashboard', 'gestione-incassi-fiere'),
            'manage_options',
            'gestione-incassi-fiere',
            array($this, 'pagina_principale')
        );
        
        add_submenu_page(
            'gestione-incassi-fiere',
            __('Elenco Fiere', 'gestione-incassi-fiere'),
            __('Elenco Fiere', 'gestione-incassi-fiere'),
            'manage_options',
            'gestione-incassi-fiere-elenco',
            array($this, 'pagina_elenco')
        );
        
        add_submenu_page(
            'gestione-incassi-fiere',
            __('Aggiungi Nuova Fiera', 'gestione-incassi-fiere'),
            __('Aggiungi Nuova', 'gestione-incassi-fiere'),
            'manage_options',
            'gestione-incassi-fiere-nuova',
            array($this, 'pagina_aggiungi')
        );
        
        add_submenu_page(
            'gestione-incassi-fiere',
            __('Statistiche Fiere', 'gestione-incassi-fiere'),
            __('Statistiche', 'gestione-incassi-fiere'),
            'manage_options',
            'gestione-incassi-fiere-stats',
            array($this, 'pagina_statistiche')
        );
        
        add_submenu_page(
            'gestione-incassi-fiere',
            __('Impostazioni', 'gestione-incassi-fiere'),
            __('Impostazioni', 'gestione-incassi-fiere'),
            'manage_options',
            'gestione-incassi-fiere-impostazioni',
            array($this, 'pagina_impostazioni')
        );
		/**
 * Aggiungi questa pagina nascosta alla funzione aggiungi_menu_admin() della classe GIF_Admin
 */
// Pagina nascosta per l'eliminazione
add_submenu_page(
    null, // Non mostrarla nel menu
    __('Elimina Fiera', 'gestione-incassi-fiere'),
    __('Elimina Fiera', 'gestione-incassi-fiere'),
    'manage_options',
    'gestione-incassi-fiere-elimina',
    array($this, 'dummy_page')
);

    }
    
    /**
     * Pagina dashboard principale
     */
    public function pagina_principale() {
        // Ottieni le statistiche generali
        $stats = $this->db->get_statistiche_generali();
        
        // Ottieni le ultime 5 fiere
        $ultime_fiere = $this->db->get_fiere('data_fiera', 'DESC', 5);
        
        // Ottieni la miglior e peggior fiera
        $miglior_fiera = $this->db->get_miglior_fiera();
        $peggior_fiera = $this->db->get_peggior_fiera();
        
        // Ottieni dati per grafico degli ultimi 12 mesi
        $dati_grafico = $this->db->get_dati_grafico_annuale(1);
        
        // Ottieni valuta
        $valuta = $this->db->get_impostazione('valuta') ?: '€';
        
        // Output della pagina
        include(GIF_PLUGIN_DIR . 'templates/dashboard.php');
    }
    
    /**
     * Pagina elenco fiere
     */
    public function pagina_elenco() {
        // Ottieni tutte le fiere
        $fiere = $this->db->get_fiere();
        
        // Ottieni statistiche generali per i totali
        $stats = $this->db->get_statistiche_generali();
        
        // Ottieni valuta
        $valuta = $this->db->get_impostazione('valuta') ?: '€';
        
        // Output della pagina
        include(GIF_PLUGIN_DIR . 'templates/elenco-fiere.php');
    }
    
    /**
     * Pagina per aggiungere o modificare una fiera
     */
    public function pagina_aggiungi() {
        // Verifica se stiamo modificando una fiera esistente
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $fiera = null;
        
        if ($id > 0) {
            $fiera = $this->db->get_fiera($id);
        }
        
        $is_edit = ($fiera !== null);
        
        // Ottieni impostazioni
        $percentuale_iva = floatval($this->db->get_impostazione('percentuale_iva'));
        $percentuale_tasse = floatval($this->db->get_impostazione('percentuale_tasse'));
        $valuta = $this->db->get_impostazione('valuta') ?: '€';
        
        // Output della pagina
        include(GIF_PLUGIN_DIR . 'templates/form-fiera.php');
    }
    
    /**
     * Pagina delle statistiche
     */
    public function pagina_statistiche() {
        // Ottieni statistiche generali
        $stats = $this->db->get_statistiche_generali();
        
        // Ottieni le fiere più redditizie
        $top_fiere = $this->db->get_fiere_piu_redditizie();
        
        // Ottieni le fiere meno redditizie
        $bottom_fiere = $this->db->get_fiere_meno_redditizie();
        
        // Ottieni dati per grafico annuale
        $dati_annuali = $this->db->get_dati_grafico_annuale();
        
        // Ottieni valuta
        $valuta = $this->db->get_impostazione('valuta') ?: '€';
        
        // Prepara i dati per i grafici
        $mesi = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');
        $dati_grafico = array();
        
        foreach ($dati_annuali as $dato) {
            $anno = $dato->anno;
            $mese = $dato->mese;
            $mese_nome = $mesi[$mese - 1];
            $periodo = $mese_nome . ' ' . $anno;
            
            $dati_grafico[] = array(
                'periodo' => $periodo,
                'incasso' => floatval($dato->incasso_totale),
                'guadagno' => floatval($dato->guadagno_netto)
            );
        }
        
        // Output della pagina
        include(GIF_PLUGIN_DIR . 'templates/statistiche.php');
    }
    
    /**
     * Pagina delle impostazioni
     */
    public function pagina_impostazioni() {
        // Ottieni tutte le impostazioni
        $impostazioni = $this->db->get_tutte_impostazioni();
        
        // Output della pagina
        include(GIF_PLUGIN_DIR . 'templates/impostazioni.php');
    }
}