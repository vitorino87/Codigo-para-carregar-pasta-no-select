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
	<!--<link rel="stylesheet" href="pdfjs-2.5.207-dist/web/viewer.css">-->
	<script src="pdfjs-2.5.207-dist/build/pdf.js"></script>
	<script src="pdfjs-2.5.207-dist/web/viewer.js"></script>
  </head>
  <body>
    <h1>Selecione o arquivo que deseja visualizar</h1>
	<div ng-app="myApp" ng-controller="myCtrl">
		<select id='box' onchange="abrirFile()" ng-model="selectedName" ng-options="x for x in names">
		</select>
	</div>
	<div id='demo'></div>
    <iframe id='frame' name="frame" width="100%" height="500px" >
    </iframe>
	<script src='angular/angular.min.js'></script>
	<script src='pdfjs-2.5.207-dist/build/pdf.js'></script>
	
	<script>

		var files = new Array();	
		<?php 
		error_reporting(0);
			try{
				$k = $_GET['path']; 
				$w = ".";
				if($k!=''){
					$w = $k;
				}
			}
			catch(Exception $e){}
		?>
		<?php $g=0;?>
		<?php $a = scandir($w);?>
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
			
		function abrirFile(){
			var b = document.getElementById('frame');
			var h = document.getElementById('box').value;
			var c = document.getElementById('BotoesNavagacao');
			var x = "<?php echo $_SERVER['REQUEST_URI'] ?>"+h;						
			var u = window.location.pathname;			
			u = u.substring(0,u.lastIndexOf("/")+1);			
			alert(u);
			alert("teste: "+u+h);					
			if(x.includes("?path=")){				
				x = x.substring(x.indexOf("=")+1);
				url = x;
			}else{			
				url = h;
			}
			url = url.replace(/string:/g,"");
			alert("url: "+url);
			if(h.includes(".pdf")){
				u = u+url;
				u=u.replace("string:","");
				var a = document.createElement('a');
				x = "pdfjs-2.5.207-dist/web/viewer.html?file="+u;
				alert(x);
				if(x.includes("?path=")){
					x = x.replace("?path=","");
				}
				a.href = x;
				a.target="frame";
				a.click();				
			}else{
				try{
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function(){
						if(this.readyState==4 && this.status==200){
							var z = this.responseText;
							z = Boolean(z);
							alert(z);
							alert(url);
							if(z){
								reloadSelect(url);
							}else{
								alert(z);
								b.src = url;
							}
						}
					};					
					//u = u.substring(1,u.length);
					alert("u - try: "+url);
					//xhttp.open("GET","demoChek.php?path="+h,true);
					xhttp.open("GET","demoChek.php?path="+url,true);
					xhttp.send();					
				}catch(err){
					
				}								
			}
		}

		function reloadSelect(path){
			window.location.href = "index6.php?path="+path+"/";			
		}
	</script>
  </body>
</html>