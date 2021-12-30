<?php
    include('apo_crew.php');

    $apo_array = getApoCrew();
    $kw_array = getKWCrew();
    $bs_array = getBSCrew();

    $count=count($apo_array);
    $i=1;
    foreach($kw_array as $kw){
        $apo_array[$count+$i] = $kw;
        $i++;
    }
    
    $count=count($apo_array);
    $i=1;
    foreach($bs_array as $bs){
        $apo_array[$count+$i] = $bs;
        $i++;
    }

    $totalMembers = count($apo_array);
    $apo_array = sort_array($apo_array, 'Damage');
    for ($i=0; $i < $totalMembers ; $i++) {
        $apo_array[$i]['Rank']=$i+1;
    }

    echo "<pre>";
    var_dump($apo_array);

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
?>