<?php 
    require_once ("../Includes/simplecms-config.php"); 
    require_once  ("../Includes/connectDB.php");
    
    $pageTitle = null;
    $currentPageId = $_REQUEST["currentPageId"];
    
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $query = 'SELECT pageTitle FROM pages WHERE id = ? LIMIT 1';
        $statement = $databaseConnection->prepare($query);
        $statement->bind_param('s', $currentPageId);
        
        $statement->execute();
        $statement->store_result();
        
        if ($statement->error)
            die('Database query failed: ' . $statement->error);

        if ($statement->num_rows != 1)
            header("Location: /FileNotFound.html");
        
        $statement->bind_result($pageTitle);
        $statement->fetch();
    }
?>
<style type="text/css">
    #add-page-wrapper .lbl {
        min-width: 10em;
        display: inline-block;
    }
    
    #add-page-wrapper UL {
        width:60%;
    }
    
    #add-page-wrapper UL UL {
        width: auto;
    }
    
    #add-page-wrapper UL SPAN { float:right; }
    
    #menu_options UL {
        list-style: none;            
    }
    
    #other-pages {
        margin-left:3em;
    }
</style>

<div id="add-page-wrapper">
    <form id="addpage-form">
    <div style="float:right">
        <button type="submit" name="save">Save</button>
    </div>
    
    <h1>New page</h1>
    
    <p>
        <label class="lbl" for="page-title">Page title</label><input type="text" id="page-title" name="title" />
    </p>
    <p>
        <label class="lbl" for="menu-title">Menu label</label><input type="text" id="menu-title" name="title" />
    </p>
   
            <p>
                <span class="lbl">Select a location to put this page:</span>
                <ul>
                    <li>
                        <input type="radio" name="page-location" id="page-location-top" value="top" /> 
                        <label for="page-location-top">put page at the top level</label>
                    </li>
                    <li>
                        <input type="radio" name="page-location" id="page-location-under-current" value="under-current" />
                        <label for="page-location-under-current">put page under <strong><?php echo $pageTitle; ?></strong></label>
                    </li>
                    <!--<li>
                        <input type="radio" name="page-location" id="page-location-else" value="other" />
                        <label for="page-location-else">or choose somewhere else</label>
                    </li>-->
                </ul>
                
                <ul id="other-pages" style="display: none">
                    <li><a>HOME</a></li>
                    <li><a>CLUBHOUSE</a></li>
                    <li><a>THE COURSE</a></li>
                    <li><a>MEMBERSHIP</a>
                        <ul>
                            <li><a>MEMBERSHIP TYPES</a></li>
                            <li><a>MEMBERSHIP FEES</a></li>
                        </ul>
                    </li>
                    <li><a>VISITORS</a></li>
                    <li><a>CONTACT US</a></li>
                </ul>
                <div class="platform" />
            </p>
        </div>
    </p>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('INPUT[name=include-menu]').live('click', function() { 
            $(this).is(':checked') ? $('.menu-detail').show() : $('.menu-detail').hide();
        });
        
        $('#addpage-form').submit(function(e) {
            alert(1);
            e.preventDefault();
        });
    });
</script>