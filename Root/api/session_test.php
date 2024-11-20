<?php
session_start();

if (!isset($_SESSION['test'])) {
    $_SESSION['test'] = "Session is working!";
    echo "Session set!";
} else {
    echo "Session exists: " . $_SESSION['test'];
}
?>