<?php
    // session_start();
    include("db_conn.php");
    if(isset($_REQUEST['action'])){
        switch($_REQUEST['action']){
            case "getRoom":
                $query = $db->prepare("SELECT * FROM rooms");
                $query->execute();

                $room = '';
                global $online_count;
                $rs = $query->fetchAll(PDO::FETCH_OBJ);

                foreach($rs as $r){
                    $room .= "<li id='room_list_li' onclick='switchRoom($r->room_id, )></li><br/>";
                }
                echo $room;
                break;
        }
    }
?>

<script src="../scripts/logged.js"></script>