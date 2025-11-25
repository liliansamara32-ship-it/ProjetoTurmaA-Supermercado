<?php
function averigua(){
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login");
        exit;
    }
}
