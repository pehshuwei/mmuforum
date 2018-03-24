<?php

$current = 0;
$target = 20;
$total = 0;

for($a=0; $a<20; $a++){

	for($b=0; $b<=20; $b++){

		for($c=0; $c<=20; $c++){

			for($d=0; $d<=20; $d++){

				$current = $a*1 + $b*5 + $c*10 + $d*20;

				if($current==$target){
					$total++;
				}

			}

		}

	}
	
}

echo $total;

?>
