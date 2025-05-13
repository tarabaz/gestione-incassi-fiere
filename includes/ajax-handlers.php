
<?php
add_action('wp_ajax_salva_fiera', 'gif_ajax_salva_fiera');
function gif_ajax_salva_fiera() {
    check_ajax_referer('gif_nonce', 'nonce');

    if (!isset($_POST['fiera']) || !is_array($_POST['fiera'])) {
        wp_send_json_error('Dati fiera non ricevuti.');
    }

    $fiera = $_POST['fiera'];

    require_once plugin_dir_path(__FILE__) . '/class-gif-database.php';
    $db = new GIF_Database();

    $successo = $db->inserisci_fiera($fiera);

    if ($successo) {
        wp_send_json_success(['message' => 'Fiera salvata con successo.']);
    } else {
        wp_send_json_error('Errore durante il salvataggio nel database.');
    }
}

add_action('wp_ajax_elimina_fiera', 'gif_ajax_elimina_fiera');
function gif_ajax_elimina_fiera() {
    check_ajax_referer('gif_nonce', 'nonce');

    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        wp_send_json_error('ID non valido');
    }

    $id = intval($_POST['id']);

    require_once plugin_dir_path(__FILE__) . '/class-gif-database.php';
    $db = new GIF_Database();
    $successo = $db->elimina_fiera($id);

    if ($successo) {
        wp_send_json_success();
    } else {
        wp_send_json_error('Errore durante la cancellazione');
    }
}


