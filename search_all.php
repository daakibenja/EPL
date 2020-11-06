<?php
include("commons/common.php")
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bacon.css">
</head>

<body>
    <div id="search">
        <?php include("commons/top.html") ?>

        <form action="http://localhost/ip/epl/search_all.php" method="get">
            <div id="direction">
                <a href="myepld.php">Home</a> >> <a href="#">Search All</a>
            </div>
            Season
            <input type="text" name="season" id="season" list="seasons" required>
             Team
            <input type="text" name="team" id="team" list="teams" required>
            
            <input type="submit" value="Search">
            Or <a href="http://localhost/ip/epl/search_specific.php?season=1819&team=Arsenal&choice=Wins&whichOne=Home"><input type="button" value="Search Specific"></a>
            </li>
        </form>
    </div>
    <div id="team-logo">
        <?php
        $imgae_src = "";

        /*Checking if nothing was entered or the user just entered white spaces 
        or less characters than expected and terminating immediately*/
        if(isset($_GET['team'])&&isset($_GET['season'])){
            if(strlen($_GET['team'])<3|| strlen($_GET['season']) != 4){

                include("commons/bottom.html");
                exit(1);
            }
                    
            $team = $_GET['team'];
        }
        else{
            include("commons/bottom.html");
            exit(1);
        }

        /*Selection of the teams logo from the where the file paths are stored 
        and assigning them to the variable $imgae_src
        If the team has no logo, the default epl logo is used*/
        $query_logo = "SELECT `Team`, `logo`, `id` FROM `logos` WHERE `Team`='$team'";
        $run_logo_query = mysqli_query($connection, $query_logo);
        $logo_result = mysqli_fetch_assoc($run_logo_query);
        if (isset($logo_result['logo'])) {
            $imgae_src = $logo_result['logo'];
        }
        if ($imgae_src == "") {
            $imgae_src = "images/epl.png";
        }

        /*Function to take a short form of a season and converts it into
        the full form suitable for displaying to the user ie 1112 
        is converted to 2011/2012*/
        function seasonfy($season){
            $final = '20' . $season[0] . $season[1] . '/20' . $season[2] . $season[3];
            return $final;
        }
        ?>
        <h2><?php echo $team . " F.C"; ?></h2>
        <img src="<?php echo $imgae_src ?>" alt="No logo yet" id="club" height="400px" width="400px">
    </div>
    <div id="matches">
        <h2><?php echo "Season: " . seasonfy($_GET['season']); ?></h2>
            <?php
            
            $val = 0;
            $season = $_GET['season'];
            $query_match = "SELECT `Date`, `HomeTeam`, `AwayTeam`, `FTHG`, `FTAG`, `FTR`, `HTHG` 
                            FROM `season_" . $season . "` WHERE `HomeTeam`='$team' OR `AwayTeam`='$team'";
            $run_match_query = mysqli_query($connection, $query_match);
            while ($match_result = mysqli_fetch_assoc($run_match_query)) {

                #Creating a table only if the team searchrd for exists
                if($val==0){
                   echo "<table>".
                        "<tr id='table-header'>".
                        "<td>Date</td>".
                        "<td>Home Team</td>".
                        "<td></td>".
                        "<td></td>".
                        "<td></td>".
                        "<td>Away Team</td>".
                        "<td>Full Time Result</td>".
                        "</tr>";        
                }

                #creating rows with alternating id attributes ie td0 and td1
                $id = $val % 2;
                echo "<tr id='td$id'>";
                echo "<td>" . $match_result['Date'] . "</td>";

                /*Creating anchors for the other teams for easy navigation*/
                if ($match_result['HomeTeam'] != $team) {
                    echo"<td>" . 
                        "<a href='search_all.php?season=$season&team=".$match_result['HomeTeam']."'>".$match_result['HomeTeam'] ."</a>".
                        "</td>";
                } else {
                    echo "<td>".$match_result['HomeTeam']."</td>";
                }
                echo "<td>" . $match_result['FTHG'] . "</td>";
                echo "<td>  -  </td>";
                echo "<td>" . $match_result['FTAG'] . "</td>";

                /*Creating anchors for the other teams for easy navigation*/
                if ($match_result['AwayTeam'] != $team) {
                    echo "<td>" . 
                        "<a href='search_all.php?season=$season&team=".$match_result['AwayTeam']."'>" . $match_result['AwayTeam']."</a>".
                        "</td>";
                } else {
                    echo "<td>" . $match_result['AwayTeam'] . "</td>";
                }

                echo "<td>" . $match_result['FTR'] . "</td>";
                $val++;
            }
            ?>
        </table>
        <?php

        #Verifying if the team was found, and if it wasn't an error Message is displayed
        if ($val==0) {
            echo "<div id='error'><h1 id='h1-error'>Sorry! No record of  " . $team . " for season ".
            seasonfy($GLOBALS['season'])." Was found".
            "</h1></div>";

        }
        ?>
    </div>
    <p id="save-me"></p>
    <?php include("commons/bottom.html"); ?>
</body>

</html>