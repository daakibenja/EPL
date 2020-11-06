<?php include("commons/top.html") ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/bacon.css">
</head>

<body>

    <div id="search">
        <form action="http://localhost/ip/epl/search_all.php" method="get">
            <div id="direction"><a href="myepld.php" id="home">Home</a></div>
            <b id="tit1">Season</b>
            <input type="text" name="season" id="season" list="seasons">
            
            <h6 id="tit2"> Team
            <input type="text" name="team" id="team" list="teams">
            </h6>
            <input type="submit" value="Search" id="but1">
            <a href="add_new_season.php"><input type="button" value="Add Season" id="but2"></a>
            <a href="remove_season.php"><input type="button" value="Remove Season" id="but3"></a>

            </li>
        </form>

    </div>




</body>

</html>
<?php include("commons/bottom.html"); ?>