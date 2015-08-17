<?php 

	$cities = array ("A0"=>"MontBlanc, France", "A1"=>"Paris, France","A2"=>"North Wales, UK", "A3"=>"Amsterdam, Netherlands", "A4"=>"Crete, Greece");
		
	$price = array(
				array( 1   ,0.5, 2  ,  0.3, 4  ),
				array( 2   ,1  , 2  ,  2  , 0.5),
				array( 0.5 ,0.5, 1  ,  0.3, 3  ),
				array( 3   ,0.5, 3  ,  1  , 3  ),
				array( 0.25,2  , 0.3,0.3  , 1  ));
	
	$distance = array(
					array( 1 ,0.25,0.25,0.5,0.5),
					array( 4 ,1   ,2   ,2  ,0.5),
					array( 4 ,0.5 ,1   ,0.5,0.5),
					array( 2 ,0.5 ,2   ,1  ,2  ),
					array( 2 ,2   ,2   ,0.5,1  ));
	
	$entertainment = array(
						array( 1 ,0.2 ,0.3,0.25,0.5 ),
						array( 5 ,1   ,4  ,0.5 ,0.5 ),
						array( 3 ,0.25,1  ,0.25,0.3 ),
						array( 4 ,2   ,4  ,1   ,0.5 ),
						array( 2 ,2   ,3  ,2   ,1   ));						
	
/***function to compute the sum of matrix columns, from the initial matrix+***/
	function sum($a) {	
		$sums = array();
		$nrows = count($a);        
		$ncols = count($a[0]); 	
		// compute sum of matrix columns  
		for ( $j=0; $j < $ncols; $j++ ) {
			$sums[$j] = 0.0;
			for( $i=0; $i < $nrows; $i++ ) {
				$sums[$j] += $a[$i][$j];
			}
		}
	return $sums;
	}
  
	$sum_p = sum($price);
	// print_r ($sum_p);
	$sum_d = sum($distance);
	// print_r ($sum_d);
	$sum_e = sum($entertainment);
	// print_r ($sum_e);
	 
/***********function that computes the normalized values of initial matrix values*****************/
	
	function norm($a,$v){		
		$nrows = count($a);        
		$ncols = count($a[0]); 
		for ( $i=0; $i<$nrows; $i++ ) {
			for( $j=0; $j<$ncols; $j++ ){
				if ($v[$i] != 0) 
				$a_norm[$j][$i] = $a[$j][$i]/$v[$i];
			}
		}
	return $a_norm;
	}
	
	$norm_p = norm($price,$sum_p);
	// print_r ($norm_p);
	$norm_d = norm($price,$sum_d);
	// print_r ($norm_d);
	$norm_e = norm($price,$sum_e);
	// print_r ($norm_e);

/**********Makes the sum of the rows***********************/
	function sumfunc($a) {
		$sums = array();
		$nrows = count($a);
			for ( $i=0; $i < $nrows; $i++ ) {
				$sums[$i] += array_sum($a[$i]);
				$sums[$i] =  $sums[$i]/$nrows;
			}
	return $sums;  
	}	
									  
	$sumf_p = sumfunc($norm_p);
	//print_r ($sumf_p);
	$sumf_d = sumfunc($norm_d);
	//print_r ($sumf_d);
	$sumf_e = sumfunc($norm_e);
	//print_r ($sumf_e);

 /***********Matrix for three (price, distance, entertainment) criteria***********/
	$critPDE = array(
				array( 1  , 3  ,4 ),
				array( 1/3, 1  ,3 ),
				array( 1/4, 1/3,1 ));	
				
				
	$sum_crit = sum($critPDE);
	  //print_r ($sum_crit);  
	$norm_crt= norm($critPDE,$sum_crit);
	  //print_r ($norm_crt);
	$sumf_crtPDE= sumfunc($norm_crt);
	 // print_r($sumf_crtPDE);
	
/**********Matrix for two criteria (price and distance)***********/	
	$critPD = array(
				array( 1  , 3 , 0 ),
				array( 1/3, 1 , 0 ),
				array( 0  , 0 , 0 ));	
			
	$sum_crit = sum($critPD);
	//print_r ($sum_crit);
	$norm_crt= norm($critPD,$sum_crit);
	// print_r ($norm_crt);
	$sumf_crtPD= sumfunc($norm_crt);
	// print_r($sumf_crtPD);

 /**********Matrix for two criteria (price and entertainment)***********/
	$critPE = array(
				array( 1  ,0 , 4 ),
				array( 0 , 0 , 0 ),
				array( 1/4,0 , 1 ));	
							
	$sum_crit = sum($critPE);
	// print_r ($sum_crit);
	$norm_crt = norm($critPE,$sum_crit);
	// print_r ($norm_crt);
	$sumf_crtPE = sumfunc($norm_crt);
	// print_r($sumf_crtPE);
  
 /**********Matrix for two criteria (distance and entertainment)***********/
	$critDE = array(
				array( 0 , 0 , 0 ),
				array( 0 , 1 , 3 ),
				array( 0, 1/3, 1 ));	

	$sum_crit = sum($critDE);
	// print_r ($sum_crit);
	$norm_crt = norm($critDE,$sum_crit);
	// print_r ($norm_crt);
	$sumf_crtDE = sumfunc($norm_crt);
	// print_r($sumf_crtDE);
			
/***************Compute decision matrix from alternative vectors**************************************/			
	$result = array();
	for ($l=0; $l<3; $l++){			
		if ( $l==0 ) {
			$v = $sumf_p;						
		} elseif ( $l==1 ) {
			$v = $sumf_d;
		}  elseif ($l == 2) {
			$v = $sumf_e;
		}
															
		$i=0;
		foreach ($v as $key => $value) {
							
			$result[$i][$l] = $value;
			$i++;
		}						
	}			
	//print_r ($result);

/**********************checking for check-box options and calculating decision criteria***************************************/
	if(isset($_POST['price'])&&($_POST['distance'])&&($_POST['entertainment'])){
		$crit_vector = $sumf_crtPDE;
	} elseif(isset($_POST['price'])&&($_POST['distance'])) {
		$crit_vector = $sumf_crtPD;
	} elseif (isset($_POST['price'])&&($_POST['entertainment'])) {
		$crit_vector = $sumf_crtPE;
	}elseif (isset($_POST['distance'])&&($_POST['entertainment'])) {
		$crit_vector = $sumf_crtDE;
	} else {echo "Choose at least 2"; $error=1; } 
	
	for($i=0;$i<5;$i++){
		for($j=0;$j<3;$j++){
			$decision[$i][$j] = $result[$i][$j]* $crit_vector[$j];
		}
	}	
	//print_r($decision);

	for($i=0;$i<5;$i++){
		for($j=0;$j<3;$j++){
			$final['A'.$i] = $final['A'.$i] + $decision[$i][$j];
		}	
	}		
	
	//print_r($final);
	if (!$error) {
		arsort($final);	
		foreach ($final as $key => $value)
			echo $cities[$key]."<br>";
	}
											
?>