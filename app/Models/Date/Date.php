<?php
namespace App\Models\Date;
use App\Models\Query\Query;
//sets date for report
class Date extends Query
{
    public static function Date()
    {
        $info = parent::Collect_student_data()['student_data'];
        // 1. Define required statuses
        $valid_statuses = ["cleared", "pending_physical_payment", "online"];
        $categories = ['library status', 'boarding status', 'accessories status', 'laboratory status', 'finance status', 'games status'];

        $is_eligible = true;
        foreach ($categories as $cat) {
            if (!in_array($info[$cat], $valid_statuses)) {
                $is_eligible = false;
                break;
            }
        }

        if ($is_eligible && $info['clearancestatus'] != "cleared") {
            $sec = 86400;
            $stamp = time();
            $clearance_days = ["Mon", "Wed"];
            $holidays = [
                "2026-01-01",
                "2026-01-05",
                "2026-03-20",
                "2026-03-21",
                "2026-04-03",
                "2026-04-06",
                "2026-06-01",
                "2026-10-20",
                "2026-12-12",
                "2026-12-25",
                "2026-12-26"
            ];

            // 2. Logic: Find the next valid clearance day (Mon or Wed) that isn't a holiday
            $found = false;
            $attempts = 0;
            while (!$found && $attempts < 15) { // Safety cap to prevent infinite loops
                $stamp += $sec;
                $current_day_name = date("D", $stamp);
                $current_date_fmt = date("Y-m-d", $stamp);

                if (in_array($current_day_name, $clearance_days) && !in_array($current_date_fmt, $holidays)) {
                    $found = true;
                }
                $attempts++;
            }

            // 3. Database Operations (Run only once)
            $report_day = date("D j F Y", $stamp);
            $report_date = date("Y-m-d", $stamp);

            // Set cookie
            setcookie("report_day", $report_day, time() + (5 * $sec), "/");

            $message = "{$info['username']} admission {$info['admission number']} is fully cleared and is due on $report_day";

            // Notification
            $stmt1 = parent::Connection_resource()->prepare("INSERT INTO `notifications`(`username`, `admission`, `message`) VALUES (?,?,?)");
            $stmt1->execute([$info['username'], $info['admission number'], $message]);

            // Update Student Data
            $cleared = "cleared";
            $stmt2 = parent::Connection_resource()->prepare("UPDATE `studentgeneraldata` SET `clearancestatus`=?, `report date`=? WHERE `admission number`=?");
            $stmt2->execute([$cleared, $report_date, $info['admission number']]);
        }
    }
}
