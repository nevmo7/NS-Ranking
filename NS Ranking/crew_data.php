<?php
	include('simple_html_dom.php');
	$href = "https://www.ninjasaga.com/game-info/all_crew.php";
	$curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept:text/html'));
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $href);
    curl_setopt($curl, CURLOPT_REFERER, $href);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    $htmlStr = curl_exec($curl);
    curl_close($curl);

	$html = str_get_html($htmlStr);
	$clan=array();
	$point=$html->find("table tr table td table td.e_font-text01",0);
	$i = 0;
	$j=0;

	foreach ($point->find('tr') as $k) {
		if ($i>=2 && $i<27) {
			$clan[$j]['Rank']=$k->find('td',0)->plaintext;
			$clan[$j]['Name']=$k->find('td',1)->plaintext;
			$clan[$j]['Crew Master']=$k->find('td',2)->plaintext;
			$clan[$j]['Member No']=$k->find('td',3)->plaintext;
			$clan[$j]['Damage']=$k->find('td',4)->plaintext;
			if ($j<1) {
				$clan[$j]['Gap']="Champ";
				$clan[$j]['Gap Champ']="Champ";
			}else{
				$clan[$j]['Gap']=$clan[$j-1]['Damage']-$clan[$j]['Damage'];
				$clan[$j]['Gap Champ']=$clan[0]['Damage']-$clan[$j]['Damage'];
			}
			$j++;
		}
		$i++;
	}

	echo build_table($clan);

	function build_table($array){
	    // start table
	    $html = '<table class="table table-striped table-sm">';
	    // header row
	    $html .= '<thead><tr>';
            $html .= '<th>Rank</th>';
            $html .= '<th>Name</th>';
            $html .= '<th>Crew Master</th>';
            $html .= '<th>Member No</th>';
            $html .= '<th>Damage</th>';
            $html .= '<th>Gap</th>';
	    $html .= '</tr></thead><tbody class="table-condensed">';

	    // data rows
	    foreach( $array as $key=>$value){
	    	if ($value['Name'] == "Invicta Family") {
	    		$html .= '<tr class="table-primary">';
		            $html .= '<td class="align-middle">' . $value['Rank'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Name'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Crew Master'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Member No'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Damage'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Gap']."<br>".$value['Gap Champ'] . '</td>';
		        $html .= '</tr>';
	    	}else{
	    		$html .= '<tr>';
		         	$html .= '<td class="align-middle">' . $value['Rank'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Name'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Crew Master'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Member No'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Damage'] . '</td>';
		        	$html .= '<td class="align-middle"><span data-toggle="tooltip" title="Gap to rank up">' . $value['Gap'].'</span><br><span class="badge badge-secondary" data-toggle="tooltip" title="Gap to champ">'. $value['Gap Champ'] . '</span></td>';
		        $html .= '</tr>';
	    	}
	    }

	    // finish table and return it

	    $html .= '</tbody></table>';
	    return $html;
	}

?>
