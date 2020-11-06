<?php include("commons/top.html"); ?>
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
    <div id="csv-file">
        <form action="http://localhost/phpmyadmin/db_import.php?db=epld" method="get" enctype="multipart/form-data">
            <label> Choose a CSV file
                <input type="file" name="table" id="">
            </label>
            <input type="submit" value="Ok">
        </form>
    </div>
</body>

</html>
<?php include("commons/bottom.html"); ?>