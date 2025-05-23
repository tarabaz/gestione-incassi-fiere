<!-- templates/partials/navigation.php -->
<?php
// Ottieni la pagina corrente
$current_page = isset($_GET['page']) ? $_GET['page'] : '';

// Definisci gli elementi del menu
$menu_items = array(
    'gestione-incassi-fiere' => array(
        'title' => __('Dashboard', 'gestione-incassi-fiere'),
        'icon' => 'dashicons-chart-area'
    ),
    'gestione-incassi-fiere-elenco' => array(
        'title' => __('Elenco Fiere', 'gestione-incassi-fiere'),
        'icon' => 'dashicons-list-view'
    ),
    'gestione-incassi-fiere-nuova' => array(
        'title' => __('Aggiungi Nuova', 'gestione-incassi-fiere'),
        'icon' => 'dashicons-plus-alt'
    ),
    'gestione-incassi-fiere-stats' => array(
        'title' => __('Statistiche', 'gestione-incassi-fiere'),
        'icon' => 'dashicons-chart-line'
    ),
    'gestione-incassi-fiere-impostazioni' => array(
        'title' => __('Impostazioni', 'gestione-incassi-fiere'),
        'icon' => 'dashicons-admin-settings'
    )
);
?>

<div class="gif-navigation">
    <ul class="gif-nav-tabs">
        <?php foreach ($menu_items as $page => $item) : ?>
            <li class="gif-nav-tab <?php echo ($current_page === $page) ? 'active' : ''; ?>">
                <a href="<?php echo admin_url('admin.php?page=' . $page); ?>">
                    <span class="dashicons <?php echo $item['icon']; ?>"></span>
                    <span class="gif-nav-label"><?php echo $item['title']; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>