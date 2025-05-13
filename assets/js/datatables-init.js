/**
 * Crea un nuovo file: assets/js/datatables-init.js
 * Questo file gestirà l'inizializzazione di DataTables in modo sicuro
 */

jQuery(document).ready(function($) {
    // Funzione per inizializzare DataTables in modo sicuro
    function initGifDataTable() {
        // Verifica se la tabella esiste
        if ($('#tabella-fiere').length === 0) {
            return;
        }
        
        // Verifica se DataTables è già stato inizializzato
        if ($.fn.DataTable.isDataTable('#tabella-fiere')) {
            $('#tabella-fiere').DataTable().destroy();
            console.log('Tabella DataTables esistente distrutta');
        }
        
        // Pulizia completa
        $('#tabella-fiere').removeAttr('aria-describedby');
        $('#tabella-fiere').removeData();
        $('.dataTables_wrapper').remove();
        
        // Inizializzazione con breve ritardo
        setTimeout(function() {
            try {
                var table = $('#tabella-fiere').DataTable({
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
                console.log('DataTables inizializzato con successo');
            } catch (error) {
                console.error('Errore durante l\'inizializzazione di DataTables:', error);
            }
        }, 100);
    }
    
    // Inizializza la tabella se siamo nella pagina elenco-fiere
    if (window.location.href.indexOf('gestione-incassi-fiere-elenco') !== -1) {
        initGifDataTable();
    }
});