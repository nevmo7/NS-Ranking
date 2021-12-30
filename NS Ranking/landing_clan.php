<?php
	include('simple_html_dom.php');
	$clorcr = $_POST['clcr'];
	if($clorcr == "clan")
		$href = "https://www.ninjasaga.com/game-info/all_clan.php";
	else
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
		if ($i>=2 && $i<12) {
			$clan[$j]['Name']=$k->find('td',1)->plaintext;
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

	echo build_table($clan);

	function build_table($array){
	    // start table
	    $html = '<div class="overflow-auto"><ul class="list-group list-group-flush">';

	    // data rows
	    foreach( $array as $key=>$value){
			$html .= '<li class="list-group-item py-2">' . $value['Name'] . '<br>
			<span class="badge badge-primary badge-pill">'. $value['Reputation'] .'</span></li>';
	    }

	    // finish table and return it

	    $html .= '</ul></div>';
	    return $html;
	}

?>
