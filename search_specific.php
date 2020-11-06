<?php
include("commons/common.php");
include("commons/top.html");
?>

<link rel="stylesheet" href="css/bacon.css">
<div id="search">
    <form action="http://localhost/ip/epl/search_specific.php" method="get">
        <div id="direction">
            <a href="myepld.php">Home</a> >> <a href="http://localhost/ip/epl/search_all.php?season=1819&team=Liverpool">Search All</a>>> <a href="#">Search Specific</a>
        </div>
        Season
        <input type="text" name="season" id="season" list="seasons" required>

        Team
        <input type="text" name="team" id="team" list="teams" required>
        
        Filter
        <input type="text" name="choice" id="choice" list="choices" required>
        <input type="radio" name="whichOne" value="Home" checked>Home
        <input type="radio" name="whichOne" value="Away">Away
        <input type="radio" name="whichOne" value="Both">Both

        <input type="submit" value="Search">

        </li>
    </form>
</div>
<div id="team-logo">
    <?php
    $season = "";
    $imgae_src = "";
    $team = $_GET['team'];
    $start_table = "<table>" .
        "<tr id='table-header'>" .
        "<td>Date</td>" .
        "<td>Home Team</td>" .
        "<td></td>" .
        "<td></td>" .
        "<td></td>" .
        "<td>Away Team</td>" .
        "</tr>";
    $query_logo = "SELECT `Team`, `logo`, `id` FROM `logos` WHERE `Team`='$team'";
    $run_logo_query = mysqli_query($connection, $query_logo);
    $logo_result = mysqli_fetch_assoc($run_logo_query);
    if (isset($logo_result['logo'])) {
        $imgae_src = $logo_result['logo'];
    }
    if ($imgae_src == "") {
        $imgae_src = "images/epl.png";
    }
    function seasonfy($season)
    {
        $final = '20' . $season[0] . $season[1] . '/20' . $season[2] . $season[3];
        return $final;
    }
    ?>
    <h2><?php echo $team . " F.C"; ?></h2>
    <img src="<?php echo $imgae_src ?>" alt="No logo yet" id="club" height="400px" width="400px">
</div>
<div id="matches">
    <h2><?php echo $_GET['whichOne'] . " " . $_GET['choice'] . " for " . $_GET['team']; ?>
        <?php echo "Season: " . seasonfy($_GET['season']); ?></h2>

    <div id="filtered-results">
        <?php
        $val = 0;
        $team = $_GET['team'];
        $season = $_GET['season'];
        $filter = $_GET['choice'];
        $where = $_GET['whichOne'];
        $query = get_final_query($team, $season, $filter, $where);
        $run_match_query = mysqli_query($connection, $query);
                while ($result = mysqli_fetch_assoc($run_match_query)) {
                    if($val==0){
                        if ($val == 0) {
                            echo $start_table;
                        }
                    }
                    $id = $val % 2;
                    echo "<tr id='td$id'><td>" . $result['Date'] .
                        "</td><td>" . $result['HomeTeam'] .
                        "</td><td>" . $result['FTHG'] .
                        "</td><td>-</td><td>" .
                        $result['FTAG'] . "</td><td>" .
                        $result['AwayTeam'] . "</td></tr>";
                    $val++;
                }
                function get_final_query($team, $season, $filter, $where){
                    /*Possible Queries*/
                    $query_home = "SELECT * FROM `season_" . $season . "` WHERE `HomeTeam`='$team'";
                    $query_away = "SELECT * FROM `season_" . $season . "` WHERE `HomeTeam`='$team'";

                    $query_home_win = "SELECT `Date`, `HomeTeam`, `AwayTeam`, `FTHG`, `FTAG`, `FTR`, `HTHG` 
                             FROM `season_" . $season . "` WHERE `HomeTeam`='$team' and FTHG > FTAG";
                    $query_away_win = "SELECT `Date`, `HomeTeam`, `AwayTeam`, `FTHG`, `FTAG`, `FTR`, `HTHG` 
                             FROM `season_" . $season . "` WHERE `AwayTeam`='$team' and FTAG > FTHG";
                    $query_home_loss = "SELECT `Date`, `HomeTeam`, `AwayTeam`, `FTHG`, `FTAG`, `FTR`, `HTHG` 
                             FROM `season_" . $season . "` WHERE `HomeTeam`='$team' and FTHG < FTAG";
                    $query_away_loss = "SELECT `Date`, `HomeTeam`, `AwayTeam`, `FTHG`, `FTAG`, `FTR`, `HTHG` 
                             FROM `season_" . $season . "` WHERE `AwayTeam`='$team' and FTAG < FTHG";
                    $query_home_draw = "SELECT `Date`, `HomeTeam`, `AwayTeam`, `FTHG`, `FTAG`, `FTR`, `HTHG` 
                             FROM `season_" . $season . "` WHERE `HomeTeam`='$team' and FTHG = FTAG";
                    $query_away_draw = "SELECT `Date`, `HomeTeam`, `AwayTeam`, `FTHG`, `FTAG`, `FTR`, `HTHG` 
                             FROM `season_" . $season . "` WHERE `AwayTeam`='$team' and FTHG = FTAG";
                    $query_both_win = "SELECT `Date`, `HomeTeam`, `AwayTeam`, `FTHG`, `FTAG`, `FTR`, `HTHG` 
                             FROM `season_" . $season . "` WHERE `AwayTeam`='$team'OR `HomeTeam`='$team'  and FTHG > FTAG";
                    $query_both_loss = "SELECT `Date`, `HomeTeam`, `AwayTeam`, `FTHG`, `FTAG`, `FTR`, `HTHG` 
                             FROM `season_" . $season . "` WHERE `AwayTeam`='$team' OR `HomeTeam`='$team' and FTHG < FTAG";
                    $query_both_draw = "SELECT `Date`, `HomeTeam`, `AwayTeam`, `FTHG`, `FTAG`, `FTR`, `HTHG` 
                             FROM `season_" . $season . "` WHERE `AwayTeam`='$team'OR `HomeTeam`='$team'  and FTHG = FTAG";
                    switch ($where) {
                        case 'Home':
                            switch ($filter) {
                                case 'Wins':
                                    return $query_home_win;
                                case 'Losses':
                                    return $query_home_loss;
                                case 'Draws':
                                    return $query_home_draw;
                                case 'Cards':
                                    $val=0;
                                    $query = $query_home;
                                    $run_match_query = mysqli_query($GLOBALS['connection'], $query);
                                    while ($result = mysqli_fetch_assoc($run_match_query)) {
                                        $id = $val % 2;
                                        if($val==0){
                                            "<table>" .
                                                "<tr id='table-header'>" .
                                                "<td>Date</td>" .
                                                "<td>Home Team</td>" .
                                                "<td></td>" .
                                                "<td>-</td>" .
                                                "<td></td>" .
                                                "<td>Away Team</td>" .
                                                "<td>Yello Cards</td>".
                                                "<td>Red Cards</td></tr>";
                                                

                                        }
                                        echo "<tr id='td$id'><td>" . $result['Date'] .
                                            "</td><td>" . $result['HomeTeam'] .
                                            "</td><td>" . $result['FTHG'] .
                                            "</td><td>-</td><td>" .
                                            $result['FTAG'] . "</td><td>" .
                                            $result['AwayTeam'] . "</td><td>".
                                            $result['HY']."</td><td>".
                                            $result['HR']."</td></tr>";
                                        $val++;
                                        
                                    }
                                    include("commons/bottom.html");
                                    exit(1);
                                    break;

                                    case 'Free kicks':
                                        $query = $query_home;
                                        $val=0;
                                        $run_match_query = mysqli_query($GLOBALS['connection'], $query);
                                        
                                        while ($result = mysqli_fetch_assoc($run_match_query)) {
                                        if ($val == 0) {
                                            "<table>" .
                                                "<tr id='table-header'>" .
                                                "<td>Date</td>" .
                                                "<td>Home Team</td>" .
                                                "<td></td>" .
                                                "<td>-</td>" .
                                                "<td></td>" .
                                                "<td>Away Team</td>" .
                                                "<td>Free Kicks</td></tr>";
                                        }
                                            $id = $val % 2;
                                            echo "<tr id='td$id'><td>" . $result['Date'] .
                                                "</td><td>" . $result['HomeTeam'] .
                                                "</td><td>" . $result['FTHG'] .
                                                "</td><td>-</td><td>" .
                                                $result['FTAG'] . "</td><td>" .
                                                $result['AwayTeam'] . "</td><td>" .
                                                $result['HF'] . "</td><td>" ;
                                            $val++;
                                        }
                                        exit(1);
                                break;
                            }
                            break;
                        case 'Away':
                            switch ($filter) {
                                case 'Wins':
                                    return $query_away_win;
                                case 'Losses':
                                    return $query_away_loss;
                                case 'Draws':
                                    return $query_away_draw;
                            }

                            break;
                        case 'Both':
                            switch ($filter) {
                                case 'Wins':
                                    return $query_both_win;
                                case 'Losses':
                                    return $query_both_loss;
                                case 'Draws':
                                    return $query_both_draw;
                            }
                            break;
                    }
                }
                if($val==0){
                    echo "<div id='error'><h1 id='h1-error'>Sorry! No record of " . $team .
                        " for Season ".seasonfy($season)."<br>".
                        "If you believe it played check the spelling of the team</h1></div>";
                }
                ?>
        </table>
    </div>
    <?php include("commons/bottom.html"); ?>