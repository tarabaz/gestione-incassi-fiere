<!-- templates/statistiche.php -->
<div class="wrap gif-admin">
    <h1 class="gif-page-title">
        <span class="dashicons dashicons-chart-line"></span>
        <?php _e('Statistiche Fiere', 'gestione-incassi-fiere'); ?>
    </h1>
    
    <?php include('partials/navigation.php'); ?>
    
    <div class="gif-content">
        <?php if (empty($stats->totale_fiere)) : ?>
            <div class="gif-box">
                <div class="gif-box-content">
                    <div class="gif-empty-state">
                        <div class="gif-empty-icon">
                            <span class="dashicons dashicons-chart-line"></span>
                        </div>
                        <h3><?php _e('Nessun dato disponibile', 'gestione-incassi-fiere'); ?></h3>
                        <p><?php _e('Aggiungi delle fiere per visualizzare le statistiche', 'gestione-incassi-fiere'); ?></p>
                        <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-nuova'); ?>" class="gif-button gif-button-primary">
                            <span class="dashicons dashicons-plus-alt"></span>
                            <?php _e('Aggiungi Prima Fiera', 'gestione-incassi-fiere'); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <!-- Riepilogo generale -->
            <div class="gif-box">
                <div class="gif-box-header">
                    <h2><?php _e('Riepilogo Generale', 'gestione-incassi-fiere'); ?></h2>
                </div>
                <div class="gif-box-content">
                    <div class="gif-stats-grid">
                        <div class="gif-stat-card">
                            <div class="gif-stat-icon">
                                <span class="dashicons dashicons-calendar-alt"></span>
                            </div>
                            <div class="gif-stat-content">
                                <div class="gif-stat-value"><?php echo $stats->totale_fiere; ?></div>
                                <div class="gif-stat-label"><?php _e('Fiere Totali', 'gestione-incassi-fiere'); ?></div>
                            </div>
                        </div>
                        
                        <div class="gif-stat-card">
                            <div class="gif-stat-icon">
                                <span class="dashicons dashicons-money-alt"></span>
                            </div>
                            <div class="gif-stat-content">
                                <div class="gif-stat-value"><?php echo $valuta; ?> <?php echo number_format($stats->incasso_totale, 2, ',', '.'); ?></div>
                                <div class="gif-stat-label"><?php _e('Incasso Totale', 'gestione-incassi-fiere'); ?></div>
                            </div>
                        </div>
                        
                        <div class="gif-stat-card">
                            <div class="gif-stat-icon">
                                <span class="dashicons dashicons-cart"></span>
                            </div>
                            <div class="gif-stat-content">
                                <div class="gif-stat-value"><?php echo $valuta; ?> <?php echo number_format($stats->incasso_pos, 2, ',', '.'); ?></div>
                                <div class="gif-stat-label"><?php _e('Incasso POS', 'gestione-incassi-fiere'); ?></div>
                            </div>
                        </div>
                        
                        <div class="gif-stat-card">
                            <div class="gif-stat-icon">
                                <span class="dashicons dashicons-money"></span>
                            </div>
                            <div class="gif-stat-content">
                                <div class="gif-stat-value"><?php echo $valuta; ?> <?php echo number_format($stats->incasso_contanti, 2, ',', '.'); ?></div>
                                <div class="gif-stat-label"><?php _e('Incasso Contanti', 'gestione-incassi-fiere'); ?></div>
                            </div>
                        </div>
                        
                        <div class="gif-stat-card">
                            <div class="gif-stat-icon">
                                <span class="dashicons dashicons-tickets-alt"></span>
                            </div>
                            <div class="gif-stat-content">
                                <div class="gif-stat-value"><?php echo $valuta; ?> <?php echo number_format($stats->spese_totali, 2, ',', '.'); ?></div>
                                <div class="gif-stat-label"><?php _e('Spese Totali', 'gestione-incassi-fiere'); ?></div>
                            </div>
                        </div>
                        
                        <div class="gif-stat-card">
                            <div class="gif-stat-icon">
                                <span class="dashicons dashicons-chart-area"></span>
                            </div>
                            <div class="gif-stat-content">
                                <div class="gif-stat-value <?php echo ($stats->guadagno_netto_totale < 0) ? 'negative' : 'positive'; ?>">
                                    <?php echo $valuta; ?> <?php echo number_format($stats->guadagno_netto_totale, 2, ',', '.'); ?>
                                </div>
                                <div class="gif-stat-label"><?php _e('Guadagno Netto', 'gestione-incassi-fiere'); ?></div>
                            </div>
                        </div>
                        
                        <div class="gif-stat-card">
                            <div class="gif-stat-icon">
                                <span class="dashicons dashicons-calculator"></span>
                            </div>
                            <div class="gif-stat-content">
                                <div class="gif-stat-value <?php echo ($stats->guadagno_medio_per_fiera < 0) ? 'negative' : 'positive'; ?>">
                                    <?php echo $valuta; ?> <?php echo number_format($stats->guadagno_medio_per_fiera, 2, ',', '.'); ?>
                                </div>
                                <div class="gif-stat-label"><?php _e('Media per Fiera', 'gestione-incassi-fiere'); ?></div>
                            </div>
                        </div>
                        
                        <div class="gif-stat-card">
                            <div class="gif-stat-icon">
                                <span class="dashicons dashicons-bank"></span>
                            </div>
                            <div class="gif-stat-content">
                                <div class="gif-stat-value"><?php echo $valuta; ?> <?php echo number_format($stats->iva_totale + $stats->tasse_totali, 2, ',', '.'); ?></div>
                                <div class="gif-stat-label"><?php _e('Imposte Totali', 'gestione-incassi-fiere'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Grafici principali -->
<div class="gif-box">
    <div class="gif-box-header">
        <h2><?php _e('Andamento Incassi e Guadagni', 'gestione-incassi-fiere'); ?></h2>
    </div>
    <div class="gif-box-content">
        <!-- Importante: Imposta l'altezza direttamente sull'elemento canvas, non solo tramite style -->
        <canvas id="grafico-andamento" width="800" height="350"></canvas>
    </div>
</div>
                
<div class="gif-box">
    <div class="gif-box-header">
        <h2><?php _e('Distribuzione Incassi', 'gestione-incassi-fiere'); ?></h2>
    </div>
    <div class="gif-box-content">
        <!-- Importante: Imposta l'altezza direttamente sull'elemento canvas, non solo tramite style -->
        <canvas id="grafico-distribuzione" width="400" height="350"></canvas>
    </div>
</div>
            
            <!-- Miglior e peggior fiera -->
            <div class="gif-row">
                <div class="gif-col gif-col-6">
                    <div class="gif-box">
                        <div class="gif-box-header">
                            <h2><?php _e('Le 5 Fiere Più Redditizie', 'gestione-incassi-fiere'); ?></h2>
                        </div>
                        <div class="gif-box-content">
                            <?php if (!empty($top_fiere)) : ?>
                                <table class="gif-table">
                                    <thead>
                                        <tr>
                                            <th><?php _e('Nome Fiera', 'gestione-incassi-fiere'); ?></th>
                                            <th><?php _e('Data', 'gestione-incassi-fiere'); ?></th>
                                            <th><?php _e('Incasso', 'gestione-incassi-fiere'); ?></th>
                                            <th><?php _e('Guadagno', 'gestione-incassi-fiere'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($top_fiere as $fiera) : ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-nuova&id=' . $fiera->id); ?>">
                                                        <?php echo esc_html($fiera->nome_fiera); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo date_i18n('d/m/Y', strtotime($fiera->data_fiera)); ?></td>
                                                <td><?php echo $valuta; ?> <?php echo number_format($fiera->incasso_totale, 2, ',', '.'); ?></td>
                                                <td class="positive"><?php echo $valuta; ?> <?php echo number_format($fiera->guadagno_netto, 2, ',', '.'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p class="gif-no-data"><?php _e('Nessun dato disponibile', 'gestione-incassi-fiere'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="gif-col gif-col-6">
                    <div class="gif-box">
                        <div class="gif-box-header">
                            <h2><?php _e('Le 5 Fiere Meno Redditizie', 'gestione-incassi-fiere'); ?></h2>
                        </div>
                        <div class="gif-box-content">
                            <?php if (!empty($bottom_fiere)) : ?>
                                <table class="gif-table">
                                    <thead>
                                        <tr>
                                            <th><?php _e('Nome Fiera', 'gestione-incassi-fiere'); ?></th>
                                            <th><?php _e('Data', 'gestione-incassi-fiere'); ?></th>
                                            <th><?php _e('Incasso', 'gestione-incassi-fiere'); ?></th>
                                            <th><?php _e('Guadagno', 'gestione-incassi-fiere'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bottom_fiere as $fiera) : ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-nuova&id=' . $fiera->id); ?>">
                                                        <?php echo esc_html($fiera->nome_fiera); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo date_i18n('d/m/Y', strtotime($fiera->data_fiera)); ?></td>
                                                <td><?php echo $valuta; ?> <?php echo number_format($fiera->incasso_totale, 2, ',', '.'); ?></td>
                                                <td class="<?php echo ($fiera->guadagno_netto < 0) ? 'negative' : 'positive'; ?>">
                                                    <?php echo $valuta; ?> <?php echo number_format($fiera->guadagno_netto, 2, ',', '.'); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p class="gif-no-data"><?php _e('Nessun dato disponibile', 'gestione-incassi-fiere'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>


<script>
// Attendi che il documento sia completamente caricato
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM caricato, verifico elementi canvas e Chart.js");
    
    // Verifica gli elementi canvas
    var canvas1 = document.getElementById('grafico-andamento');
    var canvas2 = document.getElementById('grafico-distribuzione');
    
    if (!canvas1) {
        console.error("Canvas #grafico-andamento non trovato nel DOM");
        return;
    }
    
    if (!canvas2) {
        console.error("Canvas #grafico-distribuzione non trovato nel DOM");
        return;
    }
    
    // Verifica che Chart.js sia caricato
    if (typeof Chart === 'undefined') {
        console.error("Chart.js non è caricato");
        return;
    }
    
    console.log("Canvas e Chart.js trovati, inizializzazione grafici...");
    
    // Inizializza il grafico andamento
    try {
        var ctx1 = canvas1.getContext('2d');
        if (!ctx1) {
            console.error("Impossibile ottenere il contesto 2d dal canvas #grafico-andamento");
            return;
        }
        
        // Dati per il grafico
        var labels = <?php echo json_encode($labels); ?>;
        var incassi = <?php echo json_encode($incassi); ?>;
        var guadagni = <?php echo json_encode($guadagni); ?>;
        
        console.log("Dati grafico andamento:", {labels, incassi, guadagni});
        
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Incasso Totale',
                        data: incassi,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Guadagno Netto',
                        data: guadagni,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        type: 'line'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        console.log("Grafico andamento inizializzato con successo");
    } catch (error) {
        console.error("Errore durante l'inizializzazione del grafico andamento:", error);
    }
    
    // Inizializza il grafico distribuzione
    try {
        var ctx2 = canvas2.getContext('2d');
        if (!ctx2) {
            console.error("Impossibile ottenere il contesto 2d dal canvas #grafico-distribuzione");
            return;
        }
        
        var incassoContanti = <?php echo $stats->incasso_contanti ? $stats->incasso_contanti : 0; ?>;
        var incassoPOS = <?php echo $stats->incasso_pos ? $stats->incasso_pos : 0; ?>;
        
        console.log("Dati grafico distribuzione:", {incassoContanti, incassoPOS});
        
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Incasso Contanti', 'Incasso POS'],
                datasets: [{
                    data: [incassoContanti, incassoPOS],
                    backgroundColor: [
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 159, 64, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        console.log("Grafico distribuzione inizializzato con successo");
    } catch (error) {
        console.error("Errore durante l'inizializzazione del grafico distribuzione:", error);
    }
});
</script>