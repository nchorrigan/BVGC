<?php
    
    require_once("../includes/_init.php");
    
    $helper = new PageHelper();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    }
?>

<div id="add_page">
    <form action="/admin/addpage.php" method="post">
        <fieldset>
            <legend>Step 1. Page location</legend>

            <div class="fieldset_content">
                <p>Whereabouts on the site do you want to add the page?</p>

                <ul>
                    <li><label><input type="radio" name="location" value="parent" required />
                        At the top level</label></li>
                    <li><label><input type="radio" name="location" value="child" checked="checked" required />
                        Under an existing page</label></li>
                </ul>

                <div class="top_level">
                    <p class="new_parent" style="display: none">Select the page you want to appear after</p>
                    <p class="new_child">Select the section you want the new page to appear under</p>

                    <ul>
                        <?php
                            $pages = $helper->GetParentPages();
                            for($i = 0; $i < count($pages); $i++) {
                                echo "<li><label><input type=\"radio\" name=\"pageid\" value=\"". $pages[$i]->id ."\" required title=\"Please select a page\" />". $pages[$i]->label ."</label></li>";
                            }
                        ?>
                    </ul>
                </div>
                <button name="step" type="submit" value="next" style="float: right">next &raquo;</button>
            </div>
        </fieldset>

        <fieldset>
            <legend>Step 2. Page name</legend>

            <div class="fieldset_content" style="display: none">
                <p>Now we need to give the page a name</p>
                
                <input type="text" id="pageLabel" name="pageLabel" required title="Please enter a label" />
                <label for="pageLabel">Menu label</label><br />

                <input type="text" id="pageTitle" name="pageTitle" required title="Please enter a title" />
                <label for="pageTitle">Page title</label><br />

                <button name="step" value="save" type="submit" style="float: right">CREATE PAGE</button>
                <button name="step" value="prev" type="submit">&laquo; back</button>
            </div>
        </fieldset>
    </form>
</div>

<?php include ("../includes/_dispose.php"); ?>