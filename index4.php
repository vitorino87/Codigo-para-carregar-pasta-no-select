<!DOCTYPE html>
<html>
  <head>
    <title>Load File in the iframe</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="layout.css"></link>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="google" content="notranslate">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="viewer.css">
	<script src="pdfjs-2.5.207-dist/build/pdf.js"></script>
	<script src="pdfjs-2.5.207-dist/web/viewer.js"></script>
  </head>
  <body>
    <h1>Selecione o arquivo que deseja visualizar</h1>
	<div ng-app="myApp" ng-controller="myCtrl">
		<select id='box' onchange="abrirPDF()" ng-model="selectedName" ng-options="x for x in names">
		</select>
	</div>
	<div id='demo'></div>
    <iframe id='frame' src="pdfjs-2.5.207-dist/web/viewer.html?file=compressed.tracemonkey-pldi-09.pdf" width="100%" height="500px">
    </iframe>
	<script src='angular.min.js'></script>
	<!--<script src='usePDFjs.js'></script>-->
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
				//b.style.display = "none";
				var a = document.createElement('a');
				a.href = "pdfjs-2.5.207-dist/web/viewer.html?file=compressed.tracemonkey-pldi-09.pdf"
				//document.getElementById("BotoesNavagacao").style.display = "block";//c.style.display = "block";
				a.target="frame";
				a.click();
				//carregarPDF(x,1);
			}else{
				b.style.display = "block";
				//document.getElementById("BotoesNavagacao").style.display = "none";
				//var x = "<?php echo $_SERVER['REQUEST_URI'] ?>"+h;
				//x = x.replace("string:","");
				b.src = x;
			}
		}
</script>