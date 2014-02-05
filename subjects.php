<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <link rel="stylesheet" type="text/css" href="accordionContent.css" />
        <script src="accordion.js"></script>
        <title>Subject Listings</title>
    </head>
    <body>
        
<!-- Accordion 1: NEW RECORDS -->
  <div id="AccordionContainer" class="AccordionContainer">
  <div onclick="runAccordion(1);">
    <div class="AccordionTitle" onselectstart="return false;">
      New Records
    </div>
  </div>
      
  <div id="Accordion1Content" class="AccordionContent">
     <table>
         <form action="update_subject.php" method="post" name="updateNew">
            <th>Subject ID</th>
            <th>Name</span>
            <th>Woman's <br/> Condition</th>
            <th>Age</span>
            <th>File Uploaded</th>
            <th>View <br/> Report</th>
            <th>Printed</th>
            <th>Flag with<br />Comments</th>
        <!-- table data -->         
            <?php   
                $count = 1;
                while($subject=mysql_fetch_array($result_subjects)) 
                {

                    //first find out what color background stripe to use.
                    $stripe=($count%2>0)?'class="zebra1"':'class="zebra2"';
     
                    //then print the row
                    echo "<tr $stripe >";

                        //SubjectID
                        echo "<td width=10%> $subject[0] </td>";

                         //Name
                        echo "<td> $subject[1]  $subject[2] </td>";
                  
                        // Woman's Condition 
                        echo '<td>', $subject[3]. '</td>'; 

                        // Age
                        echo '<td>', $subject[4]. '</td>';

                        //File Uploaded
                        echo '<td>', $subject[5]. '</td>';

                        //View Report
                        echo '<td><a href="NutritionAssessmentView.php?project='. $project .
                                '&SubjectID=' .$subject[0]. 
                                '&Condition=' .$subject[3]. 
                                '&Age=' .$subject[4]. 
                                '" target="_blank">VIEW</a></td>';
                        echo '<td>';
                    ?>
                    
                       <!-- //Checkbox Printed -->
                        <input type="checkbox" name="Printed[]" value="<?php echo $subject[0]; ?>">Printed<br>
                
                    <?php   
                        echo '</td>';
                        echo '<td>';
                    ?>
                       <!-- Comments box -->
                           <!--<input type="checkbox" name="CommentID[]" value="<?php echo $subject[0]; ?>">-->
                           <input type="text" name="Comments[]" value="none"><br>
                           <input type="hidden" name="SubjectID[]" value="<?php echo $subject[0]; ?>">
                      
                    <?php
                        
                    echo '</tr>'; //end of row

                    $count++;

                }// end while      
          ?>
     </table> 
            <div id="update">
            <input type="submit" name="UpdatePrint[]">
            <!--<input type="submit" name="Comment" value="Add Comments" style="margin-left:125px">-->
            </div>
        </form>
  </div> <!-- Accordion 1 content -->
     
<!-- Accordion 2: FLAGGED RECORDS -->
  <div onclick="runAccordion(2);">
    <div class="AccordionTitle" onselectstart="return false;">
      Flagged Records
    </div>
  </div>

  <div id="Accordion2Content" class="AccordionContent">
    <table>
        <form action="update_subject.php" method="post" name="updateFlag">
        <tr>
            <th align="left">Subject ID</th>
            <th>Name</th>
            <th>Woman's <br/> Condition</th>
            <th>Age</th>
            <th>File Uploaded</th>
            <th>View <br/> Report</th>
            <th>Comments</th>
            <th>Flagged</th>
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

                    //File Uploaded
                    echo '<td>', $flagged[6]. '</td>';

                    //View Report
                    echo '<td><a href="NutritionAssessmentView.php?project='. $project .
                            '&SubjectID=' .$flagged[0]. 
                            '&Condition=' .$flagged[3]. 
                            '&Age=' .$flagged[4]. 
                            '" target="_blank">VIEW</a></td>';
                    
                    // Comments
                    echo '<td>', $flagged[5]. '</td>';
                    
                    echo '<td>';
                    ?>
                    
                   <!-- //Checkbox -->
                    <input type="checkbox" name="Flagged[]" value="<?php echo $flagged[0]; ?>">Fixed, no longer needs to be flagged.<br>
                    
                    <?php   
                    echo '</td>';
                    
                echo '</tr>'; //end of row

                $count++;

            }// end while      
      ?>
     </table>    
            <div id="update2">
            <input type="submit" name="UpdateFlag[]"><br>
            </div>
        </form>
   </div> <!-- Accordion 2 content-->
    
<!-- Accordion 3: PRINTED RECORDS -->
  <div onclick="runAccordion(3);">
    <div class="AccordionTitle" onselectstart="return false;">
      Printed Records
    </div>
  </div>

  <div id="Accordion3Content" class="AccordionContent">
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

                        // View Report
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
  </div> <!-- Accordion 3 content-->
 
<!-- Accordion 4: LESS THAN 3 RECALLS RECORDS -->
<!-- INCENTIVES for RSG only --> 
       <?php
           if ($project == 'RSG') {      
       ?>    
  <div onclick="runAccordion(4);">
    <div class="AccordionTitle" onselectstart="return false;">
      Less Than 3 Recalls Conducted
    </div>
  </div>

  <div id="Accordion4Content" class="AccordionContent">
    <table>
             
              <tr>
               <th align="left">Subject ID</th>
               <th>Name</th>
               <th>Woman's <br/> Condition</th>
               <th>Age</th>
               <th>Number of Interviews <br />Completed</th>
               <th>Last Interview <br />Completed On</th>
               <th>Print Generic<br />Report</th>
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
                            
                            //View Generic Report  TDUFF: see vars: project[], subject[], incentives[]
                            echo '<td><a href="NutritionAssessmentGeneric.php?project='. $project .
                                '&SubjectID=' .$incentives[0]. 
                                '&Condition=' .$incentives[3]. 
                                '&Age=' .$incentives[4]. 
                                '" target="_blank">VIEW</a></td>';

                        echo '</tr>'; 
                        //end of row

                        $count++;
                    }//end if
                }// end while  
               
             }//end if    
          ?>
    </table>
  </div> <!-- Accordion 4 content -->

</div>
</body>
</html>
