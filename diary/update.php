<?php
    require_once("../includes/_init.php");
    
    if (is_admin()) {
        $eventHelper = new EventHelper();

        switch($_POST["action"]) {
            //Add image to event.
            case "addfile": {
                $eventHelper->AddFile($_POST["id"], $_POST["name"], $_POST["path"]);
            } break;
        }
    }

    require_once("../includes/_dispose.php");
?>