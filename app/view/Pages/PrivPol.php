<?php
// PHP logic (if needed for dynamic content)
$title = "Privacy and Policy - TixCarte";
$companyName = "TixCarte";
$address = "127 Merghany St, Heliopolis, 11341, Cairo, Egypt";
$email = "legal at TicketsMarche.com";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="../../../public/css/PrivPol.css"> <!-- Include your CSS file here -->
</head>
<body>
<?php include "../Components/NavBar.php"?>
    <header>
    </header>
    
    <section class="PrivPol">
        <h2>Our Policies / Privacy Policy</h2>
        <p>Welcome to Ticketsmarche! Please know that it is very important to us to protect your personal information. You must also know that we totally respect your privacy. Our Privacy Policy below is there to explain how we handle the information you share with us.</p>
        
        <h4>Consumer Privacy at Ticketsmarche:</h4>
        <p>At Ticketsmarche, we respect our user's privacy and we understand the importance of the information they share with us. The below text shows the basic information policies and practices we use on this site.</p>
        
        <h4>Information we may collect:</h4>
        <p>We may collect personal information that we can identify you with, such as your name, and other contact details when you log on to our site, or when provided by our business partners, and/or from third parties. We may also collect other information in the following ways:</p>
        <ul>
            <li>Our server logs automatically collect information such as your browser type, language, your time of visit, and your IP address. This information helps us track movements around our site and understand customer trends.</li>
            <li>We may provide your computer with cookies which help you personalize your experience on our site. We assume that all of our users are above the age of 13. We therefore do not knowingly collect information from children under that age and we fully comply with the Children's Online Privacy Protection Act.</li>
        </ul>
        
        <h4>Information others may collect:</h4>
        <ul>
            <li>Third party companies and/or advertisement networks may advertise on our website.</li>
            <li>We do not provide them with any personal information.</li>
        </ul>
        
        <h4>Changes to our Privacy Policy</h4>
        <p>Our privacy Policy may be updated occasionally to reflect changes in our services and business operations. If serious changes are done to the policy, you will be notified by email to the email address you most recently used in accessing our site.</p>
        
        <h4>How to contact us</h4>
        <p>If you have any questions about this summary, our Privacy Policy, or our information practices, please contact us by email or postal mail below:</p>
        
        <footer>
            <p><?php echo $companyName; ?><br><?php echo $address; ?><br><?php echo $email; ?></p>
            <p><i>&copy; <?php echo $companyName; ?>. All rights reserved.</i></p>
        </footer>
    </section>
    <?php include "../Components/Footer.php"?>
</body>
</html>