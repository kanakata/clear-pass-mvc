<?php

namespace App\Controllers;

use App\Models\Clearance_progress\Clearance_progress;
use App\Models\Date\Date;
use App\Models\Query\Query;


class view_controller extends Query
{
    public static function Display_student_data()
    {
        return parent::Collect_student_data();
    }
    public static function Display_clearance_progress()
    {
        return Clearance_progress::Clearance_progress();
    }
    public static function Display_report_date()
    {
        return Date::Date();
    }
    public static function  Display_available_destinations()
    {
        if (isset($_POST['destination'])) {
            return parent::Collect_available_destinations();
        }
    }
}
