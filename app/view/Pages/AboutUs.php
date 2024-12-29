<?php 
// You can include dynamic variables here if necessary
$title = "About Us - TicketsMarche";
$companyName = "TixCarte";
$aboutText = "The company is aimed at supplying entertainment houses, venue owners, event organizers and planners, with an easy and feasible solution to sell and market their tickets/invitations. The services provided do not end there; TixCarte also provides consultation and fraud protection as well as spectator entry monitoring and surveillance.";
$techText = "In today's world where technological breakthroughs dominate our lives, it has become important that entertainment houses and event venues adopt such technological innovations in running their businesses.";
$solutionText = "These technologies lead to quicker, more accurate and obviously easier ways for these entities to reach their audience and vice versa. TixCarte sees it as a duty that they provide their clients the most convenient way to find their places in the events they want to attend. TixCarte also helps clients find the suitable audience for their events, books the tickets for them, as well as delivers it to the spectators when needed.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="../../../public/css/AboutUs.css">
</head>
<body>

<?php include "../Components/NavBar.php"; ?>
    <section class="about-us">
        <div class="divA">
            <h2>About Us</h2>
            <div class="divb">
                <p><?php echo $aboutText; ?></p>
                <p><?php echo $techText; ?></p>
                <p><?php echo $solutionText; ?></p>
            </div>
        </div>
    </section>
    <?php include "../Components/Footer.php"; ?>
</body>
</html>