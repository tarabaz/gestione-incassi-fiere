/**
 * File: /assets/js/admin.js
 * Script JavaScript principale per l'interfaccia amministrativa
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // ====== FUNZIONI COMUNI ======
    
    /**
     * Formatta un numero in formato valuta
     */
    function formatCurrency(amount, symbol) {
        symbol = symbol || gif_vars.formati.valuta;
        return symbol + ' ' + amount.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    /**
     * Converte una data dal formato dd/mm/yyyy a yyyy-mm-dd
     */
    function convertDateFormat(dateString) {
        if (!dateString) return '';
        
        var parts = dateString.split('/');
        if (parts.length !== 3) return dateString;
        
        return parts[2] + '-' + parts[1] + '-' + parts[0];
    }
    
    /**
     * Converte una data dal formato yyyy-mm-dd a dd/mm/yyyy
     */
    function convertDateFormatReverse(dateString) {
        if (!dateString) return '';
        
        var parts = dateString.split('-');
        if (parts.length !== 3) return dateString;
        
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }
    
    // ====== INIZIALIZZAZIONE DATEPICKER ======
    if ($.fn.datepicker) {
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
    }
    
    // ====== INIZIALIZZAZIONE DATATABLES ======
    if ($.fn.DataTable) {
        $('#tabella-fiere').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/it-IT.json'
            },
            order: [[1, 'desc']], // Ordina per data di default
            columnDefs: [
                { targets: 'no-sort', orderable: false }
            ],
            pageLength: 25,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<span class="dashicons dashicons-clipboard"></span> Copia',
                    className: 'gif-dt-button'
                },
                {
                    extend: 'excel',
                    text: '<span class="dashicons dashicons-media-spreadsheet"></span> Excel',
                    className: 'gif-dt-button'
                },
                {
                    extend: 'pdf',
                    text: '<span class="dashicons dashicons-pdf"></span> PDF',
                    className: 'gif-dt-button'
                },
                {
                    extend: 'print',
                    text: '<span class="dashicons dashicons-printer"></span> Stampa',
                    className: 'gif-dt-button'
                }
            ]
        });
    }
    
// ====== GESTIONE ELIMINAZIONE FIERA ======
$('.elimina-fiera').on('click', function(e) {
    e.preventDefault();
    
    var id = $(this).data('id');
    var row = $(this).closest('tr');
    
    Swal.fire({
        title: gif_vars.testi.conferma_eliminazione,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: gif_vars.testi.conferma_si,
        cancelButtonText: gif_vars.testi.conferma_no
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: gif_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'elimina_fiera',
                    id: id,
                    nonce: gif_vars.nonce
                },
                beforeSend: function() {
                    // Mostra loader
                    Swal.fire({
                        title: 'Eliminazione in corso...',
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
                            title: gif_vars.testi.eliminazione_successo,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        // Rimuovi la riga dalla tabella se datatables è attivo
                        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tabella-fiere')) {
                            var table = $('#tabella-fiere').DataTable();
                            table.row(row).remove().draw();
                            
                            // Se non ci sono più fiere, ricarica la pagina
                            if (table.rows().count() === 0) {
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        } else {
                            // Ricarica la pagina se datatables non è attivo
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: gif_vars.testi.eliminazione_errore,
                            text: response.data
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore',
                        text: 'Errore durante la comunicazione con il server.'
                    });
                }
            });
        }
    });
});

// Aggiungi questo codice per gestire i pulsanti di eliminazione aggiunti dinamicamente
$(document).on('click', '.elimina-fiera', function(e) {
    e.preventDefault();
    
    var id = $(this).data('id');
    var row = $(this).closest('tr');
    
    // Ripeti lo stesso codice di conferma ed eliminazione qui...
    Swal.fire({
        title: gif_vars.testi.conferma_eliminazione,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: gif_vars.testi.conferma_si,
        cancelButtonText: gif_vars.testi.conferma_no
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: gif_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'elimina_fiera',
                    id: id,
                    nonce: gif_vars.nonce
                },
                beforeSend: function() {
                    // Mostra loader
                    Swal.fire({
                        title: 'Eliminazione in corso...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    console.log('Risposta dal server:', response); // Debug
                    
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: gif_vars.testi.eliminazione_successo,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        // Ricarica la pagina dopo l'eliminazione
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: gif_vars.testi.eliminazione_errore,
                            text: response.data
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Errore AJAX:', xhr, status, error); // Debug
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore',
                        text: 'Errore durante la comunicazione con il server: ' + error
                    });
                }
            });
        }
    });
});
    
    // ====== GESTIONE CALCOLI FORM FIERA ======
    function calcolaValoriFiera() {
        // Verifica se siamo nella pagina corretta
        if ($('#form-fiera').length === 0) {
            return;
        }
        
        // Ottieni le percentuali dalle impostazioni
        var percentualeIVA = parseFloat(gif_vars.impostazioni.percentuale_iva) || 22;
        var percentualeTasse = parseFloat(gif_vars.impostazioni.percentuale_tasse) || 34;
        
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
        
        // Aggiorna i display
        $('#incasso_totale_display').text(formatCurrency(incassoTotale));
        $('#spese_totali_display').text(formatCurrency(speseTotali));
        $('#iva_display').text(formatCurrency(iva));
        $('#tasse_display').text(formatCurrency(tasse));
        $('#guadagno_netto_display').text(formatCurrency(guadagnoNetto));
        
        // Aggiungi classe per evidenziare se il guadagno è positivo o negativo
        if (guadagnoNetto < 0) {
            $('#guadagno_netto_display').removeClass('positive').addClass('negative');
        } else {
            $('#guadagno_netto_display').removeClass('negative').addClass('positive');
        }
    }
    
    // Calcola i valori iniziali
    calcolaValoriFiera();
    
    // Ricalcola quando vengono modificati i campi numerici
    $('input[type="number"]').on('input', function() {
        calcolaValoriFiera();
    });
    
    // ====== GESTIONE FORM SALVATAGGIO FIERA ======
$('#form-fiera').on('submit', function(e) {
    e.preventDefault();

    // Recupera i valori dal form
    var formData = $(this).serializeArray();
    var data = {};

    $.each(formData, function(i, field) {
        data[field.name] = field.value;
    });

    // Converti la data dal formato dd/mm/yyyy a yyyy-mm-dd
    data.data_fiera = convertDateFormat(data.data_fiera);

    // Invia i dati tramite AJAX
    $.ajax({
        url: gif_vars.ajax_url,
        type: 'POST',
        data: {
            action: 'salva_fiera',
            nonce: gif_vars.nonce,
            fiera: data
        },
        beforeSend: function() {
            // Mostra loader
            Swal.fire({
                title: 'Salvataggio in corso...',
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
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                });

                // Reindirizza all'elenco dopo il salvataggio
                setTimeout(function() {
                    window.location.href = 'admin.php?page=gestione-incassi-fiere-elenco';
                }, 1500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore',
                    text: response.data
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Errore',
                text: 'Errore durante la comunicazione con il server.'
            });
        }
    });

    // 🔴 QUI NON DEVI METTERE return true;
    // 🔧 LASCIARE COSI’ COM’È va bene. Oppure puoi aggiungere esplicitamente:
    // return false;

});
    // ====== GESTIONE FORM SALVATAGGIO IMPOSTAZIONI ======
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
                    title: 'Salvataggio in corso...',
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
                        title: 'Errore',
                        text: response.data
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore',
                    text: 'Errore durante la comunicazione con il server.'
                });
            }
        });
    });
    
    // ====== ANTEPRIMA TEMA COLORE ======
    $('#tema_colore').on('change', function() {
        var nuovo_tema = $(this).val();
        var temi_disponibili = ['blue', 'green', 'purple', 'orange', 'red'];
        
        // Rimuovi tutti i temi attuali
        temi_disponibili.forEach(function(tema) {
            $('link[href*="theme-' + tema + '"]').remove();
        });
        
        // Aggiungi il nuovo tema
        var css_url = gif_vars.ajax_url.replace('admin-ajax.php', '') + '../assets/css/theme-' + nuovo_tema + '.css?ver=' + Date.now();
        $('<link>')
            .attr({
                href: css_url,
                rel: 'stylesheet',
                type: 'text/css'
            })
            .appendTo('head');
    });
});