<?php session_start();
require "views/header.view.php";
require "config/conn.php";
$tiempo_inicio = microtime_float();

 ?>
<style type="text/css">
  input[type=text],#py-data input[type=number], #py-data select{
  width: 8em; 
  height: 2em; 
  margin-top: 6px; 
  padding: 0 10px;
  text-align: right;
  -moz-border-radius: 0 4px 4px 0;
  -webkit-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;
  background-color: #fff; 
  -webkit-box-shadow: 1px 2px 5px rgba(0,0,0,.09); 
  -moz-box-shadow: 1px 2px 5px rgba(0,0,0,.09); 
  box-shadow: 1px 2px 5px rgba(0,0,0,.09); 
  border: solid 1px #cbc9c9;
}

label{
  display: inline-block;
  width: 10em;
  background-color: rgb(187,141,23);
  padding: 6px 0px 6px 3px;
  margin-left: 15px;
  -webkit-border-radius: 4px 0px 0px 4px; 
  -moz-border-radius: 4px 0px 0px 4px; 
  border-radius: 4px 0px 0px 4px;
  color: white;
  -webkit-box-shadow: 1px 2px 5px rgba(0,0,0,.09);
  -moz-box-shadow: 1px 2px 5px rgba(0,0,0,.09); 
  box-shadow: 1px 2px 5px rgba(0,0,0,.09); 
  border: solid 0px #cbc9c9;
  padding-right: 6px;
  text-align: right;
  }

  form {
  margin: 0 4px;
  color: white;
}
.rdbtn{
  color: initial;
  font-weight: 500;
}

.msg{
  margin: auto;
  display: flex;
  color: black;
  flex-direction: column;
  align-items: center;
}
.success{
  color: green;
}
.error{
  color: red;
}

</style>
<!-- modificado el 2020-ene-14 -->
<div class="f-center">
    <div style="position: fixed; top: 42px; left: 4px; width: 20em">
      <form id="c_month">
        <label style="width: 1em; margin: 0;"><i class="fas fa-calendar"></i></label><select  id="date_r" name="month" style="width: 10em;text-align: left; margin: 8px auto;"> 
        <?php for ($i=1; $i <= 12; $i++) { //todos los meses
        echo "<option value='".str_pad($i, 2, "0", STR_PAD_LEFT)."' ";
        if (date('m') == $i) echo "selected='1'";
        echo "> ".date('F',strtotime('01.'.$i.'.2001'))."</option>"; }?>
        </select>
        <select name="year" style="width: 6em;"> 
          <option> <?php echo date('Y', strtotime('-1 year'));?></option> 
          <option selected="1"><?php echo date('Y');?></option>
          <option><?php echo date('Y', strtotime('+1 year'));?></option>
        </select>
      </form>
<!--       <form action="" method="post"
          name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
        <div>
            <span style="color: black;">Choose CSV
                File</span><input type="file" name="file"
                id="file" accept=".csv" style="color: black;">
            <button type="submit" id="submit" name="import"
                class="button">Import <i class="fas fa-upload"></i></button>
            <br />
        </div>
      </form> -->
    </div>
<section>
  <!-- iniciar fecha con mes actual -->
    <h2>Generate Presentation</h2>
  <div id="schemaER" style="border: 2px solid green;border-radius: 6px;margin: 8px; width: 100%;">
    <div style="display: flex;align-items: center;flex-direction: column; margin: 4px;">
            <button id="btn_download_data" class="button">Process Data <i class="fas fa-database"></i></button>
            <p id="lastrun"></p>
    </div>
    <hr style="color: green; width: 85%;">
    <div style="display: flex;align-items: center;flex-direction: column;">
       <button id="btn_download_app" class="button">Download Presentation <i class="fas fa-file-powerpoint"></i></button>
      <p id="pptx_release" style="font-weight: bold;"></p>
    </div>

  </div>
</section>
    <div id="wait-download" class="loader-circle"></div>
 <h2>Capture Data</h2>
<section>
  <div style="display: inline-block; width: 21em;border: 2px solid green;border-radius: 6px;margin: 8px; padding: 8px 8px 8px 0;">
    <form id="inicioER" method="post">
      <label>Tipo de cambio</label><input type="text" name="moneda" placeholder="$0.00">
      <label>Capital</label><input type="text" name="capital" placeholder="$0">
      <label>Intereses</label><input type="text" name="intereses" placeholder="$0">
      <br><br>
      <div style="display: flex;align-items: center;flex-direction: column;">
        <button type="submit" class="button">Save <i class="fas fa-save"></i></button>
      </div>
        <div id="msg-capture-3" class="msg"></div>
    </form>
  </div>
  </section>

<h2>Capture settings ER</h2>
<div style="display: flex; align-items: center; flex-direction: row;"> <!-- inicio bloque x empresa -->
<section>
<!-- capture -->
  <div style="display: flex;align-items: center;flex-direction: column;border: 2px solid green;border-radius: 6px;margin: 8px;width: 30em;">
    <form id="ERdata" method="post" style="padding: 10px;">

        <div class="rdbtn" style="display: inline-block; padding-left: 6px;">
          <span><input type="radio" name="rdtype" value="mes" checked="true">Mes / Month </span><br>
          <span><input type="radio" name="rdtype" value="division" >Division</span><br>
          <span><input type="radio" name="rdtype" value="pymes" id="rdpy">Projection  <span></span></span><br>
        </div>
        <br><br>
      <div style="padding: 4px 0;">
        <label>Company</label><select name="company" style="width: 10em;text-align: center;">
            <?php  
            $r = squery("select col_name as company, idcompany from cfg_reports_col order by info_r, indexcol ");
            for ($i=0; $i < count($r); $i++) { 
             echo "<option value='".$r[$i]['idcompany']."'> ".$r[$i]['company']." </option>";
            } unset($r);  ?>

          </select>
      </div>
      <label>Description/concepto</label><select name="indexren" style="width: 16em;text-align: center;">
            <?php  
            $renglones = squery("select renc_descripcion as renglon, indexren from cfg_reports_ren where info_r = 'mes' order by indexren");
            for ($i=0; $i < count($renglones); $i++) { 
             echo "<option value='".$renglones[$i]['indexren']."'> ".$renglones[$i]['renglon']." </option>";
            } unset($renglones);  ?>   
        </select><br>
        <label>$ </label><input type="text" name="valnum" placeholder="$0">
        <br><br>
    <div style="display: flex;align-items: center;flex-direction: column;">
      	<button id="btn_saveER" type="submit" class="button">Save <i class="fas fa-save"></i></button>
    </div>
        <div id="msg-capture" class="msg"></div>
    </form>
  </div>
</section>
  <div class="divBorder">
    <table class="h-table">
      <thead>
        <tr>
          <th>Data Captured</th>
        </tr>
      </thead>
    </table>
    <table class="b-table">
      <thead>
        <tr>
        <th>Concepto</th><th>Mes</th><th>Acumulado</th>
        </tr>
      </thead>
      <tbody id="tbERdata">
        <!-- insertado x condigo js -->
      </tbody>
    </table>
  </div>
  <!-- final bloque x empresa -->
</div> 

  
</div> <!-- first div -->

<script type="text/javascript">
var monthName = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];

  $(document).ready(function(){
    
    nextMonth();
    $("#ERdata").submit(function(e) {
      e.preventDefault();
      insertER();
    });
    // $("#ERdiv").submit(function(e) {
    //   e.preventDefault();
    //   ERdiv();
    // });
    
    $("#inicioER").submit(function(e) {
      e.preventDefault();
      InicioER();
    });
    colER();
    renER();
    $("input[name=rdtype]").on('change', function() {
      colER();
      renER();
      tableERdata();
    });

    $("#frmCSVImport").on("submit", function () {
      //e.preventDefault();
      // Get form
      var form = $('#frmCSVImport')[0];
      // Create an FormData object 
      var data = new FormData(form);
            $.ajax({
              url: "op/import.php",
              type: "POST",
              enctype: 'multipart/form-data',
              data:  data,
              contentType: false,
              cache: false,
              processData: false,
              success: function(str){
                  alert(str);
              }
            });
        return true;
    });
  
//input numbers format comma with decimals
  $("input[name=valnum]").keyup(function(event) {
      // skip for arrow keys
      if(event.which >= 37 && event.which <= 40 || event.which == 173 || event.which == 190) return;
      //format number, ejem -5,000.0001
      $(this).val(function(index, value) {
        var parts = value.toString().replace(/(?!-)[^0-9.]/g, "").split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
      });
  });

  $("#tbERdata").ready(function(e) {
      tableERdata();
  });
  // $("#tbERdiv").ready(function(e) {
  //     tableERdiv();
  // });
  $("#inicioER").ready(function(e) {
      iniShow();
  });
  $("#ERdata select[name=company]").on('change', function() {
      tableERdata();
  });
  // $("#ERdiv select[name=company]").on('change', function() {
  //     tableERdiv();
  // });
  $("#c_month").on('change', function() {
      nextMonth();
      tableERdata();
      // tableERdiv();
      iniShow();
  });
 
}); //end document ready

function renER(){
    var rdtype = $('#ERdata input[name=rdtype]:checked').val();
    $.ajax( {
      url: "op/op.php",
      method: "GET",
      global: false,
      data: {type: rdtype, op: "renglones", section: ""},
      async: false,
      success: function(tb) {
        $("#ERdata select[name=indexren]").html(tb);
      }           
    });
}
function colER(){
    var rdtype = $('#ERdata input[name=rdtype]:checked').val();
    $.ajax( {
      url: "op/op.php",
      method: "GET",
      global: false,
      data: {type: rdtype, op: "companies", section: ""},
      async: false,
      success: function(tb) {
        $("#ERdata select[name=company]").html(tb);
      }           
    });
}
function nextMonth(){
  var year = $("[name='year']").val();
  var month = $("#c_month select[name='month']").val();
  var datenow = new Date(year+"-"+month);
  datenow.setMonth(datenow.getMonth() + 2, 1);
  $("#rdpy").next().text(monthName[new Date(datenow).getMonth()] + " " + year);
}

function tableERdata() {
  var company = $("#ERdata select[name=company]").val();
  if (company >= 800 && company < 900)
      $("#ERdata input[name=valnum]").prev().text("DLL $");
  else
      $("#ERdata input[name=valnum]").prev().text("MXN $");
  var nMonth = $("#c_month select[name='month']").val();
  var nYear = $("#c_month select[name='year']").val();
  var type = $("#ERdata input[name=rdtype]:checked").val();
      $.ajax( {
      url: "op/op.php",
      method: "GET",
      global: false,
      data: {idcompany: company, month: nMonth, year: nYear, op: "queryER", type_c: type, section: ""},
      //async: false,
      success: function(tb) {
        $("#tbERdata").html(tb);
      }
    });
}

// function tableERdiv() {
//   var company = $("#ERdiv select[name='company']").val();
//   var nMonth = $("#c_month select[name='month']").val();
//   var nYear = $("#c_month input[name='year']").val();
//   var type = $("#ERdiv input[name=rdtype]:checked").val();
//       $.ajax( {
//       url: "op/op.php",
//       method: "GET",
//       global: false,
//       data: {idcompany: company, month: nMonth, year: nYear, op: "queryER", type_c: type, section: ""},
//       //async: false,
//       success: function(tb) {
//         $("#tbERdiv").html(tb);
//       }           
//     });
// }

function insertER(){
  $.ajax({
      url: "op/er.php",
      method: "post",
      global: false,
      data: $("#ERdata, #c_month").serialize() + "&section= " + "&er=settgs",
      dataType: "text",
      success: function(strmsg) {
        // setTimeout(function(){// wait for n secs(2)
        //       location.reload(); // then reload the page.(3)
        //   }, 10);
          if (strmsg != "")  //insert msg (3 secs)
            $('#msg-capture').append('<span class="fade-msg">Error! Captured Fails..</span>');
          else
           $('#msg-capture').append('<span class="fade-msg">Captured Success!</span>');
          setTimeout(function(){// wait for n secs(2)
               $('.fade-msg').fadeOut();// then reload the page.(3)
          }, 3000);
          tableERdata();
      }
  });
}
function InicioER(){
  $.ajax({
      url: "op/er.php",
      method: "post",
      global: false,
      data: $("#inicioER, #c_month").serialize() + "&er=ini",
      dataType: "text",
      success: function(strmsg) {
        // setTimeout(function(){// wait for n secs(2)
        //       location.reload(); // then reload the page.(3)
        //   }, 10);
          if (strmsg != "")  //insert msg (3 secs)
            $('#msg-capture-3').append('<span class="fade-msg">Error! Captured Fails..</span>');
          else
           $('#msg-capture-3').append('<span class="fade-msg">Captured success</span>');
          setTimeout(function(){// wait for n secs(2)
               $('.fade-msg').fadeOut();// then fade msg
          }, 3000);
          
      }
  });
}

function iniShow() {
      $.ajax( {
      url: "op/er.php",
      method: "post",
      global: false,
      data: $("#inicioER, #c_month").serialize() + "&er=mainShow",
      dataType: "json",
      //async: false,
      success: function(data) {
        if (data) {
         $("#inicioER input[name='moneda']").val(data[2].value);
         $("#inicioER input[name='capital']").val(data[0].value);
         $("#inicioER input[name='intereses']").val(data[1].value);
        } else {
          $("#inicioER input").val('');
        }
      }           
    });
}


//JS Presentation

  $("#schemaER").ready(function() {
    var section = "CORPORATIVO"; //crear listbox section!!!!!!!!!!!!!!!!!!!!
    showLastRun(section);
    showrelease(section);
    var docID = setTimeout(Documentupdate, 30000, section); 

        //codigo de botones 
        $("#btn_download_data").click(function(e) {
          ProcessData(section);
        });

        $("#btn_download_app").click(function(e) {
            presentation(section);
        });

     }); 

//ajax functions

var stop; //contador para detener el metodo refresh
  function refreshRequest(section){
       $.ajax( {
          url: "op/op.php",
          method: "GET",
          cache: false,
          global: false,
          data: {op: "readimages", section: section},
          dataType: "json",
          success: function(result) { 
            if (result[0] !== undefined){
              if (result[0].search("ER") == 0) {
              clearTimeout(TimeoutID);
              CodeStop();
              updateStatus(section);
              showLastRun(section);
              showrelease(section);
              alert("pptx ready");
              }
              else{
                console.log(result[0]);
                console.log("pptx not ready");
              }
            }
            // else
            // console.log("undefined");
          },
          error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
            clearTimeout(TimeoutID);
            CodeStop();
            updateStatus(section);
        },
        complete: function(xhr,status){ //se ejecuta despues de success y error
          stop = stop + 1;
          console.log("intento "+stop);
        }
       });
        if (stop < 25){
         var TimeoutID = setTimeout(refreshRequest, 2000, section);       
        }else{
          CodeStop();
          updateStatus(section);
          showLastRun(section);
          $("#pptx_release").html("");
        }
  //});
  }

  function ProcessData(section){
      
      var mdate = $("#date_r").val();
      var ydate = $("[name='year']").val();
      var answer = confirm("Confirm Process For Generate Presentation "+ monthName[mdate-1] +" " +ydate);
      if (answer == true) {
          $.ajax( {
            url: "op/op.php",
            method: "GET",
            global: false,
            data: {op: "data", section: section, month: mdate, year: ydate},
            //async: false,
            beforeSend: function() {
              CodeBefore();
            },
            success: function(status) {
              if (status == "") {
                macro(section);
                console.log("ProcessData");
              } else{
                alert(status);
                CodeStop();
              }
            },
            error: function(){
              CodeStop();
            }
        });
      }
  }

  function macro(section){
    var ydate = $("[name='year']").val();
    $(".loader-circle").css( "display","block");
    $(".loader-circle").css( "border-top","6px solid #2f4aac"); //lodaer blue
      $.ajax( {
          url: "op/op.php",
          method: "GET",
          global: false,
          data: {op: "macro", section: section, year: ydate},
          //async: false,
          success: function() {
            stop = 0;
          refreshRequest(section);
          }           
        });
  }

  function presentation(section){
    $.ajax( {
        url: "op/op.php",
        method: "GET",
        data: {op: "readimages", section: section},
        dataType: "json",
        beforeSend: function(){
      CodeBefore();
      },
        success: function(result) {
          if (result.length > 0 && result[0].indexOf('ER') != -1) {
            var date = result[0].split(/[\s.]+/);
            result.splice(0,1); //elimina el primer dato del arreglo
            var strdate =  date[1]+' '+date[2]; //concatena el arreglo
            //pptx inicialize
            var pptx = new PptxGenJS();
            pptx.setLayout('LAYOUT_16x10');
            pptx.setAuthor('WEBINDICATOR');
        pptx.setCompany('EXELCO');
        pptx.setSubject('Month Report');
        pptx.setTitle('ER Presentation');
        pptx.defineSlideMaster({
          title: 'exelco',
          bkgd:  'FFFFFF',
          objects: [
          { 'image': { x:0, y:0, w:'100%', h:0.8, path:'img/pptx_exelco_slides.png' } }
          ],
        });
        //pptx cover page
            var cover = pptx.addNewSlide('exelco');
            cover.addImage({ path:'img/pptx_exelco.png', x:'30%', y:'25%', w:4, h:1.2 });
            cover.addText(strdate + '\nDIVISIÓN '+ section,  { x:'30%', y:'55%', w:4, h:1, bold:true, fontSize:20, align:'c', lineSpacing:28});

        //pptx slides
            result.forEach(function(str) {
                var titles = str.split("-");
          var slide = pptx.addNewSlide('exelco');
          slide.addText(titles[1]+'\n'+titles[2],  { x:0.2, y:0.1, w:'80%', h:0.8, bold:true, fontSize:14 });
          slide.addImage({ path:'files/'+ section +'/data/'+ str +'.png', x:0.4, y:0.9, w:9, h:5.3 });
            });
        //pptx save
              pptx.save('DIV '+ section + ' ' + strdate);
          }
          else 
            alert("Files not found, please process data");
      },
      complete: function(){
        CodeStop();
      }              
      });
  }

  function showLastRun(section){ //muestra fecha y hora de la ultima ejecucion
      $.ajax( {
          url: "op/op.php",
          method: "GET",
          global: false,
          data: {op: "lastrun", section: section},
          success: function(strMessage) {
              $("#lastrun").html("Last Run: "+strMessage);
          }
      }); //end subajax
  }

  function updateStatus(section){ //actualiza status de proceso
    $.ajax( {
          url: "op/op.php",
          method: "GET",
          global: false,
          data: {op: "update", section: section}
      }); //end subajax
  }

  function showrelease(section){
    $.ajax( {
          url: "op/op.php",
          method: "GET",
          global: false,
          data: {op: "release", section: section},
          success: function(strMessage) {
            if (strMessage != "")
              $("#pptx_release").html(strMessage+" Ready!");
            else
              $("#pptx_release").html("");
          }
      }); //end subajax
  }


  function CodeBefore(){
    $("button[id^='btn_download']").prop('disabled', true);
    $( "button[id^='btn_download']" ).css('background-color', 'rgb(9,112,74)');
    $(".loader-circle").css( "border-top","6px solid #0d9965");
      $(".loader-circle").css( "display","block");
  }

  function CodeStop(){
    $(".loader-circle").css( "display","none");
    $("button[id^='btn_download']").prop('disabled', false);
    $( "button[id^='btn_download']" ).css('background-color', 'rgb(13,153,101)');
  }

  function Documentupdate(section){
      showLastRun(section);
      showrelease(section);
      docID = setTimeout(Documentupdate, 30000, section); 

  }

</script>
<?php 
include_once "views/footer.view.php";
 ?>