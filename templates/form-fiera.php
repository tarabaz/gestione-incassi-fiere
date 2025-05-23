<!-- templates/form-fiera.php -->
<div class="wrap gif-admin">
    <h1 class="gif-page-title">
        <span class="dashicons dashicons-edit"></span>
        <?php echo $is_edit ? __('Modifica Fiera', 'gestione-incassi-fiere') : __('Aggiungi Nuova Fiera', 'gestione-incassi-fiere'); ?>
    </h1>
    
    <?php include('partials/navigation.php'); ?>
    
    <?php
    // Gestione messaggi
    if (isset($_GET['message'])) {
        if ($_GET['message'] === 'saved') {
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Fiera salvata con successo!', 'gestione-incassi-fiere') . '</p></div>';
        } elseif ($_GET['message'] === 'error') {
            echo '<div class="notice notice-error is-dismissible"><p>' . __('Errore durante il salvataggio della fiera.', 'gestione-incassi-fiere') . '</p></div>';
        }
    }
    ?>
    
    <div class="gif-content">
        <div class="gif-box">
            <div class="gif-box-content">
                <!-- Modificato per usare un form standard invece di AJAX -->
                <form id="form-fiera" class="gif-form" method="post" action="<?php echo admin_url('admin.php'); ?>">
                    <input type="hidden" name="gif_action" value="salva_fiera">
                    <?php wp_nonce_field('gif_salva_fiera', 'gif_nonce'); ?>
                    <input type="hidden" name="id" value="<?php echo $is_edit ? $fiera->id : ''; ?>">
                    
                    <div class="gif-form-row">
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="nome_fiera"><?php _e('Nome Fiera', 'gestione-incassi-fiere'); ?> <span class="required">*</span></label>
                                <input type="text" id="nome_fiera" name="nome_fiera" value="<?php echo $is_edit ? esc_attr($fiera->nome_fiera) : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="data_fiera"><?php _e('Data Fiera', 'gestione-incassi-fiere'); ?> <span class="required">*</span></label>
                                <div class="gif-input-icon">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                    <input type="text" id="data_fiera" name="data_fiera" class="datepicker" value="<?php echo $is_edit ? date('d/m/Y', strtotime($fiera->data_fiera)) : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gif-form-row">
                        <div class="gif-form-column">
                            <h3 class="gif-section-title"><?php _e('Incassi', 'gestione-incassi-fiere'); ?></h3>
                        </div>
                    </div>
                    
                    <div class="gif-form-row">
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="incasso_contanti"><?php _e('Incasso Contanti', 'gestione-incassi-fiere'); ?> (<?php echo $valuta; ?>)</label>
                                <div class="gif-input-icon">
                                    <span class="dashicons dashicons-money-alt"></span>
                                    <input type="number" id="incasso_contanti" name="incasso_contanti" min="0" step="0.01" value="<?php echo $is_edit ? esc_attr($fiera->incasso_contanti) : '0.00'; ?>" required>
                                </div>
                                <p class="gif-help-text"><?php _e('Incasso in contanti non soggetto a IVA e tasse', 'gestione-incassi-fiere'); ?></p>
                            </div>
                        </div>
                        
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="incasso_pos"><?php _e('Incasso POS', 'gestione-incassi-fiere'); ?> (<?php echo $valuta; ?>)</label>
                                <div class="gif-input-icon">
                                    <span class="dashicons dashicons-cart"></span>
                                    <input type="number" id="incasso_pos" name="incasso_pos" min="0" step="0.01" value="<?php echo $is_edit ? esc_attr($fiera->incasso_pos) : '0.00'; ?>" required>
                                </div>
                                <p class="gif-help-text"><?php printf(__('Incasso con POS soggetto a IVA (%s%%) e tasse (%s%%)', 'gestione-incassi-fiere'), $percentuale_iva, $percentuale_tasse); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gif-form-row">
                        <div class="gif-form-column">
                            <h3 class="gif-section-title"><?php _e('Spese', 'gestione-incassi-fiere'); ?></h3>
                        </div>
                    </div>
                    
                    <div class="gif-form-row">
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="spese_partecipazione"><?php _e('Spese Partecipazione', 'gestione-incassi-fiere'); ?> (<?php echo $valuta; ?>)</label>
                                <div class="gif-input-icon">
                                    <span class="dashicons dashicons-tickets-alt"></span>
                                    <input type="number" id="spese_partecipazione" name="spese_partecipazione" min="0" step="0.01" value="<?php echo $is_edit ? esc_attr($fiera->spese_partecipazione) : '0.00'; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="spese_noleggio"><?php _e('Spese Noleggio Mezzo', 'gestione-incassi-fiere'); ?> (<?php echo $valuta; ?>)</label>
                                <div class="gif-input-icon">
                                    <span class="dashicons dashicons-car"></span>
                                    <input type="number" id="spese_noleggio" name="spese_noleggio" min="0" step="0.01" value="<?php echo $is_edit ? esc_attr($fiera->spese_noleggio) : '0.00'; ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gif-form-row">
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="spese_pernottamento"><?php _e('Spese Pernottamento', 'gestione-incassi-fiere'); ?> (<?php echo $valuta; ?>)</label>
                                <div class="gif-input-icon">
                                    <span class="dashicons dashicons-admin-home"></span>
                                    <input type="number" id="spese_pernottamento" name="spese_pernottamento" min="0" step="0.01" value="<?php echo $is_edit ? esc_attr($fiera->spese_pernottamento) : '0.00'; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="altre_spese_non_scaricabili"><?php _e('Altre Spese non Scaricabili', 'gestione-incassi-fiere'); ?> (<?php echo $valuta; ?>)</label>
                                <div class="gif-input-icon">
                                    <span class="dashicons dashicons-plus-alt"></span>
                                    <input type="number" id="altre_spese_non_scaricabili" name="altre_spese_non_scaricabili" min="0" step="0.01" value="<?php echo $is_edit ? esc_attr($fiera->altre_spese_non_scaricabili) : '0.00'; ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gif-form-row">
                        <div class="gif-form-column">
                            <div class="gif-form-group">
                                <label for="note"><?php _e('Note', 'gestione-incassi-fiere'); ?></label>
                                <textarea id="note" name="note" rows="3"><?php echo $is_edit && isset($fiera->note) ? esc_textarea($fiera->note) : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gif-form-row">
                        <div class="gif-form-column">
                            <div class="gif-riepilogo-box">
                                <h3 class="gif-section-title"><?php _e('Riepilogo Calcoli', 'gestione-incassi-fiere'); ?></h3>
                                
                                <div class="gif-riepilogo-grid">
                                    <div class="gif-riepilogo-item">
                                        <div class="gif-riepilogo-label"><?php _e('Incasso Totale', 'gestione-incassi-fiere'); ?></div>
                                        <div id="incasso_totale_display" class="gif-riepilogo-value"><?php echo $valuta; ?> 0,00</div>
                                    </div>
                                    
                                    <div class="gif-riepilogo-item">
                                        <div class="gif-riepilogo-label"><?php _e('Spese Totali', 'gestione-incassi-fiere'); ?></div>
                                        <div id="spese_totali_display" class="gif-riepilogo-value"><?php echo $valuta; ?> 0,00</div>
                                    </div>
                                    
                                    <div class="gif-riepilogo-item">
                                        <div class="gif-riepilogo-label"><?php printf(__('IVA (%s%% su POS)', 'gestione-incassi-fiere'), $percentuale_iva); ?></div>
                                        <div id="iva_display" class="gif-riepilogo-value"><?php echo $valuta; ?> 0,00</div>
                                    </div>
                                    
                                    <div class="gif-riepilogo-item">
                                        <div class="gif-riepilogo-label"><?php printf(__('Tasse (%s%% su POS - IVA)', 'gestione-incassi-fiere'), $percentuale_tasse); ?></div>
                                        <div id="tasse_display" class="gif-riepilogo-value"><?php echo $valuta; ?> 0,00</div>
                                    </div>
                                </div>
                                
                                <div class="gif-riepilogo-total">
                                    <div class="gif-riepilogo-label"><?php _e('Guadagno Netto', 'gestione-incassi-fiere'); ?></div>
                                    <div id="guadagno_netto_display" class="gif-riepilogo-value"><?php echo $valuta; ?> 0,00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gif-form-actions">
                        <button type="submit" class="gif-button gif-button-primary">
                            <span class="dashicons dashicons-saved"></span>
                            <?php echo $is_edit ? __('Aggiorna Fiera', 'gestione-incassi-fiere') : __('Salva Fiera', 'gestione-incassi-fiere'); ?>
                        </button>
                        
                        <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-elenco'); ?>" class="gif-button">
                            <span class="dashicons dashicons-no-alt"></span>
                            <?php _e('Annulla', 'gestione-incassi-fiere'); ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Inizializza datepicker
    $('.datepicker').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '-10:+1',
        dayNames: ['Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Me', 'Gi', 'Ve', 'Sa'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
        monthNames: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
        monthNamesShort: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
        firstDay: 1,
        prevText: 'Prec',
        nextText: 'Succ'
    });
    
    // Funzione per calcolare i valori
    function calcolaValori() {
        // Percentuali IVA e tasse dalle impostazioni
        var percentualeIVA = <?php echo $percentuale_iva; ?>;
        var percentualeTasse = <?php echo $percentuale_tasse; ?>;
        
        // Recupera i valori dal form
        var incassoContanti = parseFloat($('#incasso_contanti').val()) || 0;
        var incassoPOS = parseFloat($('#incasso_pos').val()) || 0;
        var spesePartecipazione = parseFloat($('#spese_partecipazione').val()) || 0;
        var speseNoleggio = parseFloat($('#spese_noleggio').val()) || 0;
        var spesePernottamento = parseFloat($('#spese_pernottamento').val()) || 0;
        var altreSpese = parseFloat($('#altre_spese_non_scaricabili').val()) || 0;
        
        // Calcoli
        var incassoTotale = incassoContanti + incassoPOS;
        var speseTotali = spesePartecipazione + speseNoleggio + spesePernottamento + altreSpese;
        
        // IVA solo sull'incasso POS
        var iva = incassoPOS * (percentualeIVA / 100);
        
        // Tasse solo sull'incasso POS dopo aver tolto l'IVA
        var incassoPosDopIva = incassoPOS - iva;
        var tasse = incassoPosDopIva * (percentualeTasse / 100);
        
        // Guadagno netto: incasso totale - spese totali - IVA - tasse
        var guadagnoNetto = incassoTotale - speseTotali - iva - tasse;
        
        // Formatta i numeri
        function formatoEuro(numero) {
            return numero.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // Aggiorna i display
        $('#incasso_totale_display').text('<?php echo $valuta; ?> ' + formatoEuro(incassoTotale));
        $('#spese_totali_display').text('<?php echo $valuta; ?> ' + formatoEuro(speseTotali));
        $('#iva_display').text('<?php echo $valuta; ?> ' + formatoEuro(iva));
        $('#tasse_display').text('<?php echo $valuta; ?> ' + formatoEuro(tasse));
        $('#guadagno_netto_display').text('<?php echo $valuta; ?> ' + formatoEuro(guadagnoNetto));
        
        // Aggiungi classe per evidenziare se il guadagno è positivo o negativo
        if (guadagnoNetto < 0) {
            $('#guadagno_netto_display').removeClass('positive').addClass('negative');
        } else {
            $('#guadagno_netto_display').removeClass('negative').addClass('positive');
        }
    }
    
    // Calcola i valori iniziali
    calcolaValori();
    
    // Ricalcola quando vengono modificati i campi numerici
    $('input[type="number"]').on('input', function() {
        calcolaValori();
    });
    
    // Aggiunta della validazione del form prima dell'invio
    $('#form-fiera').on('submit', function(e) {
        var nomeField = $('#nome_fiera');
        var dataField = $('#data_fiera');
        
        // Verifica nome fiera
        if (nomeField.val().trim() === '') {
            alert('Inserisci il nome della fiera');
            nomeField.focus();
            e.preventDefault();
            return false;
        }
        
        // Verifica data fiera
        if (dataField.val().trim() === '') {
            alert('Inserisci la data della fiera');
            dataField.focus();
            e.preventDefault();
            return false;
        }
        
        // Disabilita il pulsante di invio per evitare doppie sottomissioni
        $(this).find('button[type="submit"]').prop('disabled', true).text('Salvataggio in corso...');
        
        // Tutto ok, procedi con l'invio
        return true;
    });
});
</script>