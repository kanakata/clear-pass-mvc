<?php
namespace App\Models\Clearance_progress;
use App\Models\Query\Query;
use PDO;
class Clearance_progress extends Query{
    public static function Clearance_progress(){
        $info = parent::Collect_student_data()['student_data'];
        //calculate clearance progress.
        $status = [
            $info['library status'],
            $info['finance status'],
            $info['accessories status'],
            $info['games status'],
            $info['boarding status'],
            $info['laboratory status'],
        ];

        $total = 0;
        foreach ($status as $stat) {
            if ($stat == "cleared" || $stat == "pending_physical_payment" || $stat == "online") {
                $total += 100;
            } else {
                $total_percentage = 0;
            }
        }
        //as per the schools departments.
        $total_percentage = number_format($total / 6, 0);
        return [
            "total_percentage" => $total_percentage,
            "status" => $status,
        ];
    }
}