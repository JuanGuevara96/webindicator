<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close" onclick="document.getElementById('myModal').style.display='none';">&times;</span>
      <!-- <p id="listafiles"></p> -->
    	<!-- <button id="btn_download" class="button">Download File <i class="fas fa-file-download"></i></button> -->
            <!-- agregar o no boton para previsualizar data -->
            <!--  <a class="button" href="./files/INMOBILIARIA/data/junio 2019.html" target="_blank" onclick="window.open(this.href,this.target,\'width=950,height=400,top=200,left=200,toolbar=no,location=no,directories=no,status=no,menubar=no\');return false;">Show Data</a> -->
      <div class="modal-content-download">
        <div style="display: flex;align-items: center;flex-direction: column;border: 2px solid green;border-radius: 6px;margin: 8px; width: 80%;">
          <p id="lastrun"></p>
          <button id="btn_download_data" class="button">Process Data <i class="fas fa-database"></i></button>
        </div>
        <div style="display: flex;align-items: center;flex-direction: column;">
          <p id="pptx_realese"></p>
      	   <button id="btn_download_app" class="button">Download Presentation <i class="fas fa-file-powerpoint"></i></button>
        </div>
          <div id="wait-download" class="loader-circle"></div>
      </div>
  </div>
</div>