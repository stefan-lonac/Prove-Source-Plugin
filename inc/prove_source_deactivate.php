<?php

/**
* Trigger this file on Plugin Deactivate
* @package ProveSource
*/

class ProveSourceDeactivate
{

    public static function deactivate() {
        flush_rewrite_rules();
    }

}