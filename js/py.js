$(document).ready(function(){
	$(".datebox").remove(); //elimina elemento datebox
	$("[id='USA ALIMENTOS']").text("PROJECTION ALIMENTOS EU");
    $("#py-data").submit(function(e) {
    	e.preventDefault();
    	var section = $("#span_section").children().attr("id");
    	insertPy(section);
    });
    //radio buttons 
    $("input[name=rdaut]").click( function() {
         var test = $(this).val();
         if (test == "std") {
         	$('#py-std').removeClass("hide");
         	$("#all_id").text("--- select company---");
         } else { 
         	$('#py-std').addClass("hide");
         	$("#all_id").text("--- all companies ---");
         	}
    } );
	if($('#rdaut').is(':checked')) { 
		$('#py-std').addClass("hide");
		$("#all_id").text("--- all companies ---");
	}
	else {
		$('#py-std').removeClass("hide");
		$("#all_id").text("--- select company---");
	}
	
//input numbers format comma with decimals
	$("input[name=netsales], input[name=opexp], input[name=gaexp]").keyup(function(event) {
  		// skip for arrow keys
  		if(event.which >= 37 && event.which <= 40 || event.which == 173 || event.which == 190) return;
       //format number, ejem -5,000.0001
      $(this).val(function(index, value) {
        var parts = value.toString().replace(/(?!-)[^0-9.]/g, "").split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
      });
	});

});
function insertPy(section){
	$.ajax({
	    url: "op/py.php",
	    method: "post",
	    global: false,
	    data: $("form").serialize() + "&section="+section,
	    dataType: "text",
	    success: function(strmsg) {
      		if (strmsg != "") 
      			alert(strmsg); 
	    	setTimeout(function(){// wait for n secs(2)
           		location.reload(); // then reload the page.(3)
      		}, 5);
	    }
	});
}



