/**
 * Sostituisci completamente il contenuto del file assets/js/datatables-init.js
 * con questo codice aggiornato
 */

// Variabile globale per tenere traccia dell'inizializzazione
window.gifDataTableInitialized = false;

jQuery(document).ready(function($) {
    console.log("Script datatables-init.js caricato");
    
    // Distruggi qualsiasi istanza precedente con un po' di ritardo
    setTimeout(function() {
        destroyExistingDataTable();
    }, 100);
});

// Funzione per distruggere l'istanza esistente
function destroyExistingDataTable() {
    var $ = jQuery;
    console.log("Controllo istanze DataTables esistenti...");
    
    // Se esiste già un'istanza DataTables, distruggila completamente
    if ($.fn.dataTable && $.fn.dataTable.isDataTable('#tabella-fiere')) {
        console.log("Istanza DataTables trovata - Distruzione in corso...");
        
        try {
            // Distruggi l'istanza DataTables
            $('#tabella-fiere').DataTable().destroy();
            
            // Rimuovi qualsiasi attributo o class aggiunto da DataTables
            $('#tabella-fiere').removeClass('dataTable');
            $('#tabella-fiere').removeAttr('role');
            $('#tabella-fiere').removeAttr('aria-describedby');
            
            // Rimuovi wrapper e altri elementi aggiunti
            var wrapper = $('#tabella-fiere').closest('.dataTables_wrapper');
            if (wrapper.length) {
                $('#tabella-fiere').insertBefore(wrapper);
                wrapper.remove();
            }
            
            // Forza pulizia della cache DataTables
            $.fn.dataTable.tables().destroy();
            
            console.log("Istanza DataTables distrutta con successo");
        } catch (error) {
            console.error("Errore durante la distruzione dell'istanza DataTables:", error);
        }
    } else {
        console.log("Nessuna istanza DataTables trovata");
    }
    
    // Inizializza dopo un piccolo ritardo
    setTimeout(initializeDataTable, 200);
}

// Funzione per inizializzare DataTables
function initializeDataTable() {
    var $ = jQuery;
    console.log("Tentativo di inizializzazione DataTables...");
    
    // Se la tabella non esiste, esci
    if ($('#tabella-fiere').length === 0) {
        console.log("Tabella #tabella-fiere non trovata nel DOM");
        return;
    }
    
    // Se è già inizializzato, non fare nulla
    if (window.gifDataTableInitialized) {
        console.log("DataTables già inizializzato, skip");
        return;
    }
    
    try {
        // Inizializza DataTables con opzioni minime
        console.log("Inizializzazione DataTables con opzioni base...");
        var table = $('#tabella-fiere').DataTable({
            responsive: true,
            order: [[1, 'desc']], // Ordina per data di default
            pageLength: 25
        });
        
        // Segna come inizializzato
        window.gifDataTableInitialized = true;
        console.log("DataTables inizializzato con successo");
    } catch (error) {
        console.error("Errore durante l'inizializzazione di DataTables:", error);
    }
}