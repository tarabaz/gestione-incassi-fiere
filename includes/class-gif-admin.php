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
 * Modifica al metodo registra_script_stili della classe GIF_Admin
 * Assicurati che il datatables-init.js sia caricato solo nella pagina elenco fiere
 * e che tutti gli script DataTables siano in ordine corretto
 */

public function registra_script_stili($hook) {
    // Registra script solo nelle pagine del nostro plugin
    if (strpos($hook, 'gestione-incassi-fiere') === false) {
        return;
    }
    
    // Verifica se siamo nella pagina elenco fiere
    $is_elenco_fiere = isset($_GET['page']) && $_GET['page'] === 'gestione-incassi-fiere-elenco';
    
    // Ottieni impostazioni per tema colore
    $tema_colore = $this->db->get_impostazione('tema_colore') ?: 'blue';
    
    // Registrazione stili
    wp_enqueue_style('gif-dashicons', admin_url('css/dashicons.min.css'));
    wp_enqueue_style('gif-admin-style', GIF_PLUGIN_URL . 'assets/css/admin.css', array(), GIF_PLUGIN_VERSION);
    wp_enqueue_style('gif-theme-style', GIF_PLUGIN_URL . 'assets/css/theme-' . $tema_colore . '.css', array('gif-admin-style'), GIF_PLUGIN_VERSION);
    
    // jQuery UI Datepicker
    wp_enqueue_style('jquery-ui-style', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_script('jquery-ui-datepicker');
    
    // Chart.js solo nelle pagine necessarie (dashboard e statistiche)
    if (isset($_GET['page']) && ($_GET['page'] === 'gestione-incassi-fiere' || $_GET['page'] === 'gestione-incassi-fiere-stats')) {
        wp_enqueue_script('gif-chartjs', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js', array(), '3.7.0', true);
    }
    
    // Sweet Alert 2
    wp_enqueue_style('gif-sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');
    wp_enqueue_script('gif-sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', array('jquery'), '11.0.0', true);
    
    // DataTables solo nella pagina elenco fiere
    if ($is_elenco_fiere) {
        // Versione basic di DataTables
        wp_enqueue_style('gif-datatables', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css');
        wp_enqueue_script('gif-datatables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', array('jquery'), '1.11.5', true);
        
        // Script di inizializzazione personalizzato (caricato dopo tutti gli altri script)
        wp_enqueue_script('gif-datatables-init', GIF_PLUGIN_URL . 'assets/js/datatables-init.js', array('jquery', 'gif-datatables'), GIF_PLUGIN_VERSION, true);
    }
    
    // Registrazione script principale
    wp_enqueue_script('gif-admin-script', GIF_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'jquery-ui-datepicker', 'gif-sweetalert2'), GIF_PLUGIN_VERSION, true);
    
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
        ),
        'is_elenco_fiere' => $is_elenco_fiere
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
 * Modifica alla funzione pagina_statistiche() nella classe GIF_Admin
 * Sostituisci o modifica questa parte
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
    $labels = array();
    $incassi = array();
    $guadagni = array();
    
    if (!empty($dati_annuali)) {
        foreach ($dati_annuali as $dato) {
            $anno = $dato->anno;
            $mese = $dato->mese;
            $mese_nome = $mesi[$mese - 1];
            $periodo = $mese_nome . ' ' . $anno;
            
            $labels[] = $periodo;
            $incassi[] = floatval($dato->incasso_totale);
            $guadagni[] = floatval($dato->guadagno_netto);
            
            $dati_grafico[] = array(
                'periodo' => $periodo,
                'incasso' => floatval($dato->incasso_totale),
                'guadagno' => floatval($dato->guadagno_netto)
            );
        }
    }
    
    // Mostra informazioni di debug se siamo in modalità sviluppo
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $this->debugGrafici();
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
	
	/**
 * Aggiungere questa funzione debugGrafici() alla classe GIF_Admin
 * nel file includes/class-gif-admin.php
 */
private function debugGrafici() {
    global $wpdb;
    $tabella_fiere = $this->db->get_tabella_fiere();
    
    // Debug informazioni sul database
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$tabella_fiere}");
    $fiere = $wpdb->get_results("SELECT id, nome_fiera, data_fiera, incasso_totale, incasso_contanti, incasso_pos, guadagno_netto FROM {$tabella_fiere} ORDER BY data_fiera DESC");
    
    echo '<div style="background: #f0f0f1; padding: 15px; margin: 15px 0; border-left: 4px solid #646970;">';
    echo '<h3>Debug Informazioni Grafici</h3>';
    echo '<p>Numero totale fiere: <strong>' . $count . '</strong></p>';
    
    if ($count > 0) {
        echo '<table style="width: 100%; border-collapse: collapse; margin-top: 10px;">';
        echo '<tr style="background: #e5e5e5;"><th style="text-align: left; padding: 8px;">ID</th><th style="text-align: left; padding: 8px;">Nome</th><th style="text-align: left; padding: 8px;">Data</th><th style="text-align: left; padding: 8px;">Incasso Totale</th><th style="text-align: left; padding: 8px;">Contanti</th><th style="text-align: left; padding: 8px;">POS</th><th style="text-align: left; padding: 8px;">Guadagno</th></tr>';
        
        foreach ($fiere as $fiera) {
            echo '<tr style="border-bottom: 1px solid #ddd;">';
            echo '<td style="padding: 8px;">' . $fiera->id . '</td>';
            echo '<td style="padding: 8px;">' . esc_html($fiera->nome_fiera) . '</td>';
            echo '<td style="padding: 8px;">' . date('d/m/Y', strtotime($fiera->data_fiera)) . '</td>';
            echo '<td style="padding: 8px;">' . number_format($fiera->incasso_totale, 2, ',', '.') . '</td>';
            echo '<td style="padding: 8px;">' . number_format($fiera->incasso_contanti, 2, ',', '.') . '</td>';
            echo '<td style="padding: 8px;">' . number_format($fiera->incasso_pos, 2, ',', '.') . '</td>';
            echo '<td style="padding: 8px;">' . number_format($fiera->guadagno_netto, 2, ',', '.') . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
    }
    
    // Ottieni dati per grafico annuale
    $dati_annuali = $this->db->get_dati_grafico_annuale(2);
    
    echo '<h3>Dati per Grafico Annuale</h3>';
    if (empty($dati_annuali)) {
        echo '<p>Nessun dato disponibile per il grafico annuale</p>';
    } else {
        echo '<table style="width: 100%; border-collapse: collapse; margin-top: 10px;">';
        echo '<tr style="background: #e5e5e5;"><th style="text-align: left; padding: 8px;">Anno</th><th style="text-align: left; padding: 8px;">Mese</th><th style="text-align: left; padding: 8px;">Incasso Totale</th><th style="text-align: left; padding: 8px;">Guadagno Netto</th></tr>';
        
        foreach ($dati_annuali as $dato) {
            echo '<tr style="border-bottom: 1px solid #ddd;">';
            echo '<td style="padding: 8px;">' . $dato->anno . '</td>';
            echo '<td style="padding: 8px;">' . $dato->mese . '</td>';
            echo '<td style="padding: 8px;">' . number_format($dato->incasso_totale, 2, ',', '.') . '</td>';
            echo '<td style="padding: 8px;">' . number_format($dato->guadagno_netto, 2, ',', '.') . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
    }
    
    echo '</div>';
}
	
}