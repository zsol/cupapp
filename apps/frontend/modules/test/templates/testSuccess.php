<h1>
    Ó jééééééééééééééééé!
</h1>

<?php
    $repData = $sf_data->getRaw('repData');
    //var_dump($repData);
?>

<?php

                                echo sprintf("Map name: %s, Game length: %s<br />\n",$repData->getMapName(),$repData->getFormattedGameLength());
                                echo sprintf("Team size: %s, Game speed: %s<br />\n",$repData->getTeamSize(), $repData->getGameSpeedText());


echo "<table border=\"1\"><tr><th>Player name</th><th>Race</th><th>Color</th><th>Team</th><th>Average APM<br />(experimental)</th><th>Winner?</th></tr>\n";
                                foreach($players as $value) {
                                        $wincolor = ($value['won'] == 1)?0x00FF00:0xFF0000;
                                        echo sprintf("<tr><td>%s</td><td>%s</td><td><font color=\"#%s\">%s</font></td><td>%s</td><td style=\"text-align: center\">%d</td><td style=\"background-color: #%06X; text-align: center\">%d</td></tr>\n",
                                                                        $value['sName'],
                                                                        $value['race'],
                                                                        $value['color'],
                                                                        $value['sColor'],
                                                                        ($value['party'] > 0)?"Team ".$value['party']:"-",
                                                                        ($value['party'] > 0)?(round($value['apmtotal'] / ($repData->getGameLength() / 60))):0,
                                                                        ((isset($value['won']))?$wincolor:0xFFFFFF),
                                                                        (isset($value['won']))?$value['won']:(($value['party'] > 0)?"Unknown":"-")
                                                                );
                                }
                                echo "</table><br />";



if (count($messages) > 0) {
        echo "<br/><br/><b>Messages:</b><br /><table border=\"1\"><tr><th>Time</th><th>Player</th><th>Target</th><th>Message</th></tr>\n";
        foreach ($messages as $val)
                echo sprintf("<tr><td>%d sec</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",$val['time'],
                                          $val['name'], ($val['target'] == 2)?"Alliance":"All",$val['message']);
        echo "</table><br />\n";
}

$t = $repData->getEvents();

        echo "<table border=\"1\"><tr><th>Timecode</th>\n";
        $pNum = count($players);
        foreach ($players as $value) {
          if ($value['party'] > 0)
                echo sprintf("<th>%s (%s)</th>",$value['sName'],$value['race']);
        }
        echo "</tr>\n";
        foreach ($t as $value) {
                echo sprintf("<tr><td>%d sec</td>",$value['t'] / 16);
                foreach ($players as $value2) {
                        if ($value2['party'] == 0) continue;
                        if ($value['p'] == $value2['id'])
                                echo sprintf("<td>%s</td>",$repData->getAbilityString($value['a']));
                        else
                                echo "<td></td>";
                }
                echo "</tr>\n";
        }
        echo "</table>";


?>