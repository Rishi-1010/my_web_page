// test_connection.php
<?php
require_once 'src/php/config/database.php';
if($pdo) {
    echo "Database connection successful!";
}
?>