<?php session_start();
require "config/conn.php";

$idcompany = $_POST['idcompany'];
$category = $_POST['category'];
$col_name = $_POST['col_name'];
$report = "600";
$tiempo_inicio = microtime_float();

//modificacion a partir de este punto 
 $arrcom = squery("select indexren, renc_descripcion, inf_renglones from cfg_reports_ren where info_r='$category'");
 $arrReport = oci_query("Select infs_renglon RENGLON, RENC_DESCRIPCION CONCEPTO, renf_valor1 MES, renf_valor2 ACUM, TO_CHAR(REPD_FECHAULTMOD,'MM-YYYY') FECHA FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia =".$idcompany." AND A.INFI_REPORTE=".$report." ORDER BY A.INFS_RENGLON", OCI_ASSOC);
 ?>
<div class="container">
<form id="dataER">
	 <table class="table table-sm table-striped table-responsive-sm">
	  <thead>
	    <tr>
	      <th>Conceptos</th>
	      <?php 

	      #NOTA hay que filtrar por division 
	    		echo "<th>".$col_name."</th>
	    				<th>INFOFIN</th>
	    				<th>TOTAL</th>";
	       ?>
	    </tr>
	  </thead>
	  <tbody>
	    <?php 
	    	$tbody = "";
	    	for ($i=0; $i < count($arrcom); $i++) { 
	    	$index = array_search($arrcom[$i]['inf_renglones'], array_column($arrReport, 'RENGLON'));
	    	$inf_value = ($index) ? number_format($arrReport[$index]['MES'] / 1000) : '-';
		    	echo "<tr>
	    		<td class='col-sm-4'>".$arrcom[$i]['renc_descripcion']."</td>";
		    		echo 
					"<td class='col-sm-4'>".
		    		"<input type='text' class='w-50 form-control form-control-sm' id='".$arrcom[$i]['inf_renglones']."'
		    		name='".$idcompany."[".$arrcom[$i]['indexren']."]'>".
		    		"</td>".
		    		"<td class='col-sm-2'>".$inf_value."</td>".
		    		"<td class='col-sm-2'>".$inf_value."</td>";
		    	echo "</tr>";
	    	}
	    	unset($r);

	    	$tiempo_fin = microtime_float();
			// echo "<br>Loading Time : " . number_format(($tiempo_fin - $tiempo_inicio),4) . " seg.";

	     ?>
	  </tbody>
	</table>
	<button type="submit" class="btn btn-primary float-right"> Save </button>
</form>
</div>

<script type="text/javascript">
    $("#dataER").submit(function(e) {
        e.preventDefault();
        var nMonth = $("#datebox [name=month]").val();
        var nYear = $("#datebox [name=year]").val();
        var rdtype = $("[name=rdtype]:checked").val();
	    var id = $("select[name=company]").val();
        $.ajax( {
            url: "op/er.php",
            method: "post",
            data: $("#dataER").serialize() + "&er=dataER&month="+nMonth+"&year="+nYear+"&category="+rdtype+"&idcompany="+id,
            success: function(data) {
            	console.log(nYear+nMonth);
            	$(".modal").modal("hide");
            	tableERdata();
               alert(data);
            }
        });

    });
    $( "input.w-50" ).keyup(function() {
    	var input_val = $(this).val();
    	var inf_val = $(this).closest( "td" ).next().text().replace(',', '');
		inf_val = (inf_val > 0) ? inf_val : 0;
    	var tot = (input_val) ? parseFloat(input_val) + parseFloat(inf_val) : inf_val;
    	$(this).closest( "td" ).next().next().text(FormatMoney(tot,0));
	});

</script>