<!-- templates/impostazioni.php -->
<div class="wrap gif-admin">
    <h1 class="gif-page-title">
        <span class="dashicons dashicons-admin-settings"></span>
        <?php _e('Impostazioni', 'gestione-incassi-fiere'); ?>
    </h1>
    
    <?php include('partials/navigation.php'); ?>
    
    <div class="gif-content">
        <div class="gif-box">
            <div class="gif-box-header">
                <h2><?php _e('Impostazioni Generali', 'gestione-incassi-fiere'); ?></h2>
            </div>
            <div class="gif-box-content">
                <form id="form-impostazioni" class="gif-form">
                    <div class="gif-form-row">
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="percentuale_iva"><?php _e('Percentuale IVA', 'gestione-incassi-fiere'); ?> (%)</label>
                                <div class="gif-input-icon">
                                    <span class="dashicons dashicons-percent"></span>
                                    <input type="number" id="percentuale_iva" name="percentuale_iva" min="0" step="0.01" value="<?php echo isset($impostazioni['percentuale_iva']) ? esc_attr($impostazioni['percentuale_iva']) : '22'; ?>" required>
                                </div>
                                <p class="gif-help-text"><?php _e('Percentuale IVA applicata all\'incasso POS', 'gestione-incassi-fiere'); ?></p>
                            </div>
                        </div>
                        
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="percentuale_tasse"><?php _e('Percentuale Tasse', 'gestione-incassi-fiere'); ?> (%)</label>
                                <div class="gif-input-icon">
                                    <span class="dashicons dashicons-bank"></span>
                                    <input type="number" id="percentuale_tasse" name="percentuale_tasse" min="0" step="0.01" value="<?php echo isset($impostazioni['percentuale_tasse']) ? esc_attr($impostazioni['percentuale_tasse']) : '34'; ?>" required>
                                </div>
                                <p class="gif-help-text"><?php _e('Percentuale tasse applicata all\'incasso POS (dopo aver tolto l\'IVA)', 'gestione-incassi-fiere'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gif-form-row">
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="valuta"><?php _e('Simbolo Valuta', 'gestione-incassi-fiere'); ?></label>
                                <input type="text" id="valuta" name="valuta" value="<?php echo isset($impostazioni['valuta']) ? esc_attr($impostazioni['valuta']) : '€'; ?>" required>
                                <p class="gif-help-text"><?php _e('Simbolo della valuta utilizzato nei report e nelle statistiche', 'gestione-incassi-fiere'); ?></p>
                            </div>
                        </div>
                        
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="tema_colore"><?php _e('Tema Colore', 'gestione-incassi-fiere'); ?></label>
                                <select id="tema_colore" name="tema_colore">
                                    <?php 
                                    $temi_colore = GIF_Settings::get_temi_colore();
                                    $tema_attuale = isset($impostazioni['tema_colore']) ? $impostazioni['tema_colore'] : 'blue';
                                    
                                    foreach ($temi_colore as $valore => $etichetta) {
                                        echo '<option value="' . esc_attr($valore) . '"' . selected($tema_attuale, $valore, false) . '>' . esc_html($etichetta) . '</option>';
                                    }
                                    ?>
                                </select>
                                <p class="gif-help-text"><?php _e('Tema colore per l\'interfaccia amministrativa', 'gestione-incassi-fiere'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gif-form-actions">
                        <button type="submit" class="gif-button gif-button-primary">
                            <span class="dashicons dashicons-saved"></span>
                            <?php _e('Salva Impostazioni', 'gestione-incassi-fiere'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="gif-row">
            <div class="gif-col gif-col-6">
                <div class="gif-box">
                    <div class="gif-box-header">
                        <h2><?php _e('Anteprima Tema', 'gestione-incassi-fiere'); ?></h2>
                    </div>
                    <div class="gif-box-content">
                        <div class="gif-theme-preview">
                            <div class="gif-theme-sample-button gif-button gif-button-primary">
                                <?php _e('Pulsante Primario', 'gestione-incassi-fiere'); ?>
                            </div>
                            
                            <div class="gif-theme-sample-button gif-button">
                                <?php _e('Pulsante Secondario', 'gestione-incassi-fiere'); ?>
                            </div>
                            
                            <div class="gif-theme-sample-box">
                                <div class="gif-theme-sample-header">
                                    <?php _e('Intestazione', 'gestione-incassi-fiere'); ?>
                                </div>
                                <div class="gif-theme-sample-content">
                                    <?php _e('Contenuto di esempio con il tema selezionato.', 'gestione-incassi-fiere'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="gif-col gif-col-6">
                <div class="gif-box">
                    <div class="gif-box-header">
                        <h2><?php _e('Informazioni Plugin', 'gestione-incassi-fiere'); ?></h2>
                    </div>
                    <div class="gif-box-content">
                        <table class="gif-info-table">
                            <tr>
                                <th><?php _e('Versione Plugin', 'gestione-incassi-fiere'); ?></th>
                                <td><?php echo GIF_PLUGIN_VERSION; ?></td>
                            </tr>
                            <tr>
                                <th><?php _e('Numero Fiere', 'gestione-incassi-fiere'); ?></th>
                                <td><?php echo isset($stats->totale_fiere) ? $stats->totale_fiere : 0; ?></td>
                            </tr>
                            <tr>
                                <th><?php _e('Database', 'gestione-incassi-fiere'); ?></th>
                                <td><?php _e('Tabelle:', 'gestione-incassi-fiere'); ?> gif_fiere, gif_impostazioni</td>
                            </tr>
                            <tr>
                                <th><?php _e('Supporto', 'gestione-incassi-fiere'); ?></th>
                                <td><a href="mailto:assistenza@example.com">assistenza@example.com</a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Anteprima tema al cambio del select
    $('#tema_colore').on('change', function() {
        var nuovo_tema = $(this).val();
        var temi_disponibili = <?php echo json_encode(array_keys(GIF_Settings::get_temi_colore())); ?>;
        
        // Rimuovi tutti i temi attuali
        temi_disponibili.forEach(function(tema) {
            $('link[href*="theme-' + tema + '"]').remove();
        });
        
        // Aggiungi il nuovo tema
        var css_url = '<?php echo GIF_PLUGIN_URL; ?>assets/css/theme-' + nuovo_tema + '.css?ver=<?php echo GIF_PLUGIN_VERSION; ?>';
        $('<link>')
            .attr({
                href: css_url,
                rel: 'stylesheet',
                type: 'text/css'
            })
            .appendTo('head');
    });
    
    // Gestione invio form
    $('#form-impostazioni').on('submit', function(e) {
        e.preventDefault();
        
        // Recupera i valori dal form
        var percentuale_iva = $('#percentuale_iva').val();
        var percentuale_tasse = $('#percentuale_tasse').val();
        var valuta = $('#valuta').val();
        var tema_colore = $('#tema_colore').val();
        
        // Crea oggetto impostazioni
        var impostazioni = {
            'percentuale_iva': percentuale_iva,
            'percentuale_tasse': percentuale_tasse,
            'valuta': valuta,
            'tema_colore': tema_colore
        };
        
        // Invia i dati tramite AJAX
        $.ajax({
            url: gif_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'salva_impostazioni',
                nonce: gif_vars.nonce,
                impostazioni: impostazioni
            },
            beforeSend: function() {
                // Mostra loader
                Swal.fire({
                    title: '<?php _e('Salvataggio in corso...', 'gestione-incassi-fiere'); ?>',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: response.data,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Ricarica la pagina dopo il salvataggio se è cambiato il tema
                    if (tema_colore !== gif_vars.tema_colore) {
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '<?php _e('Errore', 'gestione-incassi-fiere'); ?>',
                        text: response.data
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: '<?php _e('Errore', 'gestione-incassi-fiere'); ?>',
                    text: '<?php _e('Errore durante la comunicazione con il server.', 'gestione-incassi-fiere'); ?>'
                });
            }
        });
    });
});
</script>