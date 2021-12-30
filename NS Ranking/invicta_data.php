<?php
    include('simple_html_dom.php');
    if(isset($_POST['name'])){
        $clcrName = htmlentities($_POST['name']);
    }else{
        $clcrName = 'APOCALYPSE';
    }

    if($clcrName == 'APOCALYPSE'){
        $htmlStr = runCurl("https://www.ninjasaga.com/game-info/crew.php?page=0&crew_id=2321");
    }
    elseif($clcrName == 'Kamikaze Warrior'){
        $htmlStr = runCurl("https://www.ninjasaga.com/game-info/crew.php?page=0&crew_id=2927");
    }
    elseif($clcrName == 'Brother Sennin'){
        $htmlStr = runCurl("https://www.ninjasaga.com/game-info/crew.php?page=0&crew_id=2406");
    }
    elseif($clcrName == 'Invicta Family'){
        $htmlStr = runCurl("https://www.ninjasaga.com/game-info/clan.php?page=0&clan_id=84625");
    }

    $html = str_get_html($htmlStr);
    $a=$html->find('table tr table td table td.e_font-text01 div[id=page_no_1]',0)->last_child()->href;
    $lastPageNo = (int)$a[14];
    $lastLinkStr = runCurl("https://www.ninjasaga.com/game-info/$a");
    $lastLinkHtml = str_get_html($lastLinkStr);
    $count = $lastLinkHtml->find('table tr table td table td.e_font-text01',0);
    $noCount = 0;
    for ($i=0; $i <28 ; $i++) { 
        if ($count->find('tr[style="background-color:#EBD9CE"], tr[style="background-color:#F3E9E2"]',$i) !=null) {
            $noCount++;
        }else{
            break;
        }
    }
    $lastMembers = $noCount-1;
    $totalMembers = ($lastPageNo*25)+$lastMembers;
    $member = array();
    $j=0;
    //get clan members
    if($clcrName == 'APOCALYPSE'){
        
        for ($no=0; $no <= $lastPageNo; $no++) {
            $htmlStr = runCurl("https://www.ninjasaga.com/game-info/crew.php?page=$no&crew_id=2321",0);
            $get_member_html = str_get_html($htmlStr);
            $h=$get_member_html->find('table tr table td table td.e_font-text01 ',0);
            $i=0;
    
            foreach ($h->find('tr') as $k) {
                $i++;
                if($i>2 && $i<28){
                    
                    if($k->find('td[!colspan]',0)!=null)
                    $member[$j]['Name']=$k->find('td',0)->plaintext;
    
                    if($k->find('td[!colspan]',1)!=null)
                    $member[$j]['Level']=$k->find('td[!colspan]',1)->plaintext;
                    
                    if($k->find('td',2)!=null)
                    $member[$j]['Reputation']=$k->find('td',2)->plaintext;
    
                    if ($j<$totalMembers-1) {
                        $j++;
                    }   
                }
            }
        }
        $member = sort_array($member, 'Reputation');
        for ($i=0; $i < $totalMembers ; $i++) {
            $member[$i]['Rank']=$i+1;
        }
    
        echo build_table($member);

    }
    elseif($clcrName == 'Kamikaze Warrior'){

        for ($no=0; $no <= $lastPageNo; $no++) {
            $htmlStr = runCurl("https://www.ninjasaga.com/game-info/crew.php?page=$no&crew_id=2927",0);
            $get_member_html = str_get_html($htmlStr);
            $h=$get_member_html->find('table tr table td table td.e_font-text01 ',0);
            $i=0;
    
            foreach ($h->find('tr') as $k) {
                $i++;
                if($i>2 && $i<28){
                    
                    if($k->find('td[!colspan]',0)!=null)
                    $member[$j]['Name']=$k->find('td',0)->plaintext;
    
                    if($k->find('td[!colspan]',1)!=null)
                    $member[$j]['Level']=$k->find('td[!colspan]',1)->plaintext;
                    
                    if($k->find('td',2)!=null)
                    $member[$j]['Reputation']=$k->find('td',2)->plaintext;
    
                    if ($j<$totalMembers-1) {
                        $j++;
                    }   
                }
            }
        }
        $member = sort_array($member, 'Reputation');
        for ($i=0; $i < $totalMembers ; $i++) {
            $member[$i]['Rank']=$i+1;
        }
    
        echo build_table($member);

    }
    elseif($clcrName == 'Brother Sennin'){

        for ($no=0; $no <= $lastPageNo; $no++) {
            $htmlStr = runCurl("https://www.ninjasaga.com/game-info/crew.php?page=$no&crew_id=2406",0);
            $get_member_html = str_get_html($htmlStr);
            $h=$get_member_html->find('table tr table td table td.e_font-text01 ',0);
            $i=0;
    
            foreach ($h->find('tr') as $k) {
                $i++;
                if($i>2 && $i<28){
                    
                    if($k->find('td[!colspan]',0)!=null)
                    $member[$j]['Name']=$k->find('td',0)->plaintext;
    
                    if($k->find('td[!colspan]',1)!=null)
                    $member[$j]['Level']=$k->find('td[!colspan]',1)->plaintext;
                    
                    if($k->find('td',2)!=null)
                    $member[$j]['Reputation']=$k->find('td',2)->plaintext;
    
                    if ($j<$totalMembers-1) {
                        $j++;
                    }   
                }
            }
        }
        $member = sort_array($member, 'Reputation');
        for ($i=0; $i < $totalMembers ; $i++) {
            $member[$i]['Rank']=$i+1;
        }
    
        echo build_table($member);

    }
    elseif($clcrName == 'Invicta Family'){
        
        for ($no=0; $no <= $lastPageNo; $no++) {
            $htmlStr = runCurl("https://www.ninjasaga.com/game-info/clan.php?page=$no&clan_id=84625",0);
            $get_member_html = str_get_html($htmlStr);
            $h=$get_member_html->find('table tr table td table td.e_font-text01 ',0);
            $i=0;
    
            foreach ($h->find('tr') as $k) {
                $i++;
                if($i>2 && $i<28){
                    
                    if($k->find('td[!colspan]',0)!=null)
                    $member[$j]['Name']=$k->find('td',0)->plaintext;
    
                    if($k->find('td[!colspan]',1)!=null)
                    $member[$j]['Level']=$k->find('td[!colspan]',1)->plaintext;
                    
                    if($k->find('td',2)!=null)
                    $member[$j]['Reputation']=$k->find('td',2)->plaintext;
    
                    if ($j<$totalMembers-1) {
                        $j++;
                    }   
                }
            }
        }
        $member = sort_array($member, 'Reputation');
        for ($i=0; $i < $totalMembers ; $i++) {
            $member[$i]['Rank']=$i+1;
        }
    
        echo build_table($member);

    }


    function build_table($array){
        // start table
        $html = '<table class="table table-dark table-bordered">';
        // header row
        $html .= '<tr>';
            $html .= '<th>Rank</th>';
            $html .= '<th>Name</th>';
            $html .= '<th>Level</th>';
            $html .= '<th>Points</th>';
        $html .= '</tr><tbody id="myTable">';

        // data rows
        foreach( $array as $key){
            $html .= '<tr>';
                $html .= '<td>' . $key['Rank'] . '</td>';
                $html .= '<td>' . $key['Name'] . '</td>';
                $html .= '<td>' . $key['Level'] . '</td>';
                $html .= '<td>' . $key['Reputation'] . '</td>';
            $html .= '</tr>';
        }

        // finish table and return it

        $html .= '</tbody></table>';
        return $html;
    }

    function runCurl($href)
    {
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

        return $htmlStr;
    }

    //sort array
    function sort_array($members_array, $sortBy){
        $totalNumber = count($members_array);
        $temp = array();
        for ($i=0; $i < $totalNumber; $i++) { 
            for ($j=$i+1; $j < $totalNumber; $j++) { 
                if ($members_array[$i][$sortBy] < $members_array[$j][$sortBy]) {
                    $temp = $members_array[$i];
                    $members_array[$i] = $members_array[$j];
                    $members_array[$j] = $temp;
                }
            }
        }
        return $members_array;
    }
?>