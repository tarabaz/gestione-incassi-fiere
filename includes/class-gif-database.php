<?php
/**
 * Classe per la gestione del database - VERSIONE CORRETTA
 *
 * @since 2.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class GIF_Database {
    
    /**
     * Nome della tabella principale
     */
    private $tabella_fiere;
    
    /**
     * Nome della tabella impostazioni
     */
    private $tabella_impostazioni;
    
    /**
     * Costruttore
     */
    public function __construct() {
        global $wpdb;
        $this->tabella_fiere = $wpdb->prefix . 'gif_fiere';
        $this->tabella_impostazioni = $wpdb->prefix . 'gif_impostazioni';
    }
    
    /**
     * Ottieni il nome della tabella fiere
     */
    public function get_tabella_fiere() {
        return $this->tabella_fiere;
    }
    
    /**
     * Ottieni il nome della tabella impostazioni
     */
    public function get_tabella_impostazioni() {
        return $this->tabella_impostazioni;
    }
    
    /**
     * Metodo eseguito all'attivazione del plugin
     */
    public function attivazione_plugin() {
        $this->crea_tabelle();
        $this->inizializza_impostazioni();
    }
    
    /**
     * Metodo eseguito alla disattivazione del plugin
     */
    public function disattivazione_plugin() {
        // Se vuoi rimuovere le tabelle alla disattivazione, decommentare il codice sottostante
        /*
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$this->tabella_fiere}");
        $wpdb->query("DROP TABLE IF EXISTS {$this->tabella_impostazioni}");
        */
    }
    
    /**
     * Crea le tabelle nel database
     */
    private function crea_tabelle() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        // Tabella fiere
        $sql_fiere = "CREATE TABLE {$this->tabella_fiere} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nome_fiera varchar(100) NOT NULL,
            data_fiera date NOT NULL,
            incasso_contanti decimal(10,2) NOT NULL DEFAULT 0,
            incasso_pos decimal(10,2) NOT NULL DEFAULT 0,
            spese_partecipazione decimal(10,2) NOT NULL DEFAULT 0,
            spese_noleggio decimal(10,2) NOT NULL DEFAULT 0,
            spese_pernottamento decimal(10,2) NOT NULL DEFAULT 0,
            altre_spese_non_scaricabili decimal(10,2) NOT NULL DEFAULT 0,
            incasso_totale decimal(10,2) NOT NULL DEFAULT 0,
            incasso_tassabile decimal(10,2) NOT NULL DEFAULT 0,
            iva decimal(10,2) NOT NULL DEFAULT 0,
            tasse decimal(10,2) NOT NULL DEFAULT 0,
            guadagno_netto decimal(10,2) NOT NULL DEFAULT 0,
            data_creazione datetime DEFAULT CURRENT_TIMESTAMP,
            note text,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        // Tabella impostazioni
        $sql_impostazioni = "CREATE TABLE {$this->tabella_impostazioni} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nome_impostazione varchar(100) NOT NULL,
            valore_impostazione text NOT NULL,
            data_modifica datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY nome_impostazione (nome_impostazione)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_fiere);
        dbDelta($sql_impostazioni);
    }
    
    /**
     * Inizializza le impostazioni di default
     */
    private function inizializza_impostazioni() {
        global $wpdb;
        
        // Verifica se le impostazioni sono già presenti
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$this->tabella_impostazioni}");
        
        if ($count == 0) {
            // Impostazioni di default
            $impostazioni_default = array(
                array(
                    'nome_impostazione' => 'percentuale_iva',
                    'valore_impostazione' => '22'
                ),
                array(
                    'nome_impostazione' => 'percentuale_tasse',
                    'valore_impostazione' => '34'
                ),
                array(
                    'nome_impostazione' => 'valuta',
                    'valore_impostazione' => '€'
                ),
                array(
                    'nome_impostazione' => 'tema_colore',
                    'valore_impostazione' => 'blue' // Opzioni: blue, green, purple, orange, red
                ),
                array(
                    'nome_impostazione' => 'voci_spesa',
                    'valore_impostazione' => json_encode(array(
                        'Spese di partecipazione',
                        'Spese di noleggio',
                        'Spese di pernottamento',
                        'Altre spese non scaricabili'
                    ))
                )
            );
            
            // Inserisci le impostazioni di default
            foreach ($impostazioni_default as $impostazione) {
                $wpdb->insert(
                    $this->tabella_impostazioni,
                    array(
                        'nome_impostazione' => $impostazione['nome_impostazione'],
                        'valore_impostazione' => $impostazione['valore_impostazione']
                    )
                );
            }
        }
    }
    
    /**
     * Ottieni tutte le fiere
     */
    public function get_fiere($order_by = 'data_fiera', $order = 'DESC', $limit = null) {
        global $wpdb;
        
        $sql = "SELECT * FROM {$this->tabella_fiere} ORDER BY {$order_by} {$order}";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $wpdb->get_results($sql);
    }
    
    /**
     * Ottieni una fiera specifica
     */
    public function get_fiera($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->tabella_fiere} WHERE id = %d", $id));
    }
    
    /**
     * Salva una fiera (nuovo o aggiornamento)
     * 
     * @param array $dati I dati della fiera da salvare
     * @param int $id ID della fiera (0 per nuova fiera)
     * @return mixed ID della fiera inserita o aggiornata, o false in caso di errore
     */
    public function salva_fiera($dati, $id = 0) {
        global $wpdb;
        
        if ($id > 0) {
            // Aggiornamento
            $risultato = $wpdb->update(
                $this->tabella_fiere,
                $dati,
                array('id' => $id)
            );
            
            return ($risultato !== false) ? $id : false;
        } else {
            // Inserimento nuovo
            $risultato = $wpdb->insert(
                $this->tabella_fiere,
                $dati
            );
            
            return ($risultato) ? $wpdb->insert_id : false;
        }
    }
    
    /**
     * Elimina una fiera
     * 
     * @param int $id ID della fiera da eliminare
     * @return bool Vero se l'eliminazione è avvenuta con successo, falso altrimenti
     */
    public function elimina_fiera($id) {
        global $wpdb;
        
        // Assicurati che $id sia un numero intero positivo
        $id = absint($id);
        if ($id <= 0) {
            return false;
        }
        
        // Verifica che la fiera esista prima di eliminarla
        $fiera = $this->get_fiera($id);
        if (!$fiera) {
            return false;
        }
        
        // Esegui l'eliminazione
        $risultato = $wpdb->delete(
            $this->tabella_fiere,
            array('id' => $id),
            array('%d')
        );
        
        return ($risultato !== false && $risultato > 0);
    }
    
    /**
     * Ottieni statistiche generali
     */
    public function get_statistiche_generali() {
        global $wpdb;
        
        return $wpdb->get_row("
            SELECT 
                COUNT(*) as totale_fiere,
                SUM(incasso_totale) as incasso_totale,
                SUM(incasso_contanti) as incasso_contanti,
                SUM(incasso_pos) as incasso_pos,
                SUM(spese_partecipazione + spese_noleggio + spese_pernottamento + altre_spese_non_scaricabili) as spese_totali,
                SUM(iva) as iva_totale,
                SUM(tasse) as tasse_totali,
                SUM(guadagno_netto) as guadagno_netto_totale,
                AVG(guadagno_netto) as guadagno_medio_per_fiera,
                MAX(guadagno_netto) as miglior_guadagno,
                MIN(guadagno_netto) as peggior_guadagno
            FROM {$this->tabella_fiere}
        ");
    }
    
    /**
     * Ottieni le fiere più redditizie
     */
    public function get_fiere_piu_redditizie($limit = 5) {
        global $wpdb;
        
        return $wpdb->get_results("
            SELECT 
                id, 
                nome_fiera, 
                data_fiera, 
                incasso_totale, 
                guadagno_netto 
            FROM {$this->tabella_fiere} 
            ORDER BY guadagno_netto DESC 
            LIMIT {$limit}
        ");
    }
    
    /**
     * Ottieni le fiere meno redditizie
     */
    public function get_fiere_meno_redditizie($limit = 5) {
        global $wpdb;
        
        return $wpdb->get_results("
            SELECT 
                id, 
                nome_fiera, 
                data_fiera, 
                incasso_totale, 
                guadagno_netto 
            FROM {$this->tabella_fiere} 
            ORDER BY guadagno_netto ASC 
            LIMIT {$limit}
        ");
    }
    
/**
 * Correzione della funzione get_dati_grafico_annuale nel file class-gif-database.php
 */
public function get_dati_grafico_annuale($anni = 2) {
    global $wpdb;
    
    // Utilizza l'anno corrente effettivo invece di date() che potrebbe essere alterato in WordPress
    $anno_corrente = intval(date('Y'));
    $anno_inizio = $anno_corrente - $anni + 1;
    
    // Debug query
    $query = "
        SELECT 
            YEAR(data_fiera) as anno, 
            MONTH(data_fiera) as mese, 
            SUM(incasso_totale) as incasso_totale, 
            SUM(guadagno_netto) as guadagno_netto 
        FROM {$this->tabella_fiere} 
        GROUP BY YEAR(data_fiera), MONTH(data_fiera) 
        ORDER BY anno, mese
    ";
    
    // Esegui la query senza filtro sull'anno per vedere tutti i dati
    return $wpdb->get_results($query);
}
    
    /**
     * Ottieni la miglior fiera
     */
    public function get_miglior_fiera() {
        global $wpdb;
        
        return $wpdb->get_row("
            SELECT *
            FROM {$this->tabella_fiere} 
            ORDER BY guadagno_netto DESC 
            LIMIT 1
        ");
    }
    
    /**
     * Ottieni la peggior fiera
     */
    public function get_peggior_fiera() {
        global $wpdb;
        
        return $wpdb->get_row("
            SELECT *
            FROM {$this->tabella_fiere} 
            ORDER BY guadagno_netto ASC 
            LIMIT 1
        ");
    }
    
    /**
     * Ottieni un'impostazione
     */
    public function get_impostazione($nome) {
        global $wpdb;
        
        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT valore_impostazione FROM {$this->tabella_impostazioni} WHERE nome_impostazione = %s",
            $nome
        ));
        
        return $result;
    }
    
    /**
     * Ottieni tutte le impostazioni
     */
    public function get_tutte_impostazioni() {
        global $wpdb;
        
        $results = $wpdb->get_results("SELECT nome_impostazione, valore_impostazione FROM {$this->tabella_impostazioni}");
        
        $impostazioni = array();
        foreach ($results as $row) {
            $impostazioni[$row->nome_impostazione] = $row->valore_impostazione;
        }
        
        return $impostazioni;
    }
    
    /**
     * Salva un'impostazione
     */
    public function salva_impostazione($nome, $valore) {
        global $wpdb;
        
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->tabella_impostazioni} WHERE nome_impostazione = %s",
            $nome
        ));
        
        if ($exists) {
            // Aggiornamento
            return $wpdb->update(
                $this->tabella_impostazioni,
                array(
                    'valore_impostazione' => $valore,
                    'data_modifica' => current_time('mysql')
                ),
                array('nome_impostazione' => $nome)
            );
        } else {
            // Inserimento nuovo
            return $wpdb->insert(
                $this->tabella_impostazioni,
                array(
                    'nome_impostazione' => $nome,
                    'valore_impostazione' => $valore
                )
            );
        }
    }
}