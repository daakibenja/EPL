<?php include("commons/top.html"); ?>
<link rel="stylesheet" href="css/bacon.css">
<div id="search">
   <form action="http://localhost/ip/epl/search_all.php" method="get">
        <div id="direction">
            <a href="myepld.php">Home</a> >> <a href="#">Search All</a>
        </div>
        Season
        <input type="text" name="season" id="season" list="seasons" value="1819">
        
        Team
        <input type="text" name="team" id="team" list="teams" value="Liverpool">
        <input type="submit" value="Search">

        </li>
    </form>
</div>
<?php include("commons/bottom.html"); ?>