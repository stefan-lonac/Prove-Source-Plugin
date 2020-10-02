<?php 
/**
* Trigger this file on Plugin Activate
* @package ProveSource
*/

class ProveSourceActivate
{

    public static function activate() {
        flush_rewrite_rules();
    }

}