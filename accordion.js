/*
 * Opens and closes accordions on subjectList.php page (see subjects.php)
 */

var ContentHeight = 300;
var TimeToSlide = 250.0;

var openAccordion = '';

function runAccordion(index)
{
  var nID = "Accordion" + index + "Content";
  if(openAccordion == nID)
  {
    nID = '';
  }
    set(new Date().getTime(),TimeToSlide,openAccordion, nID);

  openAccordion = nID;
}

function set(lastTick, timeLeft, closingId, openingId)
{
    setTimeout(function(){animate(lastTick,timeLeft,closingId, openingId );}, 33);
}
function animate(lastTick, timeLeft, closingId, openingId)
{
  var curTick = new Date().getTime();
  var elapsedTicks = curTick - lastTick;

  var opening = (openingId == '') ? null : document.getElementById(openingId);
  var closing = (closingId == '') ? null : document.getElementById(closingId);

  if(timeLeft <= elapsedTicks)
  {
    if(opening != null)
      opening.style.height = ContentHeight + 'px';

    if(closing != null)
    {
      closing.style.display = 'none';
      closing.style.height = '0px';
    }
    return;
  }

  timeLeft -= elapsedTicks;
  var newClosedHeight = Math.round((timeLeft/TimeToSlide) * ContentHeight);

  if(opening != null)
  {
    if(opening.style.display != 'block')
      opening.style.display = 'block';
    opening.style.height = (ContentHeight - newClosedHeight) + 'px';
  }

  if(closing != null)
  {

    closing.style.height = newClosedHeight + 'px';
  }
    set(curTick,timeLeft,closingId, openingId);
}

$("#accordion1").click(function(){runAccordion(1);});
$("#accordion2").click(function(){runAccordion(2);});
$("#accordion3").click(function(){runAccordion(3);});
$("#accordion4").click(function(){runAccordion(4);});
