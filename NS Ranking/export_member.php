<?php
    include('get_data.php');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="invicta_family-'.date("Y-m-d-h-i-s").'.csv";');
    $output = fopen("php://output", "w");
    
    $clan_name = $_POST['name'];

    if($clan_name == "APOCALYPSE"){
        $member = getApoCrew();
        fputcsv($output, array('Rank', 'Name', 'Level', 'Reputation'));
        foreach($member as $mem){
            fputcsv($output, array($mem['Rank'], ConvertToUTF8($mem['Name']),$mem['Level'], $mem['Damage']));
        }
        fclose($output);
    }

    if($clan_name == "Invicta Family")
    {
        $member = getIFClan();
        fputcsv($output, array('Rank', 'Name', 'Level', 'Reputation'));
        foreach($member as $mem){
            fputcsv($output, array($mem['Rank'], ConvertToUTF8($mem['Name']),$mem['Level'], $mem['Reputation']));
        }
        fclose($output);
    }

    if($clan_name == "Kamikaze Warrior"){
        $member = getKWCrew();
        fputcsv($output, array('Rank', 'Name', 'Level', 'Reputation'));
        foreach($member as $mem){
            fputcsv($output, array($mem['Rank'], ConvertToUTF8($mem['Name']),$mem['Level'], $mem['Damage']));
        }
        fclose($output);
    }

    if($clan_name == "Brother Sennin"){
        $member = getBSCrew();
        fputcsv($output, array('Rank', 'Name', 'Level', 'Reputation'));
        foreach($member as $mem){
            fputcsv($output, array($mem['Rank'], ConvertToUTF8($mem['Name']),$mem['Level'], $mem['Damage']));
        }
        fclose($output);
    }
    
    
    

    function ConvertToUTF8($text){

        $encoding = mb_detect_encoding($text, mb_detect_order(), false);
    
        if($encoding == "UTF-8")
        {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');    
        }
    
    
        $out = iconv(mb_detect_encoding($text, mb_detect_order(), false), "UTF-8//IGNORE", $text);
    
    
        return $out;
    }
?>