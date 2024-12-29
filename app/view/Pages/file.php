<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <!-- Font Awesome CSS -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        rel="stylesheet"
    />
    <link rel="stylesheet" href="../public/css/general.css" />
    <link rel="stylesheet" href="../public/css/contactus.css" />
</head>
<body>
    <?php
    include "../Components/NavBar.php";
    require_once("../../db/Dbh.php");

    $message = ""; // Default message

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate the form fields
        $first_name = isset($_POST['first_name']) ? htmlspecialchars(trim($_POST['first_name'])) : '';
        $last_name = isset($_POST['last_name']) ? htmlspecialchars(trim($_POST['last_name'])) : '';
        $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
        $prefix = isset($_POST['prefix']) ? htmlspecialchars(trim($_POST['prefix'])) : '';
        $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
        $message_text = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
        $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : null;
        $agree_privacy = isset($_POST['agree_privacy']);

        // Check for errors
        // if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($message_text) || !$agree_privacy || $subject === null) {
        //     $message = "<div class='alert alert-danger'>All fields are required. Please complete the form correctly.</div>";
        // } else {
            try {
                // Database connection
                $dbh = new DBh();
                $conn = $dbh->connect();

                // Prepare SQL query
                $sql = "INSERT INTO chat (fname, lname, email, phone, message, status, subject) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception("Failed to prepare statement: " . $conn->error);
                }

                // Bind parameters
                $status = "pending";
                $stmt->bind_param("sssssss", $first_name, $last_name, $email, $phone, $message_text, $status, $subject);

                // Execute query
                if ($stmt->execute()) {
                    $message = "<div class='alert alert-success'>Your message has been sent successfully!</div>";
                } else {
                    throw new Exception("Failed to execute statement: " . $stmt->error);
                }

                $stmt->close();
                $conn->close();
            } catch (Exception $e) {
                $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
        // }
    }
    ?>

    <div class="container">
        <section class="contactus main_content">
            <h2 class="h2-mb" style='font-size:3rem; font-weight:700;'>Contact Us</h2>

            <!-- Display success/error message -->
            <?php if (!empty($message)) : ?>
                <?= $message ?>
            <?php endif; ?>

            <div class="row mb-3" style="margin: 0">
                <div class="col-xs-12" style="padding: 0">
                    <div class="row" style="margin: 0">
                        <div class="col-sm-12 col-md-12 col-lg-4 box_corner box_white left_box" style='background-color:#ffffff82; height:14rem; width:15rem; margin-right:9rem; border-radius:2rem; padding:1rem'>
                            <h2>TickCarte</h2>
                            <p class="opening-time body-4-regular">10am - 10pm / Everyday</p>
                            <div class="d-flex justify-content-start align-content-center flex-column flex-md-row call-icon">
                                <div class="align-self-center">
                                    <span class="type-contacts-icon1 the_icon">
                                        <i class="fas fa-phone"></i>
                                    </span> 
                                </div>
                                <div class="align-self-center phone body-4-regular">16826 / +202 2463 7000</div>
                            </div>
                            <div class="d-flex justify-content-start align-content-center flex-column flex-md-row call-icon">
                                <div class="align-self-center">
                                    <span class="type-contacts-icon2 the_icon">
                                        <i class="fab fa-whatsapp"></i>
                                    </span>
                                </div>
                                <div class="align-self-center phone body-4-regular">
                                    +20 100 842 5387<br />+20 100 TICKETS
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-8 box_corner box_white right_box"  style='background-color:#ffffffa2; padding:2rem; border-radius:1rem'>
                            <form
                                name="contactus"
                                id="contactus"
                                method="POST"
                                class="needs-validation"
                               
                            >
                                <h2 class="h2-mb" style='color:orange; font-weight:600'>How can we help ?</h2>
                                <div class="row mb-3">
                                    <div class="col-md-6 col-sm-12 field-row">
                                        <input
                                            type="text"
                                            name="first_name"
                                            id="first_name"
                                            class="form-control body-5 form_input_text"
                                            placeholder="First Name"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-6 col-sm-12 field-row">
                                        <input
                                            type="text"
                                            name="last_name"
                                            id="last_name"
                                            class="form-control body-5 form_input_text"
                                            placeholder="Last Name"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control body-5 form_input_text"
                                        placeholder="Email"
                                        required
                                    />
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-2 col-sm-12 field-row">
                                        <input
                                            type="text"
                                            name="prefix"
                                            id="prefix"
                                            class="form-control body-5 form_input_text"
                                            placeholder="+20"
                                            required
                                        />
                                    </div>
                                    <div class="col-md-10 col-sm-12 field-row">
                                        <input
                                            type="text"
                                            name="phone"
                                            id="phone"
                                            class="form-control body-5 form_input_text"
                                            placeholder="Phone Number"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <select name="subject" class="form-control body-5 form_input_text" required>
                                        <option value="" disabled selected>Choose your issue type</option>
                                        <option value="Issue with payment">Issue with payment</option>
                                        <option value="Issue with account">Issue with account</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <textarea
                                        name="message"
                                        id="message"
                                        class="form-control body-5 form_input_text"
                                        placeholder="Your Message"
                                        rows="4"
                                        required
                                    ></textarea>
                                </div>
                                <div class="d-flex justify-content-between flex-column flex-md-row mb-4 gap-3">
                                    <div class="form-check-inline align-self-center">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="agree_privacy"
                                            id="agree_privacy"
                                            value="privacy"
                                            required
                                        />
                                        <label class="form-check-label body-6" for="agree_privacy">
                                            I agree with the privacy policy
                                        </label>
                                    </div>
                                    <div>
                                        <button
                                            type="submit"
                                            id="sendMessage"
                                            class="btn btn-primary"
                                            style='background-color:orange; border-color:orange'
                                        >
                                            Send Message
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include "../Components/Footer.php"; ?>
</body>
</html>
