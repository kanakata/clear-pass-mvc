<?php

namespace App\Models\Query;

use App\Models\Database\Database;
use PDO;

abstract class Query extends Database
{
    protected static $connection = "";
    protected static function Connection_resource()
    {
        return self::$connection = parent::Database_connect("schoolclearancesite");
    }
    protected static function Collect_student_data()
    {
        //collecting data for the dept page.
        if (isset($_SESSION['admission'])) {
            $admission = $_SESSION['admission'];
            $stmt = self::Connection_resource()->prepare("SELECT * FROM studentgeneraldata WHERE `admission number`=?");
            $stmt->bindParam(1, $admission);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $info = $result;
                return [
                    "student_data" => $info,
                ];
            }
        } else {
            $error = "an error occurred try logging in or enabling cookie setting in your browser settings.";
        }
    }
    protected static function Collect_available_destinations()
    {
        $destination = $_POST['location'];
        $sql = self::Connection_resource()->prepare("SELECT * FROM `shipment cost` WHERE location=? ");
        $sql->execute([$destination]);
        $destination_info = $sql->fetch(PDO::FETCH_ASSOC);
        return [
            "destinstion_info" => $destination_info
        ];
    }
}