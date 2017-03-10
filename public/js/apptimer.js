var myVar;
var mjcounter=0;

$( document ).ready(function() {
  if(oTmr!=12){  
    myVar = setInterval(myTimer, 60000);
  }
});

function myTimer() {
    mjcounter++;
    if(mjcounter>=10){
        var href=$("#navlogout").attr("href");
        window.location.assign(href);
    }
}
