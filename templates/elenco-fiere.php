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

<!-- Modifica del template templates/elenco-fiere.php -->
<!-- Sostituisci lo script alla fine del file con questo semplice script -->

<script>
jQuery(document).ready(function($) {
    // Verifica che la funzione esista
    if (typeof destroyExistingDataTable === 'function') {
        console.log("Chiamata alla funzione destroyExistingDataTable da elenco-fiere.php");
        destroyExistingDataTable();
    } else {
        console.error("Funzione destroyExistingDataTable non trovata - potrebbe esserci un problema con il caricamento degli script");
        
        // Fallback di emergenza
        if ($.fn.dataTable && $.fn.dataTable.isDataTable('#tabella-fiere')) {
            $('#tabella-fiere').DataTable().destroy();
        }
        
        $('#tabella-fiere').DataTable({
            responsive: true,
            pageLength: 25
        });
    }
});
</script>