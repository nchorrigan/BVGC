<?php
    class ICS {
        var $data;
        var $name;
        function ICS($start,$end,$name,$description,$location) {
            $this->name = $name;
            $this->data = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nMETHOD:PUBLISH\r\nBEGIN:VEVENT\r\nDTSTART:".date("Ymd\THis\Z",strtotime($start))."\r\nDTEND:".date("Ymd\THis\Z",strtotime($end))."\r\nLOCATION:".$location."\r\nTRANSP: OPAQUE\r\nSEQUENCE:0\r\nUID:\r\nDTSTAMP:".date("Ymd\THis\Z")."\r\nSUMMARY:".$name."\r\nDESCRIPTION:".$description."\r\nPRIORITY:1\r\nCLASS:PUBLIC\r\nBEGIN:VALARM\r\nTRIGGER:-PT10080M\r\nACTION:DISPLAY\r\nDESCRIPTION:Reminder\r\nEND:VALARM\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n";
        }
        function save() {
            file_put_contents($this->name.".ics",$this->data);
        }
        function show() {
            header("Content-type:text/calendar");
            header('Content-Disposition: attachment; filename="'.$this->name.'.ics"');
            Header('Content-Length: '.strlen($this->data));
            Header('Connection: close');
            echo $this->data;
        }
    }
    $ics = new ICS($_GET["start"], $_GET["end"], $_GET["name"], $_GET["desc"], "Burghill Valley Golf Club");
    $ics->show();
?>