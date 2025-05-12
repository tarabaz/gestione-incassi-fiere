<?php
/**
 * Ecco un approccio diretto per l'eliminazione
 * Crea un file delete-fiera.php nella cartella principale del plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Gestione dell'eliminazione diretta
function gif_handle_delete_fiera() {
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
    global $wpdb;
    $tabella_fiere = $wpdb->prefix . 'gif_fiere';
    $id = intval($_GET['id']);
    
    // Verifica che la fiera esista
    $fiera = $wpdb->get_row($wpdb->prepare("SELECT id, nome_fiera FROM {$tabella_fiere} WHERE id = %d", $id));
    if (!$fiera) {
        wp_die('La fiera richiesta non esiste');
    }
    
    // Esegui l'eliminazione
    $risultato = $wpdb->delete(
        $tabella_fiere,
        array('id' => $id),
        array('%d')
    );
    
    if ($risultato === false) {
        wp_die('Errore durante l\'eliminazione della fiera');
    }
    
    // Redirect alla pagina di elenco con messaggio di successo
    wp_redirect(admin_url('admin.php?page=gestione-incassi-fiere-elenco&message=deleted'));
    exit;
}

// Aggiungi questo hook al tuo file principale del plugin
add_action('admin_post_elimina_fiera', 'gif_handle_delete_fiera');

/**
 * Modifica il link di eliminazione nel template elenco-fiere.php
 * Sostituisci il pulsante elimina con:
 */
?>

<a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=elimina_fiera&id=' . $fiera->id), 'elimina_fiera_' . $fiera->id); ?>" class="gif-button gif-button-sm gif-button-action gif-button-delete" onclick="return confirm('<?php _e('Sei sicuro di voler eliminare questa fiera?', 'gestione-incassi-fiere'); ?>')">
    <span class="dashicons dashicons-trash"></span>
</a>