<?php

 require_once('Database.php');

            $db = new Database();
            $db->Connect();
/*
 * SQL statements to get Subject list for project.
 */
    $project = $_SESSION['myproject'];

    // NEW RECORDS
            $sql_subjects = "SELECT s.subjectID, s.first_name, s.last_name, s.womans_condition,
                    ROUND((DATEDIFF(CURDATE(), s.dob) / 365.25),0) as AGE,
                    DATE_FORMAT(v.import_timestamp, '%b-%d-%Y' ) as UPLOAD
            FROM subject s,
                 subject_avg_food_view v
            WHERE s.study = '$project'
              AND s.subjectID = v.subjectID
              AND s.printed = 'N'
              AND s.flagged = 'N'
            ORDER BY v.import_timestamp";

            $result_subjects=$db->Query($sql_subjects);

    // FLAGGED RECORDS
            $sql_flagged = "SELECT s.subjectID, s.first_name, s.last_name, s.womans_condition,
                    ROUND((DATEDIFF(CURDATE(), s.dob) / 365.25),0) as AGE,
                    s.comments,
                    DATE_FORMAT(v.import_timestamp, '%b-%d-%Y' ) as UPLOAD
            FROM subject s,
                  subject_avg_food_view v
            WHERE s.study = '$project'
              AND s.subjectID = v.subjectID
              and s.flagged = 'Y'";

            $result_flagged=$db->Query($sql_flagged);

    // PRINTED RECORDS
            $sql_printed = "SELECT s.subjectID, s.first_name, s.last_name, s.womans_condition,
                    ROUND((DATEDIFF(CURDATE(), s.dob) / 365.25),0) as AGE,
                    DATE_FORMAT(v.import_timestamp, '%b-%d-%Y' ) as UPLOAD
            FROM subject s,
                  subject_avg_food_view v
            WHERE s.study = '$project'
              AND s.subjectID = v.subjectID
              and s.printed = 'Y'";

        $result_printed=$db->Query($sql_printed);

    // INCOMPLETE INTERVIEWS
            $sql_incentives = "SELECT s.subjectID, s.first_name, s.last_name, s.womans_condition,
                    ROUND((DATEDIFF(CURDATE(), s.dob) / 365.25),0) as AGE,
                    count(r.dateofinterview ) as NUM_INTERVIEWS,
                    max(r.dateofinterview) as LAST_INTERVIEW
            FROM subject s,
                 intake_from_food_raw r
            WHERE s.study = '$project'
              AND s.subjectID = r.externalstudyID
            GROUP BY s.subjectID, s.first_name, s.last_name, s.womans_condition, AGE
            ORDER BY r.dateofinterview";

            $result_incentives=$db->Query($sql_incentives);

   //COUNT INTERVIEWS completed for subject.

   function countInterviews($subjectID) {

            $StudyID = $subjectID;

            $db = new Database();
            $db->Connect();

            $sql_interviews="SELECT
                count(dateofinterview) AS count
             FROM intake_from_food_raw
             WHERE externalstudyid = '$StudyID'
             limit 1";

            $interview_count = mysql_query($sql_interviews) or die();
            $row = mysql_fetch_array($interview_count);
            $count = $row[0];

            return $count;

   }
?>
