<!-- templates/elenco-fiere.php -->
<?php
// Gestione messaggi
if (isset($_GET['message'])) {
    if ($_GET['message'] === 'deleted') {
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Fiera eliminata con successo!', 'gestione-incassi-fiere') . '</p></div>';
    } elseif ($_GET['message'] === 'error') {
        echo '<div class="notice notice-error is-dismissible"><p>' . __('Errore durante l\'eliminazione della fiera.', 'gestione-incassi-fiere') . '</p></div>';
    }
}
?>

<div class="wrap gif-admin">
    <h1 class="gif-page-title">
        <span class="dashicons dashicons-list-view"></span>
        <?php _e('Elenco Fiere', 'gestione-incassi-fiere'); ?>
        
        <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-nuova'); ?>" class="page-title-action">
            <span class="dashicons dashicons-plus-alt"></span>
            <?php _e('Aggiungi Nuova', 'gestione-incassi-fiere'); ?>
        </a>
    </h1>
    
    <?php include('partials/navigation.php'); ?>
    
    <div class="gif-content">
        <div class="gif-box">
            <div class="gif-box-content">
                <?php if (empty($fiere)) : ?>
                    <div class="gif-empty-state">
                        <div class="gif-empty-icon">
                            <span class="dashicons dashicons-calendar-alt"></span>
                        </div>
                        <h3><?php _e('Nessuna fiera registrata', 'gestione-incassi-fiere'); ?></h3>
                        <p><?php _e('Inizia ad aggiungere le tue fiere per visualizzare i dati', 'gestione-incassi-fiere'); ?></p>
                        <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-nuova'); ?>" class="gif-button gif-button-primary">
                            <span class="dashicons dashicons-plus-alt"></span>
                            <?php _e('Aggiungi Prima Fiera', 'gestione-incassi-fiere'); ?>
                        </a>
                    </div>
                <?php else : ?>
                    <table id="tabella-fiere" class="gif-table display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th><?php _e('Nome Fiera', 'gestione-incassi-fiere'); ?></th>
                                <th><?php _e('Data', 'gestione-incassi-fiere'); ?></th>
                                <th><?php _e('Incasso Contanti', 'gestione-incassi-fiere'); ?></th>
                                <th><?php _e('Incasso POS', 'gestione-incassi-fiere'); ?></th>
                                <th><?php _e('Incasso Totale', 'gestione-incassi-fiere'); ?></th>
                                <th><?php _e('Spese Totali', 'gestione-incassi-fiere'); ?></th>
                                <th><?php _e('IVA', 'gestione-incassi-fiere'); ?></th>
                                <th><?php _e('Tasse', 'gestione-incassi-fiere'); ?></th>
                                <th><?php _e('Guadagno Netto', 'gestione-incassi-fiere'); ?></th>
                                <th class="no-sort"><?php _e('Azioni', 'gestione-incassi-fiere'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fiere as $fiera) : ?>
                                <tr>
                                    <td data-order="<?php echo esc_attr($fiera->nome_fiera); ?>">
                                        <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-nuova&id=' . $fiera->id); ?>">
                                            <?php echo esc_html($fiera->nome_fiera); ?>
                                        </a>
                                    </td>
                                    <td data-order="<?php echo $fiera->data_fiera; ?>"><?php echo date_i18n('d/m/Y', strtotime($fiera->data_fiera)); ?></td>
                                    <td data-order="<?php echo $fiera->incasso_contanti; ?>"><?php echo $valuta; ?> <?php echo number_format($fiera->incasso_contanti, 2, ',', '.'); ?></td>
                                    <td data-order="<?php echo $fiera->incasso_pos; ?>"><?php echo $valuta; ?> <?php echo number_format($fiera->incasso_pos, 2, ',', '.'); ?></td>
                                    <td data-order="<?php echo $fiera->incasso_totale; ?>"><?php echo $valuta; ?> <?php echo number_format($fiera->incasso_totale, 2, ',', '.'); ?></td>
                                    <td data-order="<?php echo $fiera->spese_partecipazione + $fiera->spese_noleggio + $fiera->spese_pernottamento + $fiera->altre_spese_non_scaricabili; ?>">
                                        <?php echo $valuta; ?> <?php echo number_format(
                                            $fiera->spese_partecipazione + 
                                            $fiera->spese_noleggio + 
                                            $fiera->spese_pernottamento + 
                                            $fiera->altre_spese_non_scaricabili, 2, ',', '.'); ?>
                                    </td>
                                    <td data-order="<?php echo $fiera->iva; ?>"><?php echo $valuta; ?> <?php echo number_format($fiera->iva, 2, ',', '.'); ?></td>
                                    <td data-order="<?php echo $fiera->tasse; ?>"><?php echo $valuta; ?> <?php echo number_format($fiera->tasse, 2, ',', '.'); ?></td>
                                    <td data-order="<?php echo $fiera->guadagno_netto; ?>" class="<?php echo ($fiera->guadagno_netto < 0) ? 'negative' : 'positive'; ?>">
                                        <?php echo $valuta; ?> <?php echo number_format($fiera->guadagno_netto, 2, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <div class="gif-action-buttons">
                                            <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-nuova&id=' . $fiera->id); ?>" class="gif-button gif-button-sm gif-button-action gif-button-edit" title="<?php _e('Modifica', 'gestione-incassi-fiere'); ?>">
                                                <span class="dashicons dashicons-edit"></span>
                                            </a>
											<a href="<?php echo wp_nonce_url(admin_url('admin.php?page=gestione-incassi-fiere-elimina&id=' . $fiera->id), 'elimina_fiera_' . $fiera->id); ?>" class="gif-button gif-button-sm gif-button-action gif-button-delete" onclick="return confirm('<?php _e('Sei sicuro di voler eliminare questa fiera?', 'gestione-incassi-fiere'); ?>')">
													<span class="dashicons dashicons-trash"></span>
											</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?php _e('Totale', 'gestione-incassi-fiere'); ?></th>
                                <th></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->incasso_contanti, 2, ',', '.'); ?></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->incasso_pos, 2, ',', '.'); ?></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->incasso_totale, 2, ',', '.'); ?></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->spese_totali, 2, ',', '.'); ?></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->iva_totale, 2, ',', '.'); ?></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->tasse_totali, 2, ',', '.'); ?></th>
                                <th class="<?php echo ($stats->guadagno_netto_totale < 0) ? 'negative' : 'positive'; ?>">
                                    <?php echo $valuta; ?> <?php echo number_format($stats->guadagno_netto_totale, 2, ',', '.'); ?>
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <th><?php _e('Media', 'gestione-incassi-fiere'); ?></th>
                                <th></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->incasso_contanti / $stats->totale_fiere, 2, ',', '.'); ?></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->incasso_pos / $stats->totale_fiere, 2, ',', '.'); ?></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->incasso_totale / $stats->totale_fiere, 2, ',', '.'); ?></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->spese_totali / $stats->totale_fiere, 2, ',', '.'); ?></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->iva_totale / $stats->totale_fiere, 2, ',', '.'); ?></th>
                                <th><?php echo $valuta; ?> <?php echo number_format($stats->tasse_totali / $stats->totale_fiere, 2, ',', '.'); ?></th>
                                <th class="<?php echo ($stats->guadagno_medio_per_fiera < 0) ? 'negative' : 'positive'; ?>">
                                    <?php echo $valuta; ?> <?php echo number_format($stats->guadagno_medio_per_fiera, 2, ',', '.'); ?>
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Parte modificata del template elenco-fiere.php -->
<script>
jQuery(document).ready(function($) {
    // Fix per l'errore "Cannot reinitialise DataTable"
    if ($.fn.DataTable.isDataTable('#tabella-fiere')) {
        // Se la tabella è già stata inizializzata, distruggi l'istanza precedente
        $('#tabella-fiere').DataTable().destroy();
    }
    
    // Inizializza DataTables
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
    
    // Gestione eliminazione fiera - CORREZIONE COMPLETA
    $(document).on('click', '.elimina-fiera', function(e) {
        e.preventDefault();
        
        var id = $(this).data('id');
        var row = $(this).closest('tr');
        
        console.log('ID fiera da eliminare:', id);
        
        // Verifica se l'ID è valido
        if (!id) {
            console.error('ID mancante o non valido');
            Swal.fire({
                icon: 'error',
                title: 'Errore',
                text: 'ID fiera non valido. Impossibile procedere con l\'eliminazione.'
            });
            return;
        }
        
        Swal.fire({
            title: gif_vars.testi.conferma_eliminazione,
            text: "Questa operazione non può essere annullata",
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
                            text: 'Sto cancellando la fiera dal database',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        console.log('Risposta del server:', response);
                        
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminato!',
                                text: 'La fiera è stata eliminata con successo.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            // Forza il ricaricamento della pagina dopo l'eliminazione
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Errore!',
                                text: 'Si è verificato un errore durante l\'eliminazione: ' + (response.data || 'Errore sconosciuto')
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Errore AJAX:', {xhr: xhr, status: status, error: error});
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Errore di connessione',
                            text: 'Si è verificato un errore durante la comunicazione con il server: ' + error
                        });
                    }
                });
            }
        });
    });
});
</script>