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
    <?php include "../Components/NavBar.php"?>
    <div class="container">
      <section class="contactus main_content">
        <h2 class="h2-mb">Contact Us</h2>

        <?php
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
              // Collect and sanitize form data
              $first_name = htmlspecialchars(trim($_POST["first_name"]));
              $last_name = htmlspecialchars(trim($_POST["last_name"]));
              $email = htmlspecialchars(trim($_POST["email"]));
              $prefix = htmlspecialchars(trim($_POST["prefix"]));
              $phone = htmlspecialchars(trim($_POST["phone"]));
              $message = htmlspecialchars(trim($_POST["message"]));
              $agree_privacy = isset($_POST["agree_privacy"]) ? $_POST["agree_privacy"] : "";

              // Simple validation
              if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($phone) && !empty($message) && !empty($agree_privacy)) {
                  // Process the form data (e.g., send an email or store in database)
                  $to = "support@ticketsmarche.com"; // Your email address
                  $subject = "Contact Us Form Submission";
                  $body = "Name: $first_name $last_name\nEmail: $email\nPhone: $prefix $phone\nMessage:\n$message";
                  $headers = "From: $email";

                  if (mail($to, $subject, $body, $headers)) {
                      echo "<div class='alert alert-success'>Your message has been sent successfully!</div>";
                  } else {
                      echo "<div class='alert alert-danger'>There was an error sending your message. Please try again.</div>";
                  }
              } else {
                  echo "<div class='alert alert-danger'>All fields are required. Please complete the form.</div>";
              }
          }
        ?>

        <div class="row mb-3" style="margin: 0">
          <div class="col-xs-12" style="padding: 0">
            <div class="row" style="margin: 0">
              <div
                class="col-sm-12 col-md-12 col-lg-4 box_corner box_white left_box"
              >
                <h2>TicketsMarche</h2>
                <p class="opening-time body-4-regular">
                  10am - 10pm / Everyday
                </p>
                <div class="d-flex justify-content-start align-content-center flex-column flex-md-row call-icon">
                  <div class="align-self-center">
                    <span class="type-contacts-icon1 the_icon">
                      <i class="fas fa-phone"></i>
                    </span>
                  </div>
                  <div class="align-self-center phone body-4-regular">
                    16826 / +202 2463 7000
                  </div>
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
              <div
                class="col-sm-12 col-md-12 col-lg-8 box_corner box_white right_box"
              >
                <form
                  name="contactus"
                  id="contactus"
                  method="POST"
                  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                  class="needs-validation"
                >
                  <h2 class="h2-mb">How can we help ?</h2>

                  <div class="row mb-3">
                    <div class="col-md-6 col-sm-12 field-row">
                      <input
                        type="text"
                        name="first_name"
                        id="first_name"
                        class="form-control body-5 form_input_text"
                        placeholder="First Name"
                        aria-label="First Name"
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
                        aria-label="Last Name"
                        required
                      />
                    </div>
                  </div>
                  <div class="col-12 mb-3">
                    <div class="col-md-12 col-sm-12 field-row">
                      <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control body-5 form_input_text"
                        placeholder="Email"
                        required
                      />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-2 col-sm-12 field-row">
                      <input
                        type="text"
                        name="prefix"
                        id="prefix"
                        class="form-control body-5 form_input_text"
                        placeholder="+20"
                        aria-label="+20"
                        required
                      />
                    </div>
                    <div class="col-md-10 col-sm-12 field-row">
                      <input
                        type="number"
                        name="phone"
                        id="phone"
                        class="form-control body-5 form_input_text"
                        placeholder="Phone Number"
                        aria-label="Phone Number"
                        required
                      />
                    </div>
                  </div>
                  <div class="mb-3 mb-3">
                    <div class="col-md-12 col-sm-12 field-row">
                      <textarea
                        name="message"
                        id="message"
                        class="form-control body-5 form_input_text"
                        placeholder="Your Message"
                        aria-label="Your Message"
                        rows="4"
                        required
                      ></textarea>
                    </div>
                  </div>
                  <div
                    class="d-flex justify-content-between flex-column flex-md-row mb-4 gap-3"
                  >
                    <div class="form-check-inline align-self-center">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="agree_privacy"
                        id="agree_privacy"
                        value="privacy"
                        required
                      />
                      <label class="form-check-label body-6" for="inlineRadio1">
                        I agree with a privacy policy
                      </label>
                    </div>
                    <div>
                      <button
                        type="submit"
                        id="sendMessage"
                        class="btn btn-tm-primary submit-button body-5"
                      >
                        Send Message
                        <svg
                          width="22"
                          height="17"
                          viewBox="0 0 22 17"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            d="M1.89258 8.54541H20.5592M20.5592 8.54541L13.5592 1.54541M20.5592 8.54541L13.5592 15.5454"
                            stroke="black"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                          />
                        </svg>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section style="margin-top: 56px">
        <h2 class="h2-mb">Support & FAQ</h2>
        <div
          class="container mt-5 col-6 search-container"
          style="display: none"
        >
          <div class="input-group">
            <span class="input-group-text input-group-holder"
              ><i class="far fa-search"></i
            ></span>
            <input
              type="text"
              class="form-control search-input-field"
              placeholder="Search for common issues or questions"
              aria-label="Search"
              aria-describedby="basic-addon1"
            />
            <button
              class="btn btn-tm-primary search-input-button"
              type="button"
            >
              Search
            </button>
          </div>
        </div>
        <div class="row mb-3 gy-4 faq-boxes">
          <div class="col-xs-12 col-sm-4 gx-5">
            <div class="row align-items-center">
              <div
                class="col-4 box_corner box_background faq_box d-flex align-items-center justify-content-center"
              >
                <i class="far fa-ticket fa-3x"></i>
              </div>
              <div
                class="col-8 box_corner box_white faq_box d-flex align-items-center justify-content-center"
              >
                <a href="/faq/ticket-availability"
                  ><h3>Ticket Availability</h3></a
                >
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4 gx-5">
            <div class="row align-items-center">
              <div
                class="col-4 box_corner box_background faq_box d-flex align-items-center justify-content-center"
              >
                <i class="far fa-tag fa-3x"></i>
              </div>
              <div
                class="col-8 box_corner box_white faq_box d-flex align-items-center justify-content-center"
              >
                <a href="/faq/ticket-prices"
                  ><h3>Ticket Prices</h3>
                  <span>All your questions answered</span></a
                >
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4 gx-5">
            <div class="row align-items-center">
              <div
                class="col-4 box_corner box_background faq_box d-flex align-items-center justify-content-center"
              >
                <i class="far fa-shopping-basket fa-3x"></i>
              </div>
              <div
                class="col-8 box_corner box_white faq_box d-flex align-items-center justify-content-center"
              >
                <a href="/faq/buying-tickets"
                  ><h3>Buying Tickets</h3>
                  <span>All your questions answered</span></a
                >
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4 gx-5">
            <div class="row align-items-center">
              <div
                class="col-4 box_corner box_background faq_box d-flex align-items-center justify-content-center"
              >
                <i class="far fa-smile fa-3x"></i>
              </div>
              <div
                class="col-8 box_corner box_white faq_box d-flex align-items-center justify-content-center"
              >
                <a href="/faq/customer-service"
                  ><h3>Customer Service</h3>
                  <span>Learn about how we do things</span></a
                >
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4 gx-5">
            <div class="row align-items-center">
              <div
                class="col-4 box_corner box_background faq_box d-flex align-items-center justify-content-center"
              >
                <i class="far fa-location-arrow fa-3x"></i>
              </div>
              <div
                class="col-8 box_corner box_white faq_box d-flex align-items-center justify-content-center"
              >
                <a href="/faq/where-to-buy"><h3>Where to Buy Tickets?</h3></a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <?php include "../Components/Footer.php"?>
  </body>
</html>
