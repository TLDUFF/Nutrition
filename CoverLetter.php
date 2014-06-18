<?php
/* Creates the Cover Letters in PDF form.  Uses a template PDF, and adds the correct participant
 * information to the template.
 * TDUFF: Arial Font for RSG, Times Font for OTIS.
 */
if(session_id() == '') {
        session_start();
}

require_once ('./Database.php');
require_once ('../../application/include/fpdf17/fpdf.php');   //for creating PDF
require_once ('../../application/include/fpdi142/fpdi.php'); //for importing existing PDF as a template

// Vars
$today = date("F j, Y");
$status = '';
$src_dir = '../pdf/nutrition_cover_letters/';
$srcfilename = 'CoverLetter.pdf';  //does not exist

 switch ($_SESSION['myproject'])
    {

        case "OTIS":
            $srcfilename = 'OTISCoverLetter_Template.pdf';
            $srcfile = $src_dir . $srcfilename;

            //set font
            $font = "Times";
            $fontsize = 13;

            if( isset($_GET["lastname"]) && isset($_GET["StudyID"])){

                $firstname = 'Test';

                $lastname = $_GET["lastname"];
                $studyID = $_GET["StudyID"];
                $address = '';

                //Grab Address from db.
                $db = new Database();
                $db->Connect();

                $sql = "SELECT first_name, last_name, address
                          FROM subject
                          WHERE subjectID = $studyID";

                $result = $db->Query($sql);
                if (!($db->Query($sql))) {
                    echo mysql_error($sql);
                } else {
                    while($data = mysql_fetch_array($result)){
                        $firstname = $data[0];
                        $lastname = $data[1];
                        $address = $data[2];
                    }
                }

                $matches = preg_split("/,/", $address);

                $address = $matches[0];
                $city = $matches[1] .', '. $matches[2];
                $city = trim($city);

                $amount = '';

                createPDF($src_dir, $srcfilename, $font, $fontsize, $today, $firstname, $lastname, $address, $city, $amount);

            }
            break;
        case "RSG":
            $srcfilename = 'RSG';

            //set font
            $font = "Arial";
            $fontsize = 12;

            if( isset($_GET["Interviews"]) && isset($_GET["lastname"])) {
                $status = 'complete';
            } elseif( isset($_GET["One"]) && isset($_GET["lastname"])) {
                $status = 'partial';
            }

            //SubjectID
            $studyID = $_GET["StudyID"];

            if($status == 'complete'){

                $srcfilename .= 'CoverLetter_Complete_Template.pdf';
                $srcfile = $src_dir . $srcfilename;

                // Fill in Last name
                $lastName = $_GET["lastname"];
                $lastName .= ', ';

                //Grab Address from db.
                $db = new Database();
                $db->Connect();

                $sql = "SELECT first_name, last_name, address
                          FROM subject
                          WHERE subjectID = $studyID";

                $result = $db->Query($sql);
                if (!($db->Query($sql))) {
                    echo mysql_error($sql);
                } else {
                    while($data = mysql_fetch_array($result)){
                        $firstname = $data[0];
                        $lastname = $data[1];
                        $address = $data[2];
                    }
                }
                $matches = preg_split("/,/", $address);

                $address = $matches[0];
                $city = $matches[1] .', '. $matches[2];
                $city = trim($city);

                //number of complete interviews. if 2 = $40, 3 = $60
                $interviews = $_GET["Interviews"];
                $amount = '';

                switch($interviews) {
                    case 2:
                        $amount = '$40';
                        break;
                    case 3:
                        $amount = '$60';
                        break;
                    default:
                        $amount = '$  ';
                }

                createPDF($src_dir, $srcfilename, $font, $fontsize, $today, $firstname, $lastname, $address, $city, $amount);

            }  elseif ($status === 'partial') {

                $srcfilename .= 'CoverLetter_Partial_Template.pdf';
                $srcfile = $src_dir . $srcfilename;


                // Fill in Last name
                $lastName = $_GET["lastname"];
                $lastName .= ', ';

                //Grab Address from db.
                $db = new Database();
                $db->Connect();

                $sql = "SELECT first_name, last_name, address
                          FROM subject
                          WHERE subjectID = $studyID";

                $result = $db->Query($sql);
                if (!($db->Query($sql))) {
                    echo mysql_error($sql);
                } else {
                    while($data = mysql_fetch_array($result)){
                        $firstname = $data[0];
                        $lastname = $data[1];
                        $address = $data[2];
                    }
                }

                $matches = preg_split("/,/", $address);

                $address = $matches[0];
                $city = $matches[1] .', '. $matches[2];
                $city = trim($city);

                $amount = '';

                createPDF($src_dir, $srcfilename, $font, $fontsize, $today, $firstname, $lastname, $address, $city, $amount);

            }
            break;

        case "CTIS":
            $srcfilename = 'CTISCoverLetter_Template.pdf';
            $srcfile = $src_dir . $srcfilename;

            //set font
            $font = "Times";
            $fontsize = 13;
 
            if( isset($_GET["lastname"]) && isset($_GET["StudyID"])){

                $firstname = 'Test';

                $lastname = $_GET["lastname"];
                $studyID = $_GET["StudyID"];
                $address = '';

                //Grab Address from db.
                $db = new Database();
                $db->Connect();

                $sql = "SELECT first_name, last_name, address
                          FROM subject
                          WHERE subjectID = $studyID";

                $result = $db->Query($sql);
                if (!($db->Query($sql))) {
                    echo mysql_error($sql);
                } else {
                    while($data = mysql_fetch_array($result)){
                        $firstname = $data[0];
                        $lastname = $data[1];
                        $address = $data[2];
                    }
                }

                $matches = preg_split("/,/", $address);

                $address = $matches[0];
                $city = $matches[1] .', '. $matches[2];
                $city = trim($city);

                $amount = '';

                createPDF($src_dir, $srcfilename, $font, $fontsize, $today, $firstname, $lastname, $address, $city, $amount);

            }
            break;

    }//end switch

function createPDF ($src_dir, $srcfile, $font, $fontsize, $date, $firstname, $lastname, $address, $city, $amount){

        $template = $src_dir . $srcfile;
        //$font passed in
        //$fontsize passed in
        //$date passed in
        $date_X = '';
        $date_Y = '';
        $fullname = $firstname .' '. $lastname;
        $address1_Y = '';
        // $address passed in
        $address2_Y = '';
        // $city passed in
        $address3_Y = '';
        // $lastname passed in
        $ms_lastname = 'Ms. '.$lastname.', ';
        $name_X = '';
        $name_Y = '';
        //$amount passed in (for RSG completes only)
        $amount_X = '';
        $amount_Y = '';


        switch ($srcfile) {
            case "OTISCoverLetter_Template.pdf":
                //Position for each variable placement on templates
                $date_X = 11;
                $date_Y = 60;
                $address1_Y = 72;
                $address2_Y = 76;
                $address3_Y = 80;
                $name_X = 21;
                $name_Y = 88.5;
                break;
            case "RSGCoverLetter_Complete_Template.pdf";
                $date_X = 17;
                $date_Y = 60;
                $address1_Y = 72;
                $address2_Y = 76;
                $address3_Y = 80;
                $name_X = 27.5;
                $name_Y = 92.75;
                $amount_X = 123;
                $amount_Y = 165.5;
                break;
            case "RSGCoverLetter_Partial_Template.pdf";
                $date_X = 17;
                $date_Y = 60;
                $address1_Y = 72;
                $address2_Y = 76;
                $address3_Y = 80;
                $name_X = 27.5;
                $name_Y = 93.25;
                break;
            case "CTISCoverLetter_Template.pdf";
                $date_X = 11;
                $date_Y = 60;
                $address1_Y = 72;
                $address2_Y = 76;
                $address3_Y = 80;
                $name_X = 21;
                $name_Y = 88.5;
                break;
        }


        $pdf = new FPDI();
        $pdf->AddPage();
        $pdf->setSourceFile($template);

        $tplidx = $pdf->importPage(1);                   //import page 1
        $pdf->useTemplate($tplidx,0,0,0,0,true);         //use the imported page
        $pdf->SetFont($font, "", $fontsize);
        $pdf->SetTextColor(0,0,0);

        //Write Date on letter
        $pdf->SetXY($date_X,$date_Y);
        $pdf->Cell(20,10,$date,0,1,'L');

        //Write Address on Letter
        $pdf->SetXY($date_X, $address1_Y);
        $pdf->Cell(20,10, $fullname,0,1,'L');
        $pdf->SetXY($date_X, $address2_Y);
        $pdf->Cell(20,10, $address,0,1,'L');
        $pdf->SetXY($date_X, $address3_Y);
        $pdf->Cell(20,10, $city,0,1,'L');

        //Write Last Name on letter
        $pdf->SetXY($name_X, $name_Y);
        $pdf->Cell(16,10,$ms_lastname,0,1,'L');

        // Write $amount on RSG complete letter
        $pdf->SetXY($amount_X, $amount_Y);
        $pdf->Cell(10,10,$amount,0,1,'C');

        $pdf->Output();
        return "";
    } //cretePDF
?>
