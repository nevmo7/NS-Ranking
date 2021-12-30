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

	$html = str_get_html($htmlStr);
	$clan=array();
	$point=$html->find("table tr table td table td.e_font-text01",0);
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
	if(isset($_POST["export"]))
	{
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=clan'.date("Y-m-d-h-i-s").'.csv');
		$output = fopen("php://output", "w");
        fputcsv($output, array('Rank', 'Name', 'Clan Master', 'Member No', 'Reputation', 'Gap rank up', 'Gap champ'));
        for($i=0;$i<25;$i++){
            fputcsv($output, array($clan[$i]['Rank'],$clan[$i]['Name'],$clan[$i]['Clan Master'],
            $clan[$i]['Member No'],$clan[$i]['Reputation'],$clan[$i]['Gap'],$clan[$i]['Gap Champ']));
        }  
		
		fclose($output);  
	}
?>
