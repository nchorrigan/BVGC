<?php
    include("../includes/_init.php"); 
    
    $eventHelper = new EventHelper();
    $event = new Event();
    $event->date = date("Y-m-d");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["id"])) {
            //Update the existing event;
            $eventHelper->UpdateEvent($_POST["id"], $_POST["title"], strtotime($_POST["date"] ." ". $_POST["time"]), $_POST["summary"], $_POST["content"]);
        } else {
            //Create a new event;
            $eventHelper->CreateEvent($_POST["title"], strtotime($_POST["date"] ." ". $_POST["time"]), $_POST["summary"], $_POST["content"]);
        }
    } else {
        if (isset($_GET["id"])) {
            $event = $eventHelper->GetEvent($_GET["id"]);    
        } 
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $event->title; ?></title>
        <link rel="stylesheet" href="/css/typography.css" />
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
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
            
            #files UL { padding: 0; margin: 0 0 0 4em; }
            #files LI { float: left; margin: 0 1.5em 0 0; list-style: none; }
            
            .formsection {
                width: 100%;
                margin: 4px 0 4px 0;
            }
            .formsection LABEL {
                width: 9em;
                display: inline-block;
                font-weight: bold;
                float: left;
            }
            .detail LABEL {
                float: none;
            }
        </style>
        <script type="text/javascript">
            $(function () {
                $('#files').on('click', 'A', function (e) {
                    e.preventDefault();

                    if ($(this).hasClass('addfile')) {
                        window.KCFinder = {};
                        window.KCFinder.callBack = function (url) {
                            var filename = decodeURI(url.substring(url.lastIndexOf('/') + 1));

                            $.ajax({
                                url: './update.php',
                                type: 'POST',
                                data: {
                                    action: 'addfile',
                                    id: $('INPUT[name=id]').val(),
                                    name: filename,
                                    path: url
                                },
                                success: function () {
                                    var li = $('<LI />');

                                    $('<A />')
                                        .attr({ href: url })
                                        .html(filename)
                                        .appendTo(li);

                                    li.appendTo($('#files').find('UL'))
                                }
                            });

                            window.KCFinder = null;
                        };
                        window.open('/kcfinder/browse.php?type=events', 'kcfinder_single', 'width=600,height=500');
                    } else if ($(this).hasClass('removefile')) {
                        alert($(this).data('fileid'));
                    }
                });
            });            
        </script>
    </head>
    <body>
        <div id="info_popup">
            <?php if(!(is_admin())) { ?>
            <h1><?php echo $event->title; ?></h1>
            <h4><?php echo $event->formattedDate(); ?></h4>

            <?php if (strtolower($event->type) == "events" || strtolower($event->type) == "competitions") { ?>
            <div class="eventinfo_options"><a href="./reminder.php?start=<?php echo $event->date; ?>&amp;end=<?php echo $event->date; ?>&amp;name=<?php echo $event->title; ?>&amp;desc=<?php echo $event->summary; ?>">add to your calendar</a></div>
            <?php } ?>

            <span class="cms_content"><?php echo $event->content; ?></span>

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
            <?php } else { ?>
            <form action="./info.php" method="post" style="padding: 1em">
                <input type="hidden" name="id" value="<?php echo $event->id; ?>" />

                <div style="float: right">
                    <button type="submit">Save</button>
                    <button type="submit" name="delete">Delete</button>
                    <button type="button" onclick="window.close();">Cancel</button>
                </div>

                <div class="formsection">
                    <label for="title">Title:</label> 
                    <input type="text" name="title" id="title" size="40" maxlength="200" value="<?php echo $event->title; ?>" />
                </div>
                <?php if ($event->type != "News") {?>
                    <div class="formsection">
                        <label for="date">Date:</label>
                        <input type="date" name="date" id="date" value="<?php echo $event->formattedDate("Y-m-d"); ?>" />
                        <input type="time" name="time" value="<?php echo $event->formattedDate("H:i"); ?>" />
                    </div>
                <?php } ?>
                <div class="formsection">
                    <label for="summary">Summary:</label> 
                    <textarea name="summary" id="summary" cols="50" rows="5"><?php echo $event->summary; ?></textarea>
                </div>
                <div id="files" class="formsection">
                    <label>Associated files:</label> 
                    <?php if($event->id == NULL) { ?>
                        <em>you need to save this event before you add files</em>
                    <?php } else { ?>
                        <a href="#" class="addfile">[ + ]</a>
                        <ul>
                            <?php if (count($event->files) > 0 && $event->id != null) { ?>
                                <?php foreach($event->files as $file) { ?>
                                <li><a href="<?php echo $file->path; ?>"><?php echo $file->name; ?></a> <a href="#" class="removefile" data-fileid="<?php echo $file->id; ?>">[x]</a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul> 
                    <?php } ?>
                    <div style="clear: both; width: 100%; height: 1px;"></div>
                </div>
                <div class="formsection detail">
                    <label for="content">Event details:</label>
                    <textarea name="content" id="content" class="wysiwyg ckeditor"><?php echo $event->content; ?></textarea>
                </div>
            </form>
            <?php } ?>
        </div>
    </body>
</html>

<?php include("../includes/_dispose.php"); ?>