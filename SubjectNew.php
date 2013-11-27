<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!--<link rel="stylesheet" type="text/css" href="NutritionView.css" />-->
        <!--<link rel="stylesheet" type="text/css" href="accordionDivs.css" />-->
        <link rel="stylesheet" type="text/css" href="accordionContent.css" />
<!--        <script src="myJS.js"></script>-->
        <script src="accordion.js"></script>
        <!--
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
        
        <?php   // Display correct header for Project
            if(isset($_POST['formProject'])){
                $project = $_POST['formProject'];
            }// end if
                
            switch ($project) {  

                case "OTIS" ?>
                    <IMG src="logoOTIS_RGB-300x173.jpg" 
                         alt="Mother to Baby" 
                         style="width: 150px; float: left; margin-left: 10px; margin-bottom: 8px; margin-right: 50px" />
                    <?php break;
                case "RSG" ?>
                    <IMG src="logoRSG-outline.png" 
                         alt="Ready, Set, Go" 
                         style="width: 150px; float: left; margin-left: 10px; margin-bottom: 8px; margin-right: 50px" />
                    <?php break;
                case "CTIS" ?>
                     <IMG src="logoCTIS.jpg" 
                          alt="Mother to Baby California" 
                          style="width: 150px; float: left; margin-left: 10px; margin-bottom: 8px; margin-right: 50px" />
                     <?php break;

            }//end switch
         ?>
        <br />
         <h2>List of Nutrition Assessment Participants from <?php echo "$project" ?> </h2>
         <br /><br />
    </head>
<!-- END HEADER -------------------------------------------------------------------------------------------------->

    <body>
        
        <?php
            require_once('Database.php');

            $db = new Database();
            $db->Connect();

/*
 * SQL statements to get Subject list for project.
 */

    // NEW RECORDS
            $sql_subjects = "SELECT s.subjectID, s.first_name, s.last_name, s.womans_condition, 
                    ROUND((DATEDIFF(CURDATE(), s.dob) / 365.25),0) as AGE, 
                    DATE_FORMAT(v.import_timestamp, '%b-%d-%Y' ) as UPLOAD
            FROM subject s,
                 subject_avg_food_view v
            WHERE s.study = '$project'
              AND s.subjectID = v.subjectID
              AND s.printed = 'N'
              AND s.flagged = 'N'";
            
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
                    count(v.dateofinterview ) as NUM_INTERVIEWS,
                    max(v.dateofinterview) as LAST_INTERVIEW
            FROM subject s,
                 intake_from_food_raw v
            WHERE s.study = '$project'
              AND s.subjectID = v.externalstudyID
            GROUP BY s.subjectID, s.first_name, s.last_name, s.womans_condition, AGE";
            
            $result_incentives=$db->Query($sql_incentives);
        ?>
        
<!--/**Start Subject List output here (in Tables).**/ -->

<?php require_once('subjects.php'); ?>

<br />
<br />

<!-- CONTAINER -->

<!-- NEW RECORDS -->
    <div onclick="runAccordion(1);">
        <div class="AccordionTitle" onselectstart="return false;">
          New Records
        </div>
    </div>
        
     <div id="Accordion1Content" class="AccordionContent">  
        <table>
            <th>Subject ID</th>
            <th>Name</span>
            <th>Woman's <br/> Condition</th>
            <th>Age</span>
            <th>File Uploaded</th>
            <th>View <br/> Report</th>

    <!--  table data-->
     <?php   
      
                $count = 1;
                while($subject=mysql_fetch_array($result_subjects)) 
                {

                    //first find out what color background stripe to use.
                    $stripe=($count%2>0)?'class="zebra1"':'class="zebra2"';

                    //then print the row
                    echo '<tr '.$stripe.'>';

                        // SubjectID
                        echo '<td width=10%>', $subject[0]. '</td>';

                        // Name
                        echo '<td>', $subject[1].' '. $subject[2]. '</td>'; 

                        // Woman's Condition 
                        echo '<td>', $subject[3]. '</td>'; 

                        // Age
                        echo '<td>', $subject[4]. '</td>';

                        //File Uploaded
                        echo '<td>', $subject[5]. '</td>';

                        echo '<td><a href="NutritionAssessmentView.php?project='. $project .
                                '&SubjectID=' .$subject[0]. 
                                '&Condition=' .$subject[3]. 
                                '&Age=' .$subject[4]. 
                                '" target="_blank">VIEW</a></td>';
                    echo '</tr>'; 
                    //end of row

                    $count++;

                }// end while      
          ?>
        
     </div> <!-- content -->

   
              <!--<br />-->
<!-- FLAGGED -->              
              <section id="flagged" class="accordion-item">
                  <h3><a href="#flagged">Flagged Records</a></h3>
              

              <?php 
//              $db = new Database();
//              $db->Connect();
              ?>
<!-- TDUFF: hide <th> if no results returned -->
            <div class="content">
              <table>
                <tr>
                    <th align="left">Subject ID</th>
                    <th>Name</th>
                    <th>Woman's <br/> Condition</th>
                    <th>Age</th>
                    <th>Comments</th>
                    <th>File Uploaded</th>
                    <th>View <br/> Report</th>
                </tr>
<!-- table data-->
     <?php   
                $count = 1;
                while($flagged=mysql_fetch_array($result_flagged)) 
                {

                    //first find out what color background stripe to use.
                    $stripe=($count%2>0)?'class="zebra1"':'class="zebra2"';

                    //then print the row
                    echo '<tr '.$stripe.'>';

                        // SubjectID
                        echo '<td width=10%>', $flagged[0]. '</td>';

                        // Name
                        echo '<td>', $flagged[1].' '. $flagged[2]. '</td>'; 

                        // Woman's Condition 
                        echo '<td>', $flagged[3]. '</td>'; 

                        // Age
                        echo '<td>', $flagged[4]. '</td>';
                        
                        // Comments
                        echo '<td>', $flagged[5]. '</td>';

                        //File Uploaded
                        echo '<td>', $flagged[6]. '</td>';

                        echo '<td><a href="NutritionAssessmentView.php?project='. $project .
                                '&SubjectID=' .$flagged[0]. 
                                '&Condition=' .$flagged[3]. 
                                '&Age=' .$flagged[4]. 
                                '" target="_blank">VIEW</a></td>';
                    echo '</tr>'; 
                    //end of row

                    $count++;

                }// end while      
          ?>
            </table>     
            </div> <!-- content-->  
              </section>
              <!--<br />-->
<!-- PRINTED -->              
              <section id="printed" class="accordion-item">
                  <h3><a href="#printed">Printed Records</a></h3>
              
               <?php
//                  $db = new Database();
//                  $db->Connect();
                  ?>
              <div class="content">
              <table>
                <tr>
                    <th align="left">Subject ID</th>
                    <th>Name</th>
                    <th>Woman's <br/> Condition</th>
                    <th>Age</th>
                    <th>File Uploaded</th>
                    <th>View <br/> Report</th>
                </tr>
<!-- table data-->
     <?php   
                $counter = 1;
                while($printed=mysql_fetch_array($result_printed)) 
                {

                    //first find out what color background stripe to use.
                    $stripe=($counter%2>0)?'class="zebra1"':'class="zebra2"';

                    //then print the row
                    echo '<tr '.$stripe.'>';

                        // SubjectID
                        echo '<td width=10%>', $printed[0]. '</td>';

                        // Name
                        echo '<td>', $printed[1].' '. $printed[2]. '</td>'; 

                        // Woman's Condition 
                        echo '<td>', $printed[3]. '</td>'; 

                        // Age
                        echo '<td>', $printed[4]. '</td>';

                        //File Uploaded
                        echo '<td>', $printed[5]. '</td>';

                        echo '<td><a href="NutritionAssessmentView.php?project='. $project .
                                '&SubjectID=' .$printed[0]. 
                                '&Condition=' .$printed[3]. 
                                '&Age=' .$printed[4]. 
                                '" target="_blank">VIEW</a></td>';
                    echo '</tr>'; 
                    //end of row

                    $counter++;

                }// end while  
                
          ?>
            </table>     
    </div> <!-- content-->
              </section>
              <!--<br />-->

<!-- INCENTIVES for RSG only --> 
       <?php
           if ($project == 'RSG') {      
               
//              $db = new Database();
//              $db->Connect();
//              
               ?>            
              <section id="incentive" class="accordion-item">
                  <h3><a href="#incentive">Less Than 3 Recalls Conducted</a></h3>
              
       
              <div id="content">
              <table>
             
              <tr>
               <th align="left">Subject ID</th>
               <th>Name</th>
               <th>Woman's <br/> Condition</th>
               <th>Age</th>
               <th>Number of Interviews <br />Completed</th>
               <th>Last Interview <br />Completed On</th>
              </tr>

        <?php
                $count = 1;
                while($incentives=mysql_fetch_array($result_incentives)) 
                {

                    //first find out what color background stripe to use.
                    $stripe=($count%2>0)?'class="zebra1"':'class="zebra2"';
                    
                    if ($incentives[5]<3) { //only print those with less than three recalls.

                        //then print the row
                        echo '<tr '.$stripe.'>';

                            // SubjectID
                            echo '<td width=10%>', $incentives[0]. '</td>';

                            // Name
                            echo '<td>', $incentives[1].' '. $incentives[2]. '</td>'; 

                            // Woman's Condition 
                            echo '<td>', $incentives[3]. '</td>'; 

                            // Age
                            echo '<td>', $incentives[4]. '</td>';

                            //Number of Interviews Completed
                            echo '<td>', $incentives[5]. '</td>';
                            
                            //Date of Last Interview
                            echo '<td>', $incentives[6]. '</td>';

                        echo '</tr>'; 
                        //end of row

                        $count++;
                    }//end if
                }// end while  
               
             }//end if    
          ?>
            </table>     
            </div> <!--content-->
              </section>
            <!--<br />-->
           
        </div> <!--container-->
<!-- end of tables -->
</div> <!--AccordionContainer-->
    </body>
    <footer>
         <br /><br />
    </footer>
    
</html>
