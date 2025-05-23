<?php
/**
 * Classe per il caricamento degli hook
 *
 * @since 2.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class GIF_Loader {
    
    /**
     * Array di azioni
     */
    protected $actions;
    
    /**
     * Array di filtri
     */
    protected $filters;
    
    /**
     * Costruttore
     */
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
    }
    
    /**
     * Aggiungi un'azione
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }
    
    /**
     * Aggiungi un filtro
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }
    
    /**
     * Metodo privato per aggiungere un hook
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );
        
        return $hooks;
    }
    
    /**
     * Esegui tutti gli hook registrati
     */
    public function run() {
        // Aggiungi le azioni
        foreach ($this->actions as $hook) {
            add_action(
                $hook['hook'],
                array($hook['component'], $hook['callback']),
                $hook['priority'],
                $hook['accepted_args']
            );
        }
        
        // Aggiungi i filtri
        foreach ($this->filters as $hook) {
            add_filter(
                $hook['hook'],
                array($hook['component'], $hook['callback']),
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }
}