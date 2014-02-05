<?php
require_once('Database.php');

    $db = new Database();
    $db->Connect();
   
    
    // Flagged Records
    if(isset($_POST['Flagged'])){
        // unflag each record
       foreach ($_POST['Flagged'] as $key => $value) {
           $sql = "UPDATE subject
                   SET flagged = 'N'
                   , comments = ''
                   WHERE subjectID = $value";
           echo $sql;
           echo "<br />";
           if ($db->Query($sql)) {
                    echo "updated ID $value.";
                    echo "<br />";
            } else {
                echo "failed to update ID $value.";
                echo "<br />";
            }
       }
    } else {
        //DEBUG
//        echo "Not flagged in Comments";
//        echo "<br />";
    }   

    //Printed Records
    if(isset($_POST['Printed'])){
        // mark each record as Printed
        foreach ($_POST['Printed'] as $key => $value){
            $sql = "UPDATE subject
                    SET printed = 'Y'
                    WHERE subjectID = $value";
            echo $sql;
            echo "<br />";
            if ($db->Query($sql)) {
                    echo "updated ID $value.";
                    echo "<br />";
            } else {
                echo "failed to update ID $value.";
                echo "<br />";
            }
        }
//        DEBUG
//        $N = count($_POST['Printed']);
//        echo "N =" . $N;
//        var_dump($_POST['Printed']);
    } else {
        //DEBUG
//        echo "Record not printed";
//        echo "<br />";
    }
    
    
    //Add Comments to Records
    if(isset($_POST['Comments'])){
       // Update Comments for each record that does not have "none" for text
       // and set "Flagged" column
        $keys = ($_POST['SubjectID']);
        $values = ($_POST['Comments']);
        
        $newArray = array_combine($keys, $values);
        
        //DEBUG
        //var_dump($newArray);
        
        foreach($newArray as $subjectID => $comments) {
            if ($comments != 'none') {
                $sql = "UPDATE subject
                    SET flagged = 'Y'
                    , comments = '$comments'
                    WHERE subjectID = $subjectID";
                echo $sql;
                echo "<br />";
                
                if ($db->Query($sql)) {
                    echo "updated.";
                } else {
                    echo "failed to update";
                }
            } else {
                //DEBUG
//                echo "SubjectID $subjectID was not updated because comments were $comments";
//                echo "<br />";
            }
        }
    } else {
        //DEBUG
//        echo "No comments to add.";
//        echo "<br />";
    }

/*// Check if button name "Submit" is active, do this 
        
        $query = "UPDATE subject 
                  SET printed = 'Y'
                  WHERE subjectID = $id";
        if $db->Query($query) {
            echo "updated";
        } else {
            echo "failed to update";
        }    
    }
   */ 
    

?>
