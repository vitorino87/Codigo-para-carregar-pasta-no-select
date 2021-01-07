<!DOCTYPE html>
<html>
  <head>
    <title>Load File in the iframe</title>
	<link rel="stylesheet" href="layout.css"></link>
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
		<span>Page: <span id="page_num"></span> / <span id="page_count"></span></span>
		<canvas id="the-canvas"></canvas>
	</div>
	<script src='angular.min.js'></script>
	<script src='usePDFjs.js'></script>
	
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
			
		var pdfjsLib = window['pdfjs-dist/build/pdf'];
		pdfjsLib.GlobalWorkerOptions.workerSrc = 'pdfjs-2.5.207-dist/build/pdf.worker.js';
		var pdfDoc = null,
			pageNum =1,
			pageRendering=false,
			pageNumPending=null,
			scale=0.8,
			canvas = document.getElementById('the-canvas'),
			ctx=canvas.getContext('2d');
			
		var url = '';
			
		function abrirPDF(){
			var b = document.getElementById('frame');
			var h = document.getElementById('box').value;
			var c = document.getElementById('BotoesNavagacao');
			var x = "<?php echo $_SERVER['REQUEST_URI'] ?>"+h;
			x = x.replace("string:","");
			url = x;

			if(h.includes(".pdf")){
				b.style.display = "none";
				//document.getElementById("BotoesNavagacao").style.display = "block";//c.style.display = "block";
				openFilePDF();
			}else{
				b.style.display = "block";
				//document.getElementById("BotoesNavagacao").style.display = "none";
				//var x = "<?php echo $_SERVER['REQUEST_URI'] ?>"+h;
				//x = x.replace("string:","");
				b.src = x;
			}
		}
		
		function IrParaPag(pag,file){
			
				
		}
		
		function renderPage(num){
			pageRendering = true;
			pdfDoc.getPage(num).then(function(page){
				var viewport = page.getViewport({scale: scale});
				canvas.height = viewport.height;
				canvas.width = viewport.width;
				
				var renderContext = {
					canvasContext:ctx,
					viewport:viewport
				};
				
				var renderTask = page.render(renderContext);
				
				renderTask.promise.then(function(){
					pageRendering = false;
					if(pageNumPending!==null){
						renderPage(pageNumPending);
						pageNumPending = null;
					}
				});
			});
			document.getElementById('page_num').textContext = num;
		}
		
		function queueRenderPage(num){
			if(pageRendering){
				pageNumPending=num;
			}else{
				renderPage(num);
			}
		}
		
		function onPrevPage(){
			if(pageNum <=1){
				return;
			}
			pageNum--;
			queueRenderPage(pageNum);
		}
		
		document.getElementById('prev').addEventListener('click',onPrevPage);
		
		function onNextPage(){
			if(pageNum>=pdfDoc.numPages){
				return;
			}
			pageNum++;
			queueRenderPage(pageNum);
		}
		document.getElementById('next').addEventListener('click',onNextPage);
		
		function openFilePDF(){
			pdfjsLib.getDocument(url).promise.then(function(pdfDoc_){
				pdfDoc = pdfDoc_;
				document.getElementById('page_count').textContext=pdfDoc.numPages;
				renderPage(pageNum);
			});
		}
	</script>
  </body>
</html>