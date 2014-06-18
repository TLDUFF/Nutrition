

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <link rel="stylesheet" type="text/css" href="accordionContent.css" />
        <script src="accordion.js"></script>
        <title>Subject Listings</title>
    </head>
    <body>

<!-- Accordion 1: NEW RECORDS ------------------------------------------------------------------------------->
  <div id="AccordionContainer" class="AccordionContainer">
  <div onclick="runAccordion(1);">
    <div class="AccordionTitle" onselectstart="return false;">
      New Records
    </div>
  </div>

  <div id="Accordion1Content" class="AccordionContent">
     <table>
         <form name="updateNew" action="subjects.php" method="POST" >

            <th>Study/Preg ID</th>
            <th>Name</span>
            <th>Woman's <br/> Condition</th>
            <th>Age</span>
            <th>File Uploaded</th>
            <th>Number of Interviews <br/> Completed</th>
            <th>View <br/> Report</th>
            <th>View <br/> Cover Letter</th>
            <th>Printed</th>
            <th>Flag with<br />Comments</th>
        <!-- table data -->
            <?php
                $count_new = 1;
                while($subject=mysql_fetch_array($result_subjects))
                {

                    //first find out what color background stripe to use.
                    $stripe=($count_new%2>0)?'class="zebra1"':'class="zebra2"';

                    //then print the row
                    echo "<tr $stripe >";


                        //Study/Preg ID
                        echo "<td width=10%> $subject[0] </td>";

                         //Name
                        echo "<td> $subject[1]  $subject[2] </td>";

                        // Woman's Condition
                        echo '<td>', $subject[3]. '</td>';

                        // Age
                        echo '<td>', $subject[4]. '</td>';

                        //File Uploaded
                        echo '<td>', $subject[5]. '</td>';

                        //Num Interviews
                        $numInterviews = countInterviews($subject[0]);
                        echo '<td>', $numInterviews.  '</td>';

                        //View Report
                        echo '<td><a href="NutritionAssessmentView.php?project='. $_SESSION['myproject'] .
                                '&StudyID=' .$subject[0].
                                '&Condition=' .$subject[3].
                                '&Age=' .$subject[4].
                                '&Interviews=' .$numInterviews.
                                '" target="_blank">VIEW</a></td>';

                        //View Cover Letter (Complete)
                        echo '<td><a href="CoverLetter.php?project='. $_SESSION['myproject'] .
                                '&lastname=' .$subject[2].
                                '&StudyID=' .$subject[0].
                                '&Interviews=' .$numInterviews.
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
                           <input type="text" name="Comments[]" value=""><br>
                           <input type="hidden" name="StudyID[]" value="<?php echo $subject[0]; ?>">

                    <?php

                    echo '</tr>'; //end of row

                    $count_new++;

                }// end while
          ?>
     </table>
            <div id="update">
             <input type="submit" name="UpdatePrint">
            </div>
        </form>
  </div> <!-- Accordion 1 content -->

<!-- Accordion 2: FLAGGED RECORDS ---------------------------------------------------------------------------------->
  <div onclick="runAccordion(2);">
    <div class="AccordionTitle" onselectstart="return false;">
      Flagged Records
    </div>
  </div>

  <div id="Accordion2Content" class="AccordionContent">
    <table>
        <form name="updateFlag" action="subjects.php" method="POST">

        <tr>
            <th align="left">Study/Preg ID</th>
            <th>Name</th>
            <th>Woman's <br/> Condition</th>
            <th>Age</th>
            <th>File Uploaded</th>
            <th>Number of Interviews <br/> Completed</th>
            <th>View <br/> Report</th>
            <th>View <br/> Cover Letter</th>
            <th>Comments</th>
            <th>Flagged</th>
        </tr>
<!-- table data-->
 <?php
            $count_flag = 1;
            while($flagged=mysql_fetch_array($result_flagged))
            {

                //first find out what color background stripe to use.
                $stripe=($count_flag%2>0)?'class="zebra1"':'class="zebra2"';

                //then print the row
                echo '<tr '.$stripe.'>';

                    // Study/Preg ID
                    echo '<td width=10%>', $flagged[0]. '</td>';

                    // Name
                    echo '<td>', $flagged[1].' '. $flagged[2]. '</td>';

                    // Woman's Condition
                    echo '<td>', $flagged[3]. '</td>';

                    // Age
                    echo '<td>', $flagged[4]. '</td>';

                    //File Uploaded
                    echo '<td>', $flagged[6]. '</td>';

                    //Num Interviews
                    $numInterviews = countInterviews($flagged[0]);
                    echo '<td>', $numInterviews.  '</td>';

                    //View Report
                    echo '<td><a href="NutritionAssessmentView.php?project='. $_SESSION['myproject'] .
                            '&StudyID=' .$flagged[0].
                            '&Condition=' .$flagged[3].
                            '&Age=' .$flagged[4].
                            '&Interviews=' .$numInterviews.
                            '" target="_blank">VIEW</a></td>';

                    //View Cover Letter
                    echo '<td><a href="CoverLetter.php?project='. $_SESSION['myproject'] .
                            '&StudyID=' .$flagged[0].
                            '&lastname=' .$flagged[2].
                            '&Interviews=' .$numInterviews.
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

                $count_flag++;

            }// end while
      ?>
     </table>
            <div id="update2">
            <?php    if ($count_flag > 1) { //hide submit button, if there are no records displayed
                        echo '<input type="submit" name="UpdateFlag"><br>';
                    }
            ?>
            </div>
        </form>
   </div> <!-- Accordion 2 content-->

<!-- Accordion 3: PRINTED RECORDS --------------------------------------------------------------------------------------->
  <div onclick="runAccordion(3);">
    <div class="AccordionTitle" onselectstart="return false;">
      Printed Records
    </div>
  </div>

  <div id="Accordion3Content" class="AccordionContent">
     <table>
         <form name="moveNew" action="subjects.php" method="POST">
                <tr>
                    <th align="left">Study/Preg ID</th>
                    <th>Name</th>
                    <th>Woman's <br/> Condition</th>
                    <th>Age</th>
                    <th>File Uploaded</th>
                    <th>Number of Interviews <br/>Completed</th>
                    <th>View <br/> Report</th>
                    <th>View <br/> Cover Letter</th>
                    <th>Move to New <br/> Records</th>
                </tr>
<!-- table data-->
     <?php
                $count_print = 1;
                while($printed=mysql_fetch_array($result_printed))
                {

                    //first find out what color background stripe to use.
                    $stripe=($count_print%2>0)?'class="zebra1"':'class="zebra2"';

                    //then print the row
                    echo '<tr '.$stripe.'>';

                        // Study/Preg ID
                        echo '<td width=10%>', $printed[0]. '</td>';

                        // Name
                        echo '<td>', $printed[1].' '. $printed[2]. '</td>';

                        // Woman's Condition
                        echo '<td>', $printed[3]. '</td>';

                        // Age
                        echo '<td>', $printed[4]. '</td>';

                        //File Uploaded
                        echo '<td>', $printed[5]. '</td>';

                        //Num Interviews
                        $numInterviews = countInterviews($printed[0]);
                        echo '<td>', $numInterviews.  '</td>';

                        // View Report
                        echo '<td><a href="NutritionAssessmentView.php?project='. $_SESSION['myproject'] .
                                '&StudyID=' .$printed[0].
                                '&Condition=' .$printed[3].
                                '&Age=' .$printed[4].
                                '&Interviews=' .$numInterviews.
                                '" target="_blank">VIEW</a></td>';

                        //View Cover Letter
                        echo '<td><a href="CoverLetter.php?project='. $_SESSION['myproject'] .
                            '&StudyID=' .$printed[0].
                            '&lastname=' .$printed[2].
                            '&Interviews=' .$numInterviews.
                            '" target="_blank">VIEW</a></td>';

                        echo '<td>';
                        ?>

                       <!-- //Checkbox -->
                        <input type="checkbox" name="Move[]" value="<?php echo $printed[0]; ?>">Move to New Records<br>

                        <?php
                        echo '</td>';

                    echo '</tr>'; //end of row

                    $count_print++;

                }// end while

          ?>
     </table>
      <div id="update3">
            <?php    if ($count_print > 1) { //hide submit button, if there are no records displayed
                        echo '<input type="submit" name="MoveToNew"><br>';
                    }
            ?>
      </div> <!-- update3 -->
      </form>
  </div> <!-- Accordion 3 content-->

<!-- Accordion 4: LESS THAN 3 RECALLS RECORDS ------------------------------------------------------------------->
<!-- INCENTIVES for RSG only -->
 <?php
           if ($_SESSION['myproject'] == 'RSG') {
       ?>
  <div onclick="runAccordion(4);">
    <div class="AccordionTitle" onselectstart="return false;">
      One Recall Completed
    </div>
  </div>

  <div id="Accordion4Content" class="AccordionContent">
    <table>

              <tr>
               <th align="left">Study/Preg ID</th>
               <th>Name</th>
               <th>Woman's <br/> Condition</th>
               <th>Age</th>
               <th>Number of Interviews <br />Completed</th>
               <th>Last Interview <br />Completed On</th>
               <th>Print Generic<br />Report</th>
               <th>Print Cover<br />Letter</th>
              </tr>

        <?php
                $count_one = 1;
                while($incentives=mysql_fetch_array($result_incentives))
                {

                    //first find out what color background stripe to use.
                    $stripe=($count_one%2>0)?'class="zebra1"':'class="zebra2"';

                    if ($incentives[5]<2) { //only print those with one recall.

                        //then print the row
                        echo '<tr '.$stripe.'>';

                            // Study/Preg ID
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

                            //View Generic Report
                            echo '<td><a href="NutritionAssessmentGeneric.php?project='. $_SESSION['myproject'] .
                                '&StudyID=' .$incentives[0].
                                '&Condition=' .$incentives[3].
                                '&Age=' .$incentives[4].
                                '" target="_blank">VIEW</a></td>';

                            //View Cover Letter (Partial)
                            echo '<td><a href="CoverLetter.php?project='. $_SESSION['myproject'] .
                                '&lastname=' .$incentives[2].
                                '&StudyID=' .$incentives[0].
                                '&One=' .$incentives[5].
                                '" target="_blank">VIEW</a></td>';

                        echo '</tr>';
                        //end of row

                        $count_one++;
                    }//end if
                }// end while

             }//end if
          ?>
    </table>
  </div> <!-- Accordion 4 content -->
</div>
<?php
    //Update DB tables as $_POST vars are set.
    require_once('Database.php');

    $db = new Database();
    $db->Connect();

    // Un Flag Records
    if(isset($_POST['Flagged'])){
        // unflag each record
       foreach ($_POST['Flagged'] as $key => $value) {
           $sql = "UPDATE subject
                   SET flagged = 'N'
                   , comments = ''
                   WHERE subjectID = $value";

           if (!($db->Query($sql))) {
             echo mysql_error($sql);
           }
        header('Location:./subjectList.php');
       }
    }

    //Printed Records
    if(isset($_POST['Printed'])){
        // mark each record as Printed
        foreach ($_POST['Printed'] as $key => $value){
            $sql = "UPDATE subject
                    SET printed = 'Y'
                    WHERE subjectID = $value";
            if (!($db->Query($sql))) {
             echo mysql_error($sql);
            }
        header('Location:./subjectList.php');
        }
    }


    //Add Comments to Records
    if(isset($_POST['Comments'])){
       // Update Comments for each record that does not have "none" for text
       // and set "Flagged" column
        $keys = ($_POST['StudyID']);
        $values = ($_POST['Comments']);

        $newArray = array_combine($keys, $values);

        foreach($newArray as $StudyID => $comments) {
            if ($comments != '') {
                $sql = "UPDATE subject
                    SET flagged = 'Y'
                    , comments = '$comments'
                    WHERE subjectID = $StudyID";

            if (!($db->Query($sql))) {
             echo mysql_error($sql);
           }
            header('Location:./subjectList.php');
           }// end if
       }// end foreach
    }// end if isset

    if(isset($_POST['Move'])){
        // Update printed = N, flagged = N to move record to "New Records"
        foreach ($_POST['Move'] as $key => $value) {
           $sql = "UPDATE subject
                   SET flagged = 'N'
                   , printed = 'N'
                   WHERE subjectID = $value";

           if (!($db->Query($sql))) {
             echo mysql_error($sql);
           }
        header('Location:./subjectList.php');
       }
    }// end if isset
   ?>
</body>
</html>



