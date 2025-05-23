<?php
/**
 * Classe per la gestione delle richieste AJAX - VERSIONE CORRETTA
 *
 * @since 2.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class GIF_Ajax {
    
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
     * Ajax handler per salvare una fiera
     */
    public function ajax_salva_fiera() {
        // Verifica nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gif_nonce')) {
            wp_send_json_error('Errore di sicurezza. Ricarica la pagina e riprova.');
        }
        
        if (!isset($_POST['fiera']) || !is_array($_POST['fiera'])) {
            wp_send_json_error('Dati mancanti o non validi.');
        }
        
        $fiera = $_POST['fiera'];
        
        // Ottieni impostazioni
        $percentuale_iva = floatval($this->db->get_impostazione('percentuale_iva'));
        $percentuale_tasse = floatval($this->db->get_impostazione('percentuale_tasse'));
        
        // Se non ci sono percentuali impostate, usa i valori di default
        if ($percentuale_iva <= 0) $percentuale_iva = 22;
        if ($percentuale_tasse <= 0) $percentuale_tasse = 34;
        
        // Calcoli precisi basati sulle impostazioni attuali
        $incasso_contanti = floatval($fiera['incasso_contanti']);
        $incasso_pos = floatval($fiera['incasso_pos']);
        $incasso_totale = $incasso_contanti + $incasso_pos;
        
        // IVA solo sull'incasso POS
        $iva = $incasso_pos * ($percentuale_iva / 100);
        
        // Tasse solo sull'incasso POS dopo IVA
        $incasso_pos_post_iva = $incasso_pos - $iva;
        $tasse = $incasso_pos_post_iva * ($percentuale_tasse / 100);
        
        // Calcolo spese totali
        $spese_partecipazione = floatval($fiera['spese_partecipazione']);
        $spese_noleggio = floatval($fiera['spese_noleggio']);
        $spese_pernottamento = floatval($fiera['spese_pernottamento']);
        $altre_spese = floatval($fiera['altre_spese_non_scaricabili']);
        $spese_totali = $spese_partecipazione + $spese_noleggio + $spese_pernottamento + $altre_spese;
        
        // Calcolo guadagno netto
        $guadagno_netto = $incasso_totale - $spese_totali - $iva - $tasse;
        
        // Dati da salvare
        $dati = array(
            'nome_fiera' => sanitize_text_field($fiera['nome_fiera']),
            'data_fiera' => sanitize_text_field($fiera['data_fiera']),
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
            'note' => isset($fiera['note']) ? sanitize_textarea_field($fiera['note']) : ''
        );
        
        // Verifica se è un aggiornamento o una nuova fiera
        $id = isset($fiera['id']) && !empty($fiera['id']) ? intval($fiera['id']) : 0;
        
        $risultato = $this->db->salva_fiera($dati, $id);
        
        if ($risultato === false) {
            wp_send_json_error('Errore durante il salvataggio dei dati.');
        } else {
            // Ottieni l'ID corretto (per nuove inserzioni o aggiornamenti)
            $fiera_id = ($id > 0) ? $id : $risultato;
            
            wp_send_json_success(array(
                'message' => $id > 0 ? 'Fiera aggiornata con successo!' : 'Fiera aggiunta con successo!',
                'id' => $fiera_id
            ));
            
            // IMPORTANTE: la funzione wp_send_json_success termina l'esecuzione qui,
            // quindi nessun altro codice verrà eseguito dopo questa chiamata
        }
    }
    
    /**
     * Ajax handler per eliminare una fiera
     */
    public function ajax_elimina_fiera() {
        // Verifica nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gif_nonce')) {
            wp_send_json_error('Errore di sicurezza. Ricarica la pagina e riprova.');
        }
        
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            wp_send_json_error('ID fiera mancante.');
        }
        
        $id = intval($_POST['id']);
        
        // Chiamata al metodo DB per eliminare la fiera
        $risultato = $this->db->elimina_fiera($id);
        
        if ($risultato === false) {
            wp_send_json_error('Errore durante l\'eliminazione della fiera.');
        } else {
            wp_send_json_success('Fiera eliminata con successo!');
            // Anche qui, wp_send_json_success termina l'esecuzione
        }
    }
    
    /**
     * Ajax handler per salvare le impostazioni
     */
    public function ajax_salva_impostazioni() {
        // Verifica nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gif_nonce')) {
            wp_send_json_error('Errore di sicurezza. Ricarica la pagina e riprova.');
        }
        
        if (!isset($_POST['impostazioni']) || !is_array($_POST['impostazioni'])) {
            wp_send_json_error('Dati mancanti o non validi.');
        }
        
        $impostazioni = $_POST['impostazioni'];
        $errori = array();
        
        // Salva ogni impostazione
        foreach ($impostazioni as $nome => $valore) {
            $risultato = $this->db->salva_impostazione($nome, $valore);
            
            if ($risultato === false) {
                $errori[] = $nome;
            }
        }
        
        if (!empty($errori)) {
            wp_send_json_error('Errore durante il salvataggio delle seguenti impostazioni: ' . implode(', ', $errori));
        } else {
            wp_send_json_success('Impostazioni salvate con successo!');
        }
    }
}