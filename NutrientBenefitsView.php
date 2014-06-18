<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="NutritionView.css" />
    </head>
    <body>

        <?php
            require_once('Database.php');

            $db = new Database();
            $db->Connect();

            //Get Nutrient Benefits data for select nutrients
            $sql_3="SELECT nutrient.id, nutrient.name, nutrient.benefits_to_woman,
                    nutrient.benefits_to_mother, nutrient.benefits_to_baby
                FROM nutrient, rda
                WHERE nutrient.id = rda.nutrient_id
                AND rda.type_of_woman_id = '$id'
                AND nutrient.id IN ('8', '13', '14', '15', '16', '17', '18', '19', '20',
                   '21', '22', '23', '24')
                ORDER BY nutrient.name";

            $result_benefits=$db->Query($sql_3);

            switch ($Condition) {

                case "Adult":
                    $id='1';
                    $colspan = 3;
                    break;
                case "Pregnant":
                    $id='2';
                    $colspan = 4;
                    break;
                case "Lactating":
                    $id='3';
                    $colspan = 4;
                    break;

            }//end switch

        ?>

       <center>

        <table>
<!--        table header-->
        <thead>
            <tr id="tbl_header">
                <td id="tbl_header_text" colspan=<?php echo $colspan ?>
                    >Nutrient Benefits and Sources for <?php echo $Condition ?> Women</td>
            </tr>
           <tr>
               <th align="left">Nutrient</th>
               <th>Benefits to You</th>
               <?php
               switch ($id) {
                    case '1': break;
                    default : echo '<th>Benefits to Baby</th>';
               }//end switch
               ?>
               <th>Best Food Sources</th>
           </tr>
        </thead>

        <tbody>
         <?php
//         Table Rows & Data
//         print the values from the query in the table rows.
                $count = 1;

                while($rows=mysql_fetch_array($result_benefits)){

                    //first find out what color background stripe to use.
                    $stripe=($count%2>0)?'class="zebra1"':'class="zebra2"';


                   if ($rows[1] == 'Vitamin A' && $id != 1) { //break into two pages
                                 echo '</tbody><tfoot>
                                   <td class ="footer" colspan='. $colspan . '>
                                      NOTE: Please consult with your doctor/health care provider if you
                                      have further questions regarding the information above.   The above
                                      information was sourced from www.usda.gov
                                   </td></tfoot></table><br /><table class="page-break">';
          ?>
                    <!-- Repeat header on next page -->
                       <table>
                       <thead> <tr id="tbl_header">
                           <td id="tbl_header_text" colspan= <?php echo $colspan ?>>
                           Nutrient Benefits and Sources for <?php echo $Condition ?> Women
  </td></tr>
                       <tr>
                           <th align="left">Nutrient</th>
                           <th>Benefits to You</th>
                           <th>Benefits to Baby</th>
                           <th>Best Food Sources</th>
                       </tr>
                       </thead>
                       <tbody>

       <?php
                   }//end if

                    //then print the row
                    echo '<tr '.$stripe.'>';
                        echo '<td class="firstColumn", width="8%" >', $rows[1], '</td>';// Nutrient

                        //format table for adult or pregnant or breastfeeding data.
                        switch ($id) {
                            // Adult
                            case '1': echo '<td class="nutrientBenefits" >', $rows[2], '</td>';//Benefits to You
                                break;
                            // Pregnant or Lactating
                            default: echo '<td class="nutrientBenefits" >', $rows[2]. $rows[3],//append benefits to mother
                                    '</td>',
                                    //add Benefits to Baby column
                                    '<td class="nutrientBenefits" >', $rows[4], '</td>';
                        } // end switch

                        //Get Nutrient Foodsources data for table
                        $sql_4="select distinct food
                            FROM foodsource, nutrient_food
                            where  foodsource.id = nutrient_food.foodsource_id
                            and nutrient_food.nutrient_id=$rows[0]
                            order by 1";

                        $db2 = new Database();
                        $db2->Connect();
                        $result_foodsources=$db2->Query($sql_4);
                        $foodsArray= array();

                        while($rows2=mysql_fetch_array($result_foodsources)){
                            $foodsArray[] = $rows2[0];
                        } //end while

                        //Print Best Food Sources Data
                        //with special text for Sodium data.
                        switch ($rows[1]) {
                            case 'Sodium' : echo '<td class="nutrientBenefits" >',
                                    'Try to minimize excess sodium intake by selecting unprocessed,'
                                     .'fresh foods rather than manufactured and processed foods. </td>';
                                break;
                            default : echo '<td class="nutrientBenefits" >',
                                        $finalstring = implode(',  ', $foodsArray);
                                        '</td>';
                        }//end switch

                    echo '</tr>';
                    $count++;

                } //end while
            ?>
        </tbody>
           <tfoot>
           <td class ="footer" colspan=<?php echo $colspan ?>>
              NOTE: Please consult with your doctor/health care provider if you
              have further questions regarding the information above.   The above
              information was sourced from www.usda.gov
           </td>
           </tfoot>
<!--    End Table -->
        </table>

        <?php
            mysql_close();
        ?>

    </center>
    </body>
