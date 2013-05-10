<?php
    class PageHelper {
        function GetPage($path) {
            global $databaseConnection;
    
            if (strpos($path, "?")) {
                $path = substr($path, 0, strpos($path, "?"));
            }
            if (substr($path, strlen($path) - 1) == "/") {
                $path = substr($path, 0, strlen($path) - 1);
            }
    
            $query = 'SELECT id, path, menulabel, pageTitle, content, left_pos, right_pos, showOnMenu FROM pages WHERE path = ? LIMIT 1';
            $statement = $databaseConnection->prepare($query);
            $statement->bind_param('s', $path);
            $statement->execute();
            $statement->store_result();
    
            $page = new Page();
    
            if (!$statement->error && !$statement->num_rows != 1) {
                $statement->bind_result($page->id, $page->path, $page->label, $page->title, $page->content, $page->leftPos, $page->rightPos, $page->showInMenu);
                $statement->fetch();
    
                $statement = $databaseConnection->prepare("select c.id, c.path, c.menulabel, c.pageTitle, '', c.left_pos, c.right_pos, c.showOnMenu
                                                            from 
                                                                pages p
                                                                join pages c on p.left_pos < c.left_pos and p.right_pos > c.right_pos
                                                            where p.id = ?
                                                            order by p.left_pos, c.left_pos");
                $statement->bind_param('i', $page->id);
    
                if ($statement->execute()) {
                    $subPage = new Page();
                    $statement->bind_result($subPage->id, $subPage->path, $subPage->label, $subPage->title, $subPage->content, $subPage->leftPos, $subPage->rightPos, $subPage->showInMenu);
    
                    while($row = $statement->fetch()) {
                        $thisPage = new Page();
    
                        $thisPage->id = $subPage->id;
                        $thisPage->path = $subPage->path;
                        $thisPage->label = $subPage->label;
                        $thisPage->title = $subPage->title;                        
    
                        $page->subPages[] = $thisPage;
                    }
                }
    
                $statement = $databaseConnection->prepare("SELECT id, imagePath, imageDesc, content, sortOrder FROM pagebanners pb WHERE pb.pageId = ? ORDER BY pb.sortOrder;");
                $statement->bind_param('i', $page->id);
    
                if ($statement->execute()) {
                    $statement->bind_result($id, $imagePath, $imageDesc, $content, $sortOrder);
    
                    while($row = $statement->fetch()) {
                        $banner = new PageBanner();
    
                        $banner->id = $id;
                        $banner->imagePath = $imagePath;
                        $banner->imageDesc = $imageDesc;
                        $banner->content = $content;
                        $banner->sortOrder = $sortOrder;                        
    
                        $page->banners[] = $banner;
                    }
                }
            } 
    
            return $page;
        }
    
    
        function BuildMenu() {
            global $databaseConnection;
    
            //Get an array of all of the pages.
            $pages = array();
    
            $query = 'SELECT id, path, menulabel, pageTitle, left_pos, right_pos FROM pages WHERE showOnMenu = 1 ORDER BY left_pos ASC;';
            $statement = $databaseConnection->prepare($query);
    
            if ($statement->execute()) {
                $statement->bind_result($id, $path, $label, $title, $leftPos, $rightPos);
    
                while($row = $statement->fetch()) {
                    $page = new Page();
    
                    $page->id = $id;
                    $page->path = $path;
                    $page->label = $label;
                    $page->title = $title;
                    $page->leftPos = $leftPos;
                    $page->rightPos = $rightPos;
    
                    $pages[] = $page;
                }
            }
    
            for($i = 0; $i < count($pages); $i++) {
                echo "<li><a href=\"". ($pages[$i]->path ? $pages[$i]->path : "/") ."\">". $pages[$i]->label ."</a>";
    
                if ($i + 1 < count($pages) && $pages[$i+1]->rightPos < $pages[$i]->rightPos) {
                    $children = $pages[$i]->rightPos - $pages[$i+1]->rightPos;
    
                    echo "<ul>";
                    for($x = 0; $x < $children; $x++) {
                        echo "<li><a href=\"". $pages[$i]->path ."\">". $pages[$i]->label ."</a>";
                        $i++;
                    }
                    echo "</ul>";
                    $i--;
                }
    
                echo "</li>";
            }
        }
    
        function UpdatePage($pageId, $pageTitle, $content) {
            global $databaseConnection;
    
            $query = "UPDATE pages SET pageTitle = ?, content = ? WHERE Id = ?";
            $statement = $databaseConnection->prepare($query);
            $statement->bind_param('ssi', $pageTitle, $content, $pageId);
            $statement->execute();
    
            $statement->store_result();
            return (!$statement->error && $statement->affected_rows == 1);
        }
    
        function PageHeading($title) {
            return (is_admin() && $this->isEditable()) ? "<input name=\"pageTitle\" class=\"wysiwyg_heading\" value=\"". $title ."\" />" : $title;
        }
    
        function wysiwyg($content) {
            return (is_admin() && $this->isEditable()) ? "<textarea name=\"content\" class=\"wysiwyg ckeditor cms_content\">". $content ."</textarea>" : "<div class=\"cms_content\">$content</div>";
        }
    
        function IsEditable() {
            return (isset($_POST["edit"]) || isset($_POST["save"]));
        }
    
        function EditPageButton() {
            if (is_admin()) { 
                if ($this->isEditable()) {
                    echo "<div id=\"edit-page\"><button type=\"submit\" name=\"save\" title=\"Save your changes\" /><button type=\"submit\" name=\"add\" title=\"add a sub-page\" /><button type=\"submit\" name=\"delete\" onclick=\"if (!confirm('Are you sure you want to delete this page?')) return false;\" title=\"delete this page\" /></div>"; 
                } else {
                    echo "<div id=\"edit-page\"><button type=\"submit\" name=\"edit\" /><button type=\"submit\" name=\"add\" title=\"add a new page\" /></div>"; 
                }
            }
        }       
    }
    
    class Page {
        public $id = null;
        public $path = null;
        public $title = null;
        public $label = null;
        public $content = null;
        public $leftPos = null;
        public $rightPos = null;
        public $showInMenu = null;
    
        public $subPages = array();
        public $banners = array();
    }
    
    class PageBanner {
        public $id = NULL;
        public $imagePath = NULL;
        public $imageDesc = NULL;
        public $content = NULL;
        public $sortOrder = NULL;
    }   
?>