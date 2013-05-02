<?php
    include("../includes/_init.php"); 
    
    $eventHelper = new EventHelper();
    $event = $eventHelper->GetEvent($_GET["id"]);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $event->title; ?></title>
        <link rel="stylesheet" href="/css/typography.css" />
        <style type="text/css">
            BODY {
                padding-left: 1em;
                margin: 0;
                position: relative;
            }
            
            #info_popup H1, #info_popup H2, #info_popup H3, #info_popup H4 {
                margin-top: .2em;
                margin-bottom: .4em;
                color: #800;
            }
            
            #info_popup H1 {
                font-size: 1.6em;
            }
            
            .eventinfo_options {
                position: absolute;
                top: 0;
                right: 0;
                margin:  1em;
            }
            
            #eventinfo_footer {
                position: absolute;
                width: 100%;
                bottom: 0;
                left: 0;
                border-top: 1px solid #000; 
                background-color: #e6e0e0;
                padding-bottom: 1.6em;
            }
            
            #eventinfo_files {
                padding: .6em 0 .8em 1em;
            }
            
             #eventinfo_files LI {
                list-style: none;
                float: left;
                margin-right: 1.5em;
            }
        </style>
    </head>
    <body>
        <div id="info_popup">
            <h1><?php echo $event->title; ?></h1>
            <h4><?php echo $event->formattedDate(); ?></h4>

            <?php if (strtolower($event->type) == "events" || strtolower($event->type) == "competitions") { ?>
            <div class="eventinfo_options"><a href="./reminder.php?start=<?php echo $event->date; ?>&amp;end=<?php echo $event->date; ?>&amp;name=<?php echo $event->title; ?>&amp;desc=<?php echo $event->summary; ?>">add to your calendar</a></div>
            <?php } ?>

            <?php if (count($event->files) > 0) { ?>
            <div id="eventinfo_footer">
                <div id="eventinfo_files">
                    <h4>Associated files:</h4>
                    <ul>
                        <?php foreach($event->files as $file) { ?>
                        <li><a href="<?php echo $file->path; ?>"><?php echo $file->name; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
            <span class="cms_content"><?php echo $event->content; ?></span>
        </div>
    </body>
</html>

<?php include("../includes/_dispose.php"); ?>