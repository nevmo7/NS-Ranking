<?php
	include('simple_html_dom.php');
	$href = "https://www.ninjasaga.com/game-info/all_clan.php";
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
	
	if(isset($_COOKIE['clanName'])){
		$saved_clan_name = $_COOKIE['clanName'];
	}else{
		$saved_clan_name = "Invicta Family";
	}

	$html = str_get_html($htmlStr);
	$clan=array();
	$point=$html->find("table tr table td table td.e_font-text01",0)->plaintext;
	var_dump( $point);
	$i = 0;
	$j=0;



	foreach ($point->find('tr') as $k) {
		if ($i>=2 && $i<27) {
			$clan[$j]['Rank']=$k->find('td',0)->plaintext;
			$clan[$j]['Name']=$k->find('td',1)->plaintext;
			$clan[$j]['Clan Master']=$k->find('td',2)->plaintext;
			$clan[$j]['Member No']=$k->find('td',3)->plaintext;
			$clan[$j]['Reputation']=$k->find('td',4)->plaintext;
			if ($j<1) {
				$clan[$j]['Gap']="Champ";
				$clan[$j]['Gap Champ']="Champ";
			}else{
				$clan[$j]['Gap']=$clan[$j-1]['Reputation']-$clan[$j]['Reputation'];
				$clan[$j]['Gap Champ']=$clan[0]['Reputation']-$clan[$j]['Reputation'];
			}
			$j++;
		}
		$i++;
	}
	
	echo build_table($clan, $saved_clan_name);


	function build_table($array,$saved_clan_name){
	    // start table
	    $html = '<table class="table table-striped table-sm">';
	    // header row
	    $html .= '<thead><tr>';
            $html .= '<th>Rank</th>';
            $html .= '<th>Name</th>';
            $html .= '<th>Clan Master</th>';
            $html .= '<th>Member No</th>';
            $html .= '<th>Reputation</th>';
            $html .= '<th>Gap</th>';
	    $html .= '</tr></thead><tbody class="table-condensed">';

	    // data rows
	    foreach( $array as $key=>$value){
	    	if ($value['Name'] == $saved_clan_name) {
	    		$html .= '<tr class="table-primary">';
		            $html .= '<td class="align-middle">' . $value['Rank'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Name'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Clan Master'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Member No'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Reputation'] . '</td>';
		        	$html .= '<td class="align-middle"><span data-toggle="tooltip" title="Gap to rank up">' . $value['Gap'].'</span><br><span class="badge badge-secondary" data-toggle="tooltip" title="Gap to champ">'. $value['Gap Champ'] . '</span></td>';
		        $html .= '</tr>';
	    	}else{
	    		$html .= '<tr>';
		         	$html .= '<td class="align-middle">' . $value['Rank'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Name'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Clan Master'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Member No'] . '</td>';
		        	$html .= '<td class="align-middle">' . $value['Reputation'] . '</td>';
		        	$html .= '<td class="align-middle"><span data-toggle="tooltip" title="Gap to rank up">' . $value['Gap'].'</span><br><span class="badge badge-secondary" data-toggle="tooltip" title="Gap to champ">'. $value['Gap Champ'] . '</span></td>';
		        $html .= '</tr>';
	    	}
	    }

	    // finish table and return it

	    $html .= '</tbody></table>';
	    return $html;
	}
