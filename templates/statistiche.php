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
            <div class="gif-row">
                <div class="gif-col gif-col-8">
                    <div class="gif-box">
                        <div class="gif-box-header">
                            <h2><?php _e('Andamento Incassi e Guadagni', 'gestione-incassi-fiere'); ?></h2>
                        </div>
                        <div class="gif-box-content">
                            <div id="grafico-andamento" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="gif-col gif-col-4">
                    <div class="gif-box">
                        <div class="gif-box-header">
                            <h2><?php _e('Distribuzione Incassi', 'gestione-incassi-fiere'); ?></h2>
                        </div>
                        <div class="gif-box-content">
                            <div id="grafico-distribuzione" style="height: 350px;"></div>
                        </div>
                    </div>
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
<!-- Modifica alla pagina statistiche.php per aggiungere debug e fix -->
<script>
jQuery(document).ready(function($) {
    <?php if (!empty($stats->totale_fiere)) : ?>
    
    console.log("Inizializzazione grafici delle statistiche...");
    
    // Dati per i grafici
    var periodi = <?php echo json_encode($labels); ?>;
    var incassi = <?php echo json_encode($incassi); ?>;
    var guadagni = <?php echo json_encode($guadagni); ?>;
    
    console.log("Dati grafici:", {
        periodi: periodi,
        incassi: incassi,
        guadagni: guadagni,
        incasso_contanti: <?php echo $stats->incasso_contanti; ?>,
        incasso_pos: <?php echo $stats->incasso_pos; ?>
    });
    
    // Se non ci sono periodi definiti, mostra un messaggio
    if (periodi.length === 0) {
        $("#grafico-andamento").html('<div style="text-align: center; padding: 50px 20px;"><p style="color: #646970; font-size: 16px;">Non ci sono dati sufficienti per visualizzare il grafico</p></div>');
        $("#grafico-distribuzione").html('<div style="text-align: center; padding: 50px 20px;"><p style="color: #646970; font-size: 16px;">Non ci sono dati sufficienti per visualizzare il grafico</p></div>');
    } else {
        // Grafico andamento
        var ctx = document.getElementById('grafico-andamento').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: periodi,
                datasets: [
                    {
                        label: '<?php _e('Incasso Totale', 'gestione-incassi-fiere'); ?> (<?php echo $valuta; ?>)',
                        data: incassi,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '<?php _e('Guadagno Netto', 'gestione-incassi-fiere'); ?> (<?php echo $valuta; ?>)',
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
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '<?php echo $valuta; ?> ' + value.toLocaleString('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '<?php echo $valuta; ?> ' + context.parsed.y.toLocaleString('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                return label;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    }
    
    // Verifica se ci sono incassi per il grafico a torta
    var incassoContanti = <?php echo $stats->incasso_contanti; ?>;
    var incassoPOS = <?php echo $stats->incasso_pos; ?>;
    
    if (incassoContanti === 0 && incassoPOS === 0) {
        $("#grafico-distribuzione").html('<div style="text-align: center; padding: 50px 20px;"><p style="color: #646970; font-size: 16px;">Non ci sono dati di incasso per visualizzare il grafico</p></div>');
    } else {
        // Grafico distribuzione
        var ctx2 = document.getElementById('grafico-distribuzione').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['<?php _e('Incasso Contanti', 'gestione-incassi-fiere'); ?>', '<?php _e('Incasso POS', 'gestione-incassi-fiere'); ?>'],
                datasets: [{
                    data: [
                        incassoContanti, 
                        incassoPOS
                    ],
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                var value = context.parsed;
                                var total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                var percentage = Math.round((value / total) * 100);
                                
                                label += '<?php echo $valuta; ?> ' + value.toLocaleString('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                label += ' (' + percentage + '%)';
                                
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
    
    <?php endif; ?>
});
</script>