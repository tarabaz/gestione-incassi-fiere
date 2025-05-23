<?php
/**
 * Classe estesa per la gestione delle operazioni dirette
 *
 * @since 2.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class GIF_Handler {
    
    /**
     * Oggetto database
     */
    private $db;
    
    /**
     * Costruttore
     */
    public function __construct($database) {
        $this->db = $database;
        
        // Registra l'handler di eliminazione
        add_action('admin_init', array($this, 'handle_elimina_fiera'));
        
        // Registra l'handler di salvataggio
        add_action('admin_init', array($this, 'handle_salva_fiera'));
    }
    
    /**
     * Handler per l'eliminazione di una fiera
     */
    public function handle_elimina_fiera() {
        // Verifica se siamo nella pagina di eliminazione
        if (!isset($_GET['page']) || $_GET['page'] !== 'gestione-incassi-fiere-elimina') {
            return;
        }
        
        // Verifica che sia un amministratore
        if (!current_user_can('manage_options')) {
            wp_die('Accesso negato');
        }
        
        // Verifica presenza dell'ID
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            wp_die('ID fiera mancante');
        }
        
        // Verifica nonce per sicurezza
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'elimina_fiera_' . $_GET['id'])) {
            wp_die('Verifica di sicurezza fallita');
        }
        
        // Elimina la fiera
        $id = intval($_GET['id']);
        $risultato = $this->db->elimina_fiera($id);
        
        // Redirect alla pagina di elenco con un messaggio appropriato
        if ($risultato) {
            wp_redirect(admin_url('admin.php?page=gestione-incassi-fiere-elenco&message=deleted'));
        } else {
            wp_redirect(admin_url('admin.php?page=gestione-incassi-fiere-elenco&message=error'));
        }
        exit;
    }
    
    /**
     * Handler per il salvataggio di una fiera
     */
    public function handle_salva_fiera() {
        // Verifica se è una richiesta di salvataggio fiera
        if (!isset($_POST['gif_action']) || $_POST['gif_action'] !== 'salva_fiera') {
            return;
        }
        
        // Verifica che sia un amministratore
        if (!current_user_can('manage_options')) {
            wp_die('Accesso negato');
        }
        
        // Verifica nonce per sicurezza
        if (!isset($_POST['gif_nonce']) || !wp_verify_nonce($_POST['gif_nonce'], 'gif_salva_fiera')) {
            wp_die('Verifica di sicurezza fallita');
        }
        
        // Ottieni impostazioni per IVA e tasse
        $percentuale_iva = floatval($this->db->get_impostazione('percentuale_iva'));
        $percentuale_tasse = floatval($this->db->get_impostazione('percentuale_tasse'));
        
        // Se non ci sono percentuali impostate, usa i valori di default
        if ($percentuale_iva <= 0) $percentuale_iva = 22;
        if ($percentuale_tasse <= 0) $percentuale_tasse = 34;
        
        // Calcoli precisi basati sulle impostazioni attuali
        $incasso_contanti = floatval($_POST['incasso_contanti']);
        $incasso_pos = floatval($_POST['incasso_pos']);
        $incasso_totale = $incasso_contanti + $incasso_pos;
        
        // IVA solo sull'incasso POS
        $iva = $incasso_pos * ($percentuale_iva / 100);
        
        // Tasse solo sull'incasso POS dopo IVA
        $incasso_pos_post_iva = $incasso_pos - $iva;
        $tasse = $incasso_pos_post_iva * ($percentuale_tasse / 100);
        
        // Calcolo spese totali
        $spese_partecipazione = floatval($_POST['spese_partecipazione']);
        $spese_noleggio = floatval($_POST['spese_noleggio']);
        $spese_pernottamento = floatval($_POST['spese_pernottamento']);
        $altre_spese = floatval($_POST['altre_spese_non_scaricabili']);
        $spese_totali = $spese_partecipazione + $spese_noleggio + $spese_pernottamento + $altre_spese;
        
        // Calcolo guadagno netto
        $guadagno_netto = $incasso_totale - $spese_totali - $iva - $tasse;
        
        // Converti la data dal formato dd/mm/yyyy a yyyy-mm-dd
        $data_fiera = $_POST['data_fiera'];
        $data_parti = explode('/', $data_fiera);
        if (count($data_parti) == 3) {
            $data_fiera = $data_parti[2] . '-' . $data_parti[1] . '-' . $data_parti[0];
        }
        
        // Dati da salvare
        $dati = array(
            'nome_fiera' => sanitize_text_field($_POST['nome_fiera']),
            'data_fiera' => sanitize_text_field($data_fiera),
            'incasso_contanti' => $incasso_contanti,
            'incasso_pos' => $incasso_pos,
            'spese_partecipazione' => $spese_partecipazione,
            'spese_noleggio' => $spese_noleggio,
            'spese_pernottamento' => $spese_pernottamento,
            'altre_spese_non_scaricabili' => $altre_spese,
            'incasso_totale' => $incasso_totale,
            'incasso_tassabile' => $incasso_pos,
            'iva' => $iva,
            'tasse' => $tasse,
            'guadagno_netto' => $guadagno_netto,
            'note' => isset($_POST['note']) ? sanitize_textarea_field($_POST['note']) : ''
        );
        
        // Verifica se è un aggiornamento o una nuova fiera
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        
        $risultato = $this->db->salva_fiera($dati, $id);
        
        if ($risultato === false) {
            // Redirect con errore
            wp_redirect(admin_url('admin.php?page=gestione-incassi-fiere-nuova&message=error' . ($id > 0 ? '&id=' . $id : '')));
            exit;
        } else {
            // Redirect con successo
            wp_redirect(admin_url('admin.php?page=gestione-incassi-fiere-elenco&message=saved'));
            exit;
        }
    }
}