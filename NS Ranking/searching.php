<?php
    include('simple_html_dom.php');

    if(isset($_POST['clan'])){
        $clanId = $_POST['clan'];
    }else{
        $clanId = "-";
    } 

    $htmlStr = runCurl("https://www.ninjasaga.com/game-info/clan.php?page=0&clan_id=".$clanId);
    $html = str_get_html($htmlStr);
    $clanName = $html->find('table tr table td table td.e_font-text01 table tr td', 0)->plaintext;
    if($clanName != "" && $clanName != "Clan Ranking"){
        setcookie("clanName", $clanName, time() + 864000000);
        setcookie("clanId", $clanId, time() + 864000000);
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

        for ($no=0; $no <= $lastPageNo; $no++) {
            $htmlStr = runCurl("https://www.ninjasaga.com/game-info/clan.php?page=$no&clan_id=".$clanId,0);
            $get_member_html = str_get_html($htmlStr);
            $h=$get_member_html->find('table tr table td table td.e_font-text01 ',0);
            $i=0;

            foreach ($h->find('tr') as $k) {
                $i++;
                if($i>2 && $i<28){
                    
                    if($k->find('td[!colspan]',0)!=null)
                    $member[$j]['Name']=ConvertToUTF8($k->find('td',0)->plaintext);

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
        $clanInfo = array("name"=>$clanName,
                        "id"=>$clanId,
                        "members"=>$member);

        // echo "<pre>";
        // var_dump($clanInfo);
        // echo "</pre>";
        echo json_encode($clanInfo);
    }else{
        $clanInfo = array("name"=>null,
                        "id"=>null,
                        "members"=>null);

        // echo "<pre>";
        // var_dump($clanInfo);
        // echo "</pre>";
        echo json_encode($clanInfo);
    }

    function build_table($array, $name){
        // start table
        $html = '<div class="clan_container">
        <div class="clearfix clan_title"><h2 class="float-left">
        '.$name.'
        </h2><div class="float-right btn-group btn-group">
            <a href="#searchClanBox" class="btn btn-info" id="change" type="button" data-toggle="collapse">Change clan</a>
            <a href="#searchMember" class="btn btn-info" id="change" type="button" data-toggle="collapse">Search Member</a>
            <button class="btn btn-info" id="refresh" type="button">Refresh</a>  
        </div>
        </div>
        <div id="searchClanBox" class="collapse">
        <div class="input-group mb-3"><input type="text" id="clan_id" class="form-control" placeholder="Search clan"><div class="input-group-append"><button class="btn btn-success" id="go_btn" type="submit">Go</button></div></div>
        </div>
        
        <div class="clan_table">
        <table class="table table-striped">';
        // header row
        $html .= '<tr>';
            $html .= '<th>Rank</th>';
            $html .= '<th>Name</th>';
            $html .= '<th>Level</th>';
            $html .= '<th>Reputation</th>';
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

        $html .= '</tbody></table></div></div>';
        return $html;
    }

    function ConvertToUTF8($text){

        $encoding = mb_detect_encoding($text, mb_detect_order(), false);
    
        if($encoding == "UTF-8")
        {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');    
        88    
    
        $out = iconv(mb_detect_encoding($text, mb_detect_order(), false), "UTF-8//IGNORE", $text);
    
    
        return $out;
    }

    //sort array
    function sort_array($members_array, $sortBy){
        $totalNumber = count($members_array);
        $temp = array();
        for ($i=0; $i < $totalNumber-1; $i++) { 
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
