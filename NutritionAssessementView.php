<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="NutritionView.css" />

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
        //Get passed variables: StudyID, Condition, Age, Interviews;
            $StudyID = $_GET['StudyID'];

            $Condition = $_GET['Condition'];

            $Age = $_GET['Age'];

            $Interviews = $_GET['Interviews'];

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
                and subjectID = '$StudyID'";

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
            
            
            $sql_subjectFood="SELECT
                ROUND(BetaCarotene_avg, 0) as `Beta-carotene`,
                ROUND(Calcium_avg, 0) as Calcium,
                ROUND(Choline_avg, 0) as Choline,
                ROUND(DHA_EPA_avg, 2) as DHA_EPA,
                ROUND(Folate_avg, 0) as Folate,
                ROUND(Iron_avg, 0) as Iron,
                ROUND(Potassium_avg, 0) as Potassium,
                ROUND(Retinol_Avg, 0) as Retinol,
                ROUND(Sodium_avg, 0) as Sodium,
                ROUND(VitB12_avg, 1) as VitB12,
                ROUND(VitB6_avg, 1) as VitB6,
                ROUND(VitC_avg, 0) as VitC,
                ROUND(VitD_avg, 0) as VitD,
                ROUND(Zinc_avg, 0) as Zinc,
                ROUND(VegServ_avg, 0) as VegServ,
                ROUND(FruitServ_avg, 0) as FruitServ
            FROM subject_avg_food_view
            WHERE SubjectID = '$StudyID'";

            $result_subjectFood=$db->Query($sql_subjectFood);
            $food_array = mysql_fetch_array($result_subjectFood, MYSQL_NUM);

            $sql_subjectSuppl="SELECT
                ROUND(BetaCarotene_avg, 0) as `Beta-carotene`,
                ROUND(Calcium_avg, 0) as Calcium,
                ROUND(Choline_avg, 0) as Choline,
                ROUND(DHA_EPA_avg, 2) as DHA_EPA,
                ROUND(Folate_avg, 0) as Folate,
                ROUND(Iron_avg, 0) as Iron,
                ROUND(Potassium_avg, 0) as Potassium,
                ROUND(Retinol_Avg, 0) as Retinol,
                ROUND(Sodium_avg, 0) as Sodium,
                ROUND(VitB12_avg, 1) as VitB12,
                ROUND(VitB6_avg, 1) as VitB6,
                ROUND(VitC_avg, 0) as VitC,
                ROUND(VitD_avg, 0) as VitD,
                ROUND(Zinc_avg, 0) as Zinc
            FROM subject_avg_suppl_view
            WHERE SubjectID = '$StudyID'";

            $result_subjectSuppl=$db->Query($sql_subjectSuppl);
            $suppl_array = mysql_fetch_array($result_subjectSuppl, MYSQL_NUM);

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
                        echo '<td class="firstColumn" width="25%">', $nutrients[1].' ('.$nutrients[3].')', '</td>';

                        // From Food
                        echo '<td width="10%">', $food_array[$count]. '</td>';

                        // From Supplements
                        echo '<td>', $suppl_array[$count]. '</td>';


                        // From Food and Supplements
                        echo '<td>', ($food_array[$count] + $suppl_array[$count]). '</td>';

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
                            echo '<td class="firstColumn" width="25%">', $foodGroups[1].' ('.$foodGroups[3].')', '</td>';

                            // From Food
                            echo '<td>', $food_array[$count]. '</td>';

                            // From Supplements
                            echo '<td> N/A </td>';

                            // From Food and Supplements
                            echo '<td>', $food_array[$count]. '</td>';

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
            <td  class ="footer" colspan="5">
                NOTE: Values are an average of <?php echo "$Interviews" ?> days;
                Recommended Intake based on Dietary Reference Intakes: Recommended Dietary Allowances (RDA),
                established by the Institute of Medicine.<br>
                Abbreviations: DHA - Docosahexaenoic Acid, EPA - Eicosapentaenoic Acid (omega-3 fatty acids)<br>
                * Retinol is the form of Vitamin A from animal sources.
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
         //Table with nutrients and their benefits
         require_once ("NutrientBenefitsView.php");
       ?>
    </body>
</html>
