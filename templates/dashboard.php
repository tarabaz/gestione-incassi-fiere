<!-- templates/dashboard.php -->
<div class="wrap gif-admin">
    <h1 class="gif-page-title">
        <span class="dashicons dashicons-chart-area"></span>
        <?php _e('Dashboard Incassi Fiere', 'gestione-incassi-fiere'); ?>
    </h1>
    
    <?php include('partials/navigation.php'); ?>
    
    <div class="gif-dashboard">
        <!-- Cards Principali -->
        <div class="gif-card-grid">
            <div class="gif-card">
                <div class="gif-card-header">
                    <div class="gif-card-icon">
                        <span class="dashicons dashicons-money-alt"></span>
                    </div>
                    <div class="gif-card-title"><?php _e('Incasso Totale', 'gestione-incassi-fiere'); ?></div>
                </div>
                <div class="gif-card-content">
                    <div class="gif-card-value">
                        <?php echo $valuta; ?> <?php echo number_format($stats->incasso_totale, 2, ',', '.'); ?>
                    </div>
                    <div class="gif-card-description">
                        <?php echo $valuta; ?> <?php echo number_format($stats->incasso_contanti, 2, ',', '.'); ?> <?php _e('in contanti', 'gestione-incassi-fiere'); ?> + 
                        <?php echo $valuta; ?> <?php echo number_format($stats->incasso_pos, 2, ',', '.'); ?> <?php _e('con POS', 'gestione-incassi-fiere'); ?>
                    </div>
                </div>
            </div>
            
            <div class="gif-card">
                <div class="gif-card-header">
                    <div class="gif-card-icon">
                        <span class="dashicons dashicons-chart-line"></span>
                    </div>
                    <div class="gif-card-title"><?php _e('Guadagno Netto', 'gestione-incassi-fiere'); ?></div>
                </div>
                <div class="gif-card-content">
                    <div class="gif-card-value <?php echo ($stats->guadagno_netto_totale < 0) ? 'negative' : 'positive'; ?>">
                        <?php echo $valuta; ?> <?php echo number_format($stats->guadagno_netto_totale, 2, ',', '.'); ?>
                    </div>
                    <div class="gif-card-description">
                        <?php _e('Media per fiera', 'gestione-incassi-fiere'); ?>: <?php echo $valuta; ?> <?php echo number_format($stats->guadagno_medio_per_fiera, 2, ',', '.'); ?>
                    </div>
                </div>
            </div>
            
            <div class="gif-card">
                <div class="gif-card-header">
                    <div class="gif-card-icon">
                        <span class="dashicons dashicons-calendar-alt"></span>
                    </div>
                    <div class="gif-card-title"><?php _e('Fiere Totali', 'gestione-incassi-fiere'); ?></div>
                </div>
                <div class="gif-card-content">
                    <div class="gif-card-value">
                        <?php echo $stats->totale_fiere; ?>
                    </div>
                    <div class="gif-card-description">
                        <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-elenco'); ?>"><?php _e('Visualizza tutte', 'gestione-incassi-fiere'); ?></a>
                    </div>
                </div>
            </div>
            
            <div class="gif-card">
                <div class="gif-card-header">
                    <div class="gif-card-icon">
                        <span class="dashicons dashicons-arrow-up-alt"></span>
                    </div>
                    <div class="gif-card-title"><?php _e('Migliore Prestazione', 'gestione-incassi-fiere'); ?></div>
                </div>
                <div class="gif-card-content">
                    <div class="gif-card-value positive">
                        <?php echo $valuta; ?> <?php echo number_format($miglior_fiera->guadagno_netto, 2, ',', '.'); ?>
                    </div>
                    <div class="gif-card-description">
                        <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-nuova&id=' . $miglior_fiera->id); ?>">
                            <?php echo esc_html($miglior_fiera->nome_fiera); ?> (<?php echo date_i18n('d/m/Y', strtotime($miglior_fiera->data_fiera)); ?>)
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Grafico e Ultime Fiere -->
        <div class="gif-row">
            <div class="gif-col gif-col-8">
                <div class="gif-box">
                    <div class="gif-box-header">
                        <h2><?php _e('Andamento Ultimi 12 Mesi', 'gestione-incassi-fiere'); ?></h2>
                    </div>
                    <div class="gif-box-content">
                        <div id="grafico-andamento" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="gif-col gif-col-4">
                <div class="gif-box">
                    <div class="gif-box-header">
                        <h2><?php _e('Ultime Fiere', 'gestione-incassi-fiere'); ?></h2>
                    </div>
                    <div class="gif-box-content">
                        <?php if (empty($ultime_fiere)) : ?>
                            <p class="gif-no-data"><?php _e('Nessuna fiera registrata', 'gestione-incassi-fiere'); ?></p>
                        <?php else : ?>
                            <ul class="gif-list">
                                <?php foreach ($ultime_fiere as $fiera) : ?>
                                    <li class="gif-list-item">
                                        <div class="gif-list-title">
                                            <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-nuova&id=' . $fiera->id); ?>">
                                                <?php echo esc_html($fiera->nome_fiera); ?>
                                            </a>
                                        </div>
                                        <div class="gif-list-meta">
                                            <span class="gif-date"><?php echo date_i18n('d/m/Y', strtotime($fiera->data_fiera)); ?></span>
                                            <span class="gif-amount <?php echo ($fiera->guadagno_netto < 0) ? 'negative' : 'positive'; ?>">
                                                <?php echo $valuta; ?> <?php echo number_format($fiera->guadagno_netto, 2, ',', '.'); ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="gif-box-footer">
                                <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-elenco'); ?>" class="gif-button gif-button-sm">
                                    <?php _e('Vedi tutte', 'gestione-incassi-fiere'); ?>
                                </a>
                                <a href="<?php echo admin_url('admin.php?page=gestione-incassi-fiere-nuova'); ?>" class="gif-button gif-button-sm gif-button-primary">
                                    <?php _e('Aggiungi Nuova', 'gestione-incassi-fiere'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dettagli Fiscali -->
        <div class="gif-row">
            <div class="gif-col gif-col-12">
                <div class="gif-box">
                    <div class="gif-box-header">
                        <h2><?php _e('Dettagli Fiscali', 'gestione-incassi-fiere'); ?></h2>
                    </div>
                    <div class="gif-box-content">
                        <div class="gif-grid-stats">
                            <div class="gif-stat-item">
                                <div class="gif-stat-label"><?php _e('Spese Totali', 'gestione-incassi-fiere'); ?></div>
                                <div class="gif-stat-value"><?php echo $valuta; ?> <?php echo number_format($stats->spese_totali, 2, ',', '.'); ?></div>
                            </div>
                            
                            <div class="gif-stat-item">
                                <div class="gif-stat-label"><?php _e('IVA Totale', 'gestione-incassi-fiere'); ?></div>
                                <div class="gif-stat-value"><?php echo $valuta; ?> <?php echo number_format($stats->iva_totale, 2, ',', '.'); ?></div>
                            </div>
                            
                            <div class="gif-stat-item">
                                <div class="gif-stat-label"><?php _e('Tasse Totali', 'gestione-incassi-fiere'); ?></div>
                                <div class="gif-stat-value"><?php echo $valuta; ?> <?php echo number_format($stats->tasse_totali, 2, ',', '.'); ?></div>
                            </div>
                            
                            <div class="gif-stat-item">
                                <div class="gif-stat-label"><?php _e('Incasso Contanti', 'gestione-incassi-fiere'); ?></div>
                                <div class="gif-stat-value"><?php echo $valuta; ?> <?php echo number_format($stats->incasso_contanti, 2, ',', '.'); ?></div>
                            </div>
                            
                            <div class="gif-stat-item">
                                <div class="gif-stat-label"><?php _e('Incasso POS', 'gestione-incassi-fiere'); ?></div>
                                <div class="gif-stat-value"><?php echo $valuta; ?> <?php echo number_format($stats->incasso_pos, 2, ',', '.'); ?></div>
                            </div>
                            
                            <div class="gif-stat-item">
                                <div class="gif-stat-label"><?php _e('Profittabilità Media', 'gestione-incassi-fiere'); ?></div>
                                <div class="gif-stat-value">
                                    <?php 
                                    if ($stats->incasso_totale > 0) {
                                        $profittabilita = ($stats->guadagno_netto_totale / $stats->incasso_totale) * 100;
                                        echo number_format($profittabilita, 2, ',', '.') . '%';
                                    } else {
                                        echo '0,00%';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Prepara i dati per il grafico
    var periodi = <?php 
        $labels = array();
        $incassi = array();
        $guadagni = array();
        
        foreach ($dati_grafico as $dato) {
            $labels[] = $dato['periodo'];
            $incassi[] = $dato['incasso'];
            $guadagni[] = $dato['guadagno'];
        }
        
        echo json_encode($labels); 
    ?>;
    
    var incassi = <?php echo json_encode($incassi); ?>;
    var guadagni = <?php echo json_encode($guadagni); ?>;
    
    // Creazione del grafico
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
                    borderWidth: 1
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
                }
            }
        }
    });
});
</script>