<?php
    class EventHelper {
        function GetEvent($id) {
            global $databaseConnection;
    
            $statement = $databaseConnection->prepare("SELECT e.id, et.name, e.title, e.summary, e.content, e.date 
                                                       FROM `events` e JOIN `eventtypes` et ON e.eventTypeId = et.id 
                                                       WHERE e.id = ?;");
            $statement->bind_param('i', $id);
            $statement->execute();
            $statement->store_result();
    
            $event = new Event();
    
            if (!$statement->error && !$statement->num_rows != 1) {
    
                $statement->bind_result($event->id, $event->type, $event->title, $event->summary, $event->content, $event->date);
                $statement->fetch();
    
                $event->files = $this->GetEventFiles($event->id);
            }
    
            return $event;;
        }
    
        function GetEvents($filter, $limit, $sortOrder = "desc") {
            global $databaseConnection;
    
            $events = array();
    
            $statement = $databaseConnection->prepare("SELECT e.id, et.name, e.title, e.summary, e.date 
                                                       FROM `events` e JOIN `eventtypes` et ON e.eventTypeId = et.id 
                                                       WHERE et.name LIKE ? AND e.date >= NOW()
                                                       ORDER BY e.`date` LIMIT ?;");
            $statement->bind_param('si', $filter, $limit);
    
            if ($statement->execute()) {
                $statement->bind_result($id, $type, $title, $summary, $date);
    
                while($row = $statement->fetch()) {
                    $event = new Event();
    
                    $event->id = $id;
                    $event->date = $date;
                    $event->type = $type;
                    $event->title = $title;       
                    $event->summary = $summary;
    
                    $events[] = $event;
                }
            }
    
            if ($sortOrder == "desc") {
                usort($events, function($a, $b) {
                    return strtotime($b->date) - strtotime($a->date);
                });
            } else {
                usort($events, function($a, $b) {
                    return strtotime($a->date) - strtotime($b->date);
                });
            }
    
            return $events;
        }
    
        function GetEventFiles($eventId) {
            global $databaseConnection;
    
            $files = array();
    
            $statement = $databaseConnection->prepare("SELECT gi.id, gi.filename, gi.path, gi.createdAt
                                                       FROM `eventfiles` gi
                                                       WHERE gi.event_id = ?            
                                                       ORDER BY gi.`filename` ASC");
            $statement->bind_param('i', $eventId);
    
            if ($statement->execute()) {
                $statement->bind_result($id, $filename, $path, $createdAt);
    
                while($row = $statement->fetch()) {
                    $file = new EventFile();
    
                    $file->id = $id;
                    $file->name = $filename;
                    $file->path = $path;
                    $file->createdAt = $createdAt;       
    
                    $files[] = $file;
                }
            }
    
            return $files;
        }

        function AddFile($eventId, $name, $path) {
            global $databaseConnection;

            $statement = $databaseConnection->prepare("INSERT INTO eventfiles(event_id, filename, path) VALUES (?, ?, ?);");

            $statement->bind_param('iss', $eventId, $name, $path);
            $statement->execute();
        }

        function UpdateEvent($id, $title, $date, $summary, $content) {
            global $databaseConnection;

            $statement = $databaseConnection->prepare("UPDATE `events` SET `title` = ?, `date` = ?, `summary` = ?, `content` = ? WHERE `id` = ?;");

            $statement->bind_param('ssssi', $title, date("Y-m-d H:i", $date), $summary, $content, $id);
            $statement->execute();

            return $this->GetEvent($id);
        }

        function CreateEvent($title, $type, $date, $summary, $content) {
            global $databaseConnection;

            $statement = $databaseConnection->prepare("INSERT INTO `events` (`title`, `eventTypeId`, `date`, `summary`, `content`) VALUES (?, ?, ?, ?, ?);");
            $statement->bind_param('sisss', $title, $type, date("Y-m-d H:i", $date), $summary, $content);
            $statement->execute();

            return $this->GetEvent($databaseConnection->insert_id);
        }

        function DeleteEvent($id) {
            global $databaseConnection;

            $statement = $databaseConnection->prepare("DELETE FROM `eventfiles` WHERE `event_id` = ?;");
            $statement->bind_param('i', $id);
            $statement->execute();

            $statement = $databaseConnection->prepare("DELETE FROM `events` WHERE `id` = ?;");
            $statement->bind_param('i', $id);
            $statement->execute();
        }
    }
    
    class Event {
        public $id = null;
        public $type = NULL;
        public $date = NULL;
        public $title = NULL;
        public $summary = NULL;
        public $content = NULL;
        public $files = NULL;
    
        public function formattedDate($format = null) {
            if ($format == null) {
                if (((string)date("H:i:s", strtotime($this->date))) == "00:00:00") {
                    return date("jS M Y", strtotime($this->date));
                } else {
                    return date("jS M Y, H:i", strtotime($this->date));
                }
            } else {
                return date($format, strtotime($this->date));
            }
        }
    }
    
    class EventFile {
        public $id = NULL;
        public $name = NULL;
        public $path = NULL;
        public $createdAt = NULL;
    }
?>