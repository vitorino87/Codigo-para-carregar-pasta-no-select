<!DOCTYPE html>
<html>
  <head>
    <title>Load File in the iframe</title>
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
	
	<script src='angular.min.js'>
	</script>
	
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
			
		function abrirPDF(){
			var b = document.getElementById('frame');
			var h = document.getElementById('box').value;
			var x = "<?php echo $_SERVER['REQUEST_URI'] ?>"+h;
			x = x.replace("string:","");
			b.src = x;
		}
	</script>
  </body>
</html>