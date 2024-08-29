$(document).ready(function(){

    //INIT
    colER();
    tableERdata();

    $("input[name=rdtype]").on('change', function() {
      colER();
          // renER();
      tableERdata();
      $("#tbERxC tbody").html("");
      if ($(this).val() == "mes")
        $("#btn_ER600").prop( "disabled", false);
      else 
        $("#btn_ER600").prop( "disabled", true );
    });

    $("#ERdata select[name=company]").on('change', function(){
       tableERdata();
       $("#tbERxC tbody").html("");
     });


    $('#btn_capture').click( function () {
   // var load = bootbox.dialog({
   //      message: '<p><i class="fa fa-spin fa-spinner"></i> Loading...</p>',
   //      closeButton: false
   //  });
    var rdtype = $("[name=rdtype]:checked").val();
    var id = $("select[name=company]").val();
    var name = $("[name=company] option:selected").text();
    var month = $("#date_r option:selected").text();
        $.ajax( {
            url: "newtables.php",
            method: "post",
            async: false,
            data: {idcompany: id, category: rdtype, col_name:name},
            success: function(data) {

                // close load
                // load.modal('hide');
                bootbox.dialog({
                title: "<h1>Capture On "+month+"</h1>",
                 message: data,
                 size: 'lg',
                 onEscape: true,
                buttons: {
                    ok: {
                        label: 'Cerrar',
                        className: 'btn-secondary'
                       }
                    }
                });
            }
        });
    });

   $("#btn_ER600").click(function(e) {
        // report infofin x idcompany
        ERxC();
    });

// END DOCUMENT
});

/*********FUNCTIONS**********/

function colER(){
    var rdtype = $('#ERdata input[name=rdtype]:checked').val();
    $.ajax( {
      url: "op/op.php",
      method: "GET",
      data: {type: rdtype, op: "companies", section: ""},
      global: false,
      async: false,
      success: function(tb) {
        $("#ERdata select[name=company]").html(tb);
      }           
    });
}

// function ERxC() {
//     var company = $("#ERdata select[name=company]").val();
//     var nMonth = $("#datebox [name=month]").val();
//     var nYear = $("#datebox [name=year]").val();
//       $.ajax( {
//       url: "op/er.php",
//       method: "post",
//       global: false,
//       data: {er: "ERxCompany", idcompany: company, month: nMonth, year: nYear},
//       //async: false,
//       beforeSend: function() {
//         // setting a timeout
//        $("#tbERxC tbody").html("");
//        $("#spinner2").append('<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>');//crea spinner de carga.
//       },
//       success: function(tb) {
//         $("#tbERxC tbody").html(tb);
//       },
//       complete: function(){
//          $("#spinner2 div").remove(".spinner-grow");//elimina spinner
//       }
//     });
// }

function tableERdata() {
  var company = $("#ERdata select[name=company]").val();
  // if (company >= 800 && company < 900)
  //     $("#ERdata #inputmoneda").text("DLL $");
  // else
  //     $("#ERdata #inputmoneda").text("MXN $");
  var nMonth = $("#datebox select[name='month']").val();
  var nYear = $("#datebox [name='year']").val();
  var rdtype = $('#ERdata input[name=rdtype]:checked').val();
      $.ajax( {
      url: "op/op.php",
      method: "GET",
      global: false,
      data: {idcompany: company, month: nMonth, year: nYear, op: "queryER", type_c: rdtype, section: ""},
      //async: false,
      beforeSend: function() {
        // setting a timeout
       $("#tbERdata").html("");
       $("#spinner").append('<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>');//crea spinner de carga.
      },
      success: function(tb) {
        $("#tbERdata").html(tb);
      },
      complete: function(){
         $("#spinner div").remove(".spinner-grow");//elimina spinner
      }
    });
}

