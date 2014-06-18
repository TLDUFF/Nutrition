<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="NutritionView.css" />
        <!--<link rel="stylesheet" href="print.css" type="text/css" media="print" />-->

        <?php   // Display correct header for Project
            if(isset($_GET['project'])){
                $project = $_GET['project'];
            }

            switch ($project) {

                case "RSG": $header='HeaderViewRSG.php'; break;
                case "OTIS"; $header='HeaderViewOTIS.php'; break;
                case "CTIS"; $header='HeaderViewCTIS.php'; break;

            }//end switch

            require_once("$header");

        ?>
    </head>
    <body>

        <?php
        //Get passed variables: StudyID, Condition, Age;
            $studyID = $_GET['StudyID'];
            $Condition = $_GET['Condition'];
            $Age = $_GET['Age'];

            require_once('Database.php');

            $db = new Database();
            $db->Connect();


            switch ($Condition) {

                case "Adult": $id='1'; break;
                case "Pregnant": $id='2'; break;
                case "Lactating": $id='3'; break;
                case "Pregnant/Lactating": $id='4'; break;

            }//end switch


/*
 * SQL statements to get Nutrient and Food Group headers and RDA data.
 */

            $sql_subjects = "SELECT first_name, last_name
             FROM subject
             WHERE study = '$project'
                and subjectID = '$studyID'";

            $result_subjects=$db->Query($sql_subjects);
            $subject_array = mysql_fetch_array($result_subjects, MYSQL_ASSOC);

            $sql_nutrients="SELECT nutrient.id, nutrient.name,
                rda.value, nutrient.unit
            FROM nutrient, rda
            WHERE nutrient.id = rda.nutrient_id
            AND rda.type_of_woman_id = '$id'
            AND nutrient.id IN ('8', '13', '14', '15', '16', '17', '18', '19', '20',
               '21', '22', '23', '24', '29')
            ORDER BY nutrient.name";

            $result_nutrients=$db->Query($sql_nutrients);

             $sql_foodgroups="SELECT nutrient.id, nutrient.name,
                 rda.value, nutrient.unit
            FROM nutrient, rda
            WHERE nutrient.id = rda.nutrient_id
            AND rda.type_of_woman_id = '$id'
            AND nutrient.id IN ('27', '28')
            ORDER BY nutrient.name";

            $result_foodgroups=$db->Query($sql_foodgroups);

        ?>

<!--/**Start report output here.**/ -->

        <center>
                <p> Name:   <?php echo implode(" ", $subject_array ); ?></p>

       <table>

<!--     table header-->
          <tr id="tbl_header">
              <td id="tbl_header_text" colspan="5">Your Nutrition Facts</td>
            </tr>

            <tr>
                <th align="left">Nutrient</th>
                <th>From <br /> Food</th>
                <th>From Supplements</th>
                <th>From Food + Supplements</th>
                <th>Recommended Intake: <br />
                    <?php echo $Condition ?> Women <br />
                    19 - 50 years old</th>
            </tr>

<!--      table body-->
            <?php

                // print the values from the queries in the table rows.
                $count = 0; // row counter
                while($nutrients=mysql_fetch_array($result_nutrients)){

                    //first find out what color background stripe to use.
                    $stripe=($count%2>0)?'class="zebra2"':'class="zebra1"';

                    //then print the row
                    echo '<tr '.$stripe.'>';

                        // Nutrient/Food Group Names with Units
                        echo '<td class="firstColumn" width="20%">', $nutrients[1].' ('.$nutrients[3].')', '</td>';

                        // From Food
                        echo '<td width="13%"> --'; switch($count) {
                                                case '0': echo ' See note below';//first line only
                                                default : echo '';
                                                } //end switch
                        echo '</td>';

                        // From Supplements
                        echo '<td> -- </td>';

                        // From Food and Supplements
                        echo '<td> -- </td>';

                        //Recommended Intake
                        echo '<td width="25%">'; switch ($nutrients[2]) {
                                        case '': echo 'Not Determined'; //fill NULL values
                                        default : echo $nutrients[2];
                                     } // end switch
                        echo '</td>';
                    echo '</tr>';
                    //end of row

                    $count++;

               } //end while

                //total veg servings and total fruit servings
                echo '<tr class="foodGroup">';
                    echo '<td class="foodGroup" colspan="5"> Food Group </td>';
                echo '</tr>';

                    while($foodGroups=mysql_fetch_array($result_foodgroups)){

                        $stripe=($count%2>0)?'class="zebra2"':'class="zebra1"';

                        echo '<tr '.$stripe.'>';
                            // Food Groups with Units
                            echo '<td class="firstColumn">', $foodGroups[1].' ('.$foodGroups[3].')', '</td>';
                            // From Food
                            echo '<td width="17%"> -- </td>';
                            // From Supplements
                            echo '<td> -- </td>';
                            // From Food and Supplements
                            echo '<td> -- </td>';
                            // Recommended Intake
                            echo '<td width="25%">'; switch ($foodGroups[2]) {
                                            case '': echo 'Not Determined'; //fill NULL values
                                            default : echo $foodGroups[2];
                                         } // end switch
                            echo '</td>';
                        echo '</tr>';
                        //end of row

                        $count++;

                    } // end while

             ?>

            <tfoot>
            <td class ="footer" colspan="5">
                -- Unable to calculate average 3-day nutrient intake due to missing interviews. <br />
                Abbreviations: DHA - Docosahexaenoic Acid, EPA - Eicosapentaenoic Acid (omega-3 fatty acids)<br />
                * RDA is for total Vitamin A, mostly from retinol and beta-carotene combined.  Retinol is the form 
                of Vitamin A from animal sources and can be toxic and cause birth defects at high intakes.  
                Beta-carotene is the form of Vitamin A from plant sources and is not harmful at high intakes. 
                Please consult your physician for questions or concerns.
            </td>
            </tfoot>

        </table>
        </center>



        <?php
            mysql_close();
        ?>
    <br />

        <div class="page-break"></div>

       <?php
         // Table of Nutrients and their benefits
         require_once ("NutrientBenefitsView.php");
       ?>

    </body>
    <footer>
    </footer>

</html>
