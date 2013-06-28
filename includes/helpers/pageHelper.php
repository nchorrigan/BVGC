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
    
            $query = 'SELECT id, parentPageId, sortOrder, showOnMenu, path, menulabel, pageTitle, content FROM pages WHERE path = ? LIMIT 1';
            $statement = $databaseConnection->prepare($query);
            $statement->bind_param('s', $path);
            $statement->execute();
            $statement->store_result();
    
            $page = new Page();
    
            if (!$statement->error && !$statement->num_rows != 1) {
                $statement->bind_result($page->id, $page->parentPageId, $page->sortOrder, $page->showInMenu, $page->path, $page->label, $page->title, $page->content);
                $statement->fetch();
    
                $statement = $databaseConnection->prepare("select p.id, p.path, p.menulabel, p.pageTitle, '', p.showOnMenu
                                                            from 
                                                                pages p
                                                            where p.parentPageId = ?
                                                            order by p.sortOrder");
                $statement->bind_param('i', $page->id);
    
                if ($statement->execute()) {
                    $subPage = new Page();
                    $statement->bind_result($subPage->id, $subPage->path, $subPage->label, $subPage->title, $subPage->content, $subPage->showInMenu);
    
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
    
        function GetParentPages(){
            global $databaseConnection;
    
            //Get an array of all of the pages.
            $pages = array();
    
            $query = 'SELECT id, path, menulabel, pageTitle FROM pages WHERE parentPageId IS NULL AND showOnMenu = 1 ORDER BY sortOrder ASC;';
            $statement = $databaseConnection->prepare($query);
    
            if ($statement->execute()) {
                $statement->bind_result($id, $path, $label, $title);
   
                while($row = $statement->fetch()) {
                    $page = new Page();
    
                    $page->id = $id;
                    $page->path = $path;
                    $page->label = $label;
                    $page->title = $title;
    
                    $pages[] = $page;
                }
            }

            return $pages;
        }

        function BuildMenu($hideAdmin = false) {
            global $databaseConnection;
    
            //Get an array of all of the pages.
            $pages = $this->GetParentPages();
    
            for($i = 0; $i < count($pages); $i++) {
                echo "<li><a href=\"". ($pages[$i]->path ? $pages[$i]->path : "/") ."\">". $pages[$i]->label ."</a>";
    
                $query = 'SELECT id, path, menulabel, pageTitle FROM pages WHERE parentPageId = ? AND showOnMenu = 1 ORDER BY sortOrder ASC;';
                $statement = $databaseConnection->prepare($query);
                $statement->bind_param('i', $pages[$i]->id);
                $statement->execute();
                $statement->store_result();

                if (!$statement->error && !$statement->num_rows != 1) {
                    $statement->bind_result($id, $path, $label, $title);
                   
                    echo "<ul>";
                    echo "<li><a href=\"". ($pages[$i]->path ? $pages[$i]->path : "/") ."\">". $pages[$i]->label ."</a>";
                    while($row = $statement->fetch()) {    
                        echo "<li><a href=\"". $path ."\">". $label ."</a>";
                    }

                    echo "</ul>";
                }
    
                echo "</li>";
            }
    
            if (!$hideAdmin && is_admin()) {
                echo "<li><a href=\"/admin/\" style=\"font-weight: bold\">ADMIN</a>";
                echo "<ul>";
                echo    "<li><a class=\"admin\" data-action=\"add_page\" href=\"/admin/addpage.php\">Add a new page</a></li>";
                echo    "<li><a href=\"/admin/logoff.php\">LOGOUT</a></li>";
                echo "</ul>";
                echo "</li>";
            }
        }
    
        function CreatePage($parentPageId, $label, $title) {
            global $databaseConnection;
    
            if ($parentPageId > 0) {
                $path = "";
            } else {
                $path = "/". ;
            }

            $query = "INSERT INTO pages (parentPageId, label, title) values(?, ?, ?)";
            $statement = $databaseConnection->prepare($query);
            $statement->bind_param('iss', $parentPageId, $label, $title);
            $statement->execute();
    
            $statement->store_result();
            return (!$statement->error && $statement->affected_rows == 1);
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
    
        function GetBanner($bannerId) {
            global $databaseConnection;
    
            $query = "SELECT id, imagePath, imageDesc, content, sortOrder FROM pagebanners WHERE id = ?;";
            $statement = $databaseConnection->prepare($query);
            $statement->bind_param('i', $bannerId);
            $statement->execute();    
            $statement->store_result();
    
            $banner = new PageBanner();
    
            if (!$statement->error && !$statement->num_rows != 1) {
                $statement->bind_result($banner->id, $banner->imagePath, $banner->imageDesc, $banner->content, $page->sortOrder);
                $statement->fetch();
            }
    
            return $banner;
        }
    
        function AddBanner($pageId, $path) {
            global $databaseConnection;
    
            $query = "INSERT INTO pagebanners (pageId, imagePath, sortOrder) SELECT ?, ?, (MAX(sortOrder) + 1) FROM pageBanners WHERE pageId = ?;";
            $statement = $databaseConnection->prepare($query);
            $statement->bind_param('isi', $pageId, $path, $pageId);
            $statement->execute();
    
            $statement->store_result();
            return (!$statement->error && $statement->affected_rows == 1);
        }
    
        function UpdateBanner($bannerId, $content, $imagePath, $sortOrder) {
            global $databaseConnection;
    
            $query = "UPDATE pagebanners SET `content` = ?, imagePath = ?, sortOrder = ? WHERE id = ?;";
            $statement = $databaseConnection->prepare($query);
            $statement->bind_param('ssii', $content, $imagePath, $sortOrder, $bannerId);
            $statement->execute();
    
            $statement->store_result();
            return (!$statement->error && $statement->affected_rows == 1);
        }
    
        function RemoveBanner($bannerId) {
            global $databaseConnection;
    
            $query = "DELETE FROM pagebanners WHERE id = ?;";
            $statement = $databaseConnection->prepare($query);
            $statement->bind_param('i', $bannerId);
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
                    echo "<div id=\"edit-page\"><button type=\"submit\" name=\"save\" title=\"Save your changes\">Save</button></div>";//<button type=\"submit\" name=\"add\" title=\"add a sub-page\" /><button type=\"submit\" name=\"delete\" onclick=\"if (!confirm('Are you sure you want to delete this page?')) return false;\" title=\"delete this page\" /></div>"; 
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