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
        <?php
        // put your code here
        ?>

    <div id="AccordionContainer" class="AccordionContainer">

  <div onclick="runAccordion(1);">
    <div class="AccordionTitle" onselectstart="return false;">
      Accordion 1
    </div>
  </div>
  <div id="Accordion1Content" class="AccordionContent">
    I Am Accordion 1.
  </div>

  <div onclick="runAccordion(2);">
    <div class="AccordionTitle" onselectstart="return false;">
      Accordion 2
    </div>
  </div>
  <div id="Accordion2Content" class="AccordionContent">
    I Am Accordion 2.
  </div>

  <div onclick="runAccordion(3);">
    <div class="AccordionTitle" onselectstart="return false;">
      Accordion 3
    </div>
  </div>
  <div id="Accordion3Content" class="AccordionContent">
    I Am Accordion 3.
  </div>

  <div onclick="runAccordion(4);">
    <div class="AccordionTitle" onselectstart="return false;">
      Accordion 4
    </div>
  </div>
  <div id="Accordion4Content" class="AccordionContent">
    I Am Accordion 4.
  </div>

  <div onclick="runAccordion(5);">
    <div class="AccordionTitle" onselectstart="return false;">
      Accordion 5
    </div>
  </div>
  <div id="Accordion5Content" class="AccordionContent">
    I Am Accordion 5.
  </div>

</div>
</body>
</html>
