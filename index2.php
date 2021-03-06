<!DOCTYPE html>
<html>
  <head>
    <title>Load File in the iframe</title>
	<link rel="stylesheet" href="layout.css"></link>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <h1>Selecione o arquivo que deseja visualizar</h1>
	<div ng-app="myApp" ng-controller="myCtrl">
		<select id='box' onchange="abrirPDF()" ng-model="selectedName" ng-options="x for x in names">
		</select>
	</div>
	<div id='demo'></div>
    <iframe id='frame' width="100%" height="500px">
    </iframe>
	<div id='BotoesNavegacao'>
		<button id="prev">Previous</button>
		<button id="next">Next</button>
		&nbsp;&nbsp;
		<span>Page: <span id="page_num"></span> / <span id="page_count"></span>
		&nbsp;&nbsp;
		<span>Ir para: <input type="text" id="page" style="width:50px;"></span>		
		<button id="buscar" style="width:100px;">Ir</button>
		</span>
		<br />
		<canvas id="the-canvas" width="1000px" height="500px"></canvas>
	</div>
	<script src='angular.min.js'></script>
	<script src='usePDFjs.js'></script>
	<script src='pdfjs-2.5.207-dist/build/pdf.js'></script>
	
	<script>

		var files = new Array();				
		<?php $g=0;?>
		<?php $a = scandir(".");?>
		<?php while($g<count($a)):?>
		files[<?php echo $g;?>]='<?php echo $a[$g];?>';
		<?php $g++;
		endwhile;?>		
		
		<?php //retornar todos os files 
			function find_all_files($dir)
			{
				$root = scandir($dir);
				foreach($root as $value)
				{
					if($value === '.' || $value === '..') {continue;}
					if(is_file("$dir/$value")) {$result[]="$dir/$value";continue;}
						foreach(find_all_files("$dir/$value") as $value)
						{
							$result[]=$value;
						}
				}
			return $result;
			}
		?>
		
		var app = angular.module('myApp',[]);
		app.controller('myCtrl',function($scope){
			$scope.names = files;
			});
						
		var url = '';
			
		function abrirPDF(){
			var b = document.getElementById('frame');
			var h = document.getElementById('box').value;
			var c = document.getElementById('BotoesNavagacao');
			var x = "<?php echo $_SERVER['REQUEST_URI'] ?>"+h;
			x = x.replace("string:","");
			x = x.replace("index2.php","");
			url = x;

			if(h.includes(".pdf")){
				b.style.display = "none";
				//document.getElementById("BotoesNavagacao").style.display = "block";//c.style.display = "block";
				carregarPDF(x,1);
			}else{
				b.style.display = "block";
				//document.getElementById("BotoesNavagacao").style.display = "none";
				//var x = "<?php echo $_SERVER['REQUEST_URI'] ?>"+h;
				//x = x.replace("string:","");
				b.src = x;
			}
		}
		
		// Get the input field
var input = document.getElementById("page");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
	var k = Number(input.value);
    carregarPDF(url,k);
  }
});

function buscarPage(){
	var input = document.getElementById("page");
	var k = Number(input.value);
    //carregarPDF(url,k);
	renderPage(k);
}
		
	
	</script>
  </body>
</html>