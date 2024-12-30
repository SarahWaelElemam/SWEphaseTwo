<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="../../../public/css/pay.css" />
        <title>Movie Seat Booking</title>
    </head>
    <body>
        <?php include "../Components/NavBar.php";
        require_once("../../db/Dbh.php");
        require_once("../../model/Ticket.php");
        $dbh = new Dbh();
        $conn = $dbh->getConn();
        $ticketsModel = new Ticket($conn);
        // Check if we have POST data
        if (!isset($_POST['event_id']) || empty($_POST['event_id'])) {
        echo "<p>Error: Event ID is missing.</p>";
        exit;
        }

    $eventId = intval($_POST['event_id']);
    // Fetch tickets for the event
    $tickets = $ticketsModel->getTicketsByEvent($eventId);

    if (empty($tickets)) {
        echo "<p>No tickets available for this event.</p>";
    }

    // Format date and time for display
    $dateTime = date('M d | h:ia', strtotime($_POST['date'] . ' ' . $_POST['time']));
    ?>
        <div class="checkout">	
        <div class="selectTicket">

        <div class="container" id="container1">
        <div class="screen"></div>
        <?php
        $seatsPerRow = 20;  // Maximum seats per row
        $totalRows = 8;      // Ensure exactly 8 rows
        $seatCount = count($tickets);  // Total tickets available
        $currentTicket = 0;

        // Ensure we have exactly 8 rows, even if we don't have enough tickets
        for ($i = 1; $i <= $totalRows; $i++) {
            echo '<div class="row" data-row="' . $i . '">';
            
            // Calculate the number of seats in this row
            $seatsInThisRow = min($seatsPerRow, $seatCount - ($currentTicket));

            // Add available seats (if tickets are available)
            for ($j = 1; $j <= $seatsInThisRow; $j++) {
                if ($currentTicket < $seatCount) {
                    $ticket = $tickets[$currentTicket];
                    $type = htmlspecialchars($ticket['type']);
                    $price = htmlspecialchars($ticket['Price']);
                    $ticketId = htmlspecialchars($ticket['Ticket_ID']);
                    $status = htmlspecialchars($ticket['Status']);
                    
                    $tooltip = "$type Seat: {$price}LE";
                    $soldClass = ($status === 'Sold') ? ' sold' : '';
                    echo '<div class="seat ' . $soldClass . '"
                            data-tooltip="' . $tooltip . '"
                            data-type="' . $type . '"
                            data-price="' . $price . '"
                            data-ticket-id="' . $ticketId . '"
                            data-row="' . $i . '"
                            data-seat="' . $j . '">
                        </div>';
                    $currentTicket++;
                }
            }

            // Fill remaining seats in row with "no-ticket" if there are fewer tickets than seats
            $remainingSeats = $seatsPerRow - $seatsInThisRow;
            for ($k = 1; $k <= $remainingSeats; $k++) {
                echo '<div class="seat no-ticket" data-row="' . $i . '" data-seat="' . ($seatsInThisRow + $k) . '" style="background-color: rgb(187, 186, 186);" data-tooltip="No Ticket"></div>';
            }

            echo '</div>';
        }
        ?>
    </div>


        <!-- Container 2 -->
        <div class="container" id="container2">
            <div class="screen"></div>
            <div class="allrows">
                <?php
                $rows = 7;
                $seats = 20;
                for ($i = 1; $i <= $rows; $i++) {
                    echo '<div class="row">';
                    for ($j = 1; $j <= $seats; $j++) {
                    $tooltip = "Silver Seat: 50LE" ;
                    
                    echo '<div class="seat" data-tooltip="'.$tooltip.'"></div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <!-- Buttons to Toggle Containers -->
        <div class="button-container">
            <button class="btn active" id="show1" onclick="SHOW1()">1</button>
            <button class="btn" id="show2" onclick="SHOW2()">2</button>
        </div>
        </div>
        

        
        <!-- Confirmation Modal -->
    <div id="seatModal" class="modal" style="display:none;">
        <div class="modal-content">
            <p id="seatInfo"></p>
            <p id="seatPrice"></p>
            <button id="confirmBtn">Confirm</button>
            <button id="cancelBtn">Cancel</button>
        </div>
    </div>
    <!-- Warning Modal -->
    <div id="warningModal" class="modall" style="display:none;">
        <div class="modall-content">
            <p id="warningMessage">Please select and confirm at least one seat before proceeding.</p>
            <button id="closeWarningBtn">OK</button>
        </div>
    </div>
    <div class="Payment" style="display:none">
        <div class="payment-header">
            <h2>Payment Details</h2>
        </div>
        
        <div class="payment-summary">
        <h3>Order Summary</h3>
        <p><?php echo htmlspecialchars($_POST["Category"]); ?>: <span><?php echo htmlspecialchars($_POST["name"]); ?></span></p>
        <p>Date: <span><?php echo htmlspecialchars($dateTime); ?></span></p>
        <p>Selected Seats: <span id="paymentSeatsDisplay"></span></p>
        <p>Total Amount: <span id="paymentTotalDisplay"></span></p>
    </div>

        <form class="payment-form" id="paymentForm">
            <div class="payment-method">
                <label>
                    <input type="radio" name="paymentMethod" value="card" checked>
                    <i class="fa-brands fa-cc-mastercard"></i>Credit/Debit Card
                </label>
                <label>
                    <input type="radio" name="paymentMethod" value="wallet">
                    <img src="../images/valU.png">
                </label>
            </div>
        <div class="creditcard">
            <div class="form-group">
                <label>Cardholder Name</label>
                <input type="text" required placeholder="Name on card" style="text-transform: uppercase;">
                
            </div>

            <div class="card-details">
                <div class="form-group">
                    <label>Card Number</label>
                    <input id="cardNumber" type="text" required placeholder="1234 5678 9012 3456" maxlength="12">
                    <span id="cardNumberError" style="color: red; display: none;">Card number must be exactly 12 digits.</span>
                </div>
                <div class="form-group">
                    <label>Expiry</label>
                    <input type="text" required placeholder="MM/YY">
                </div>
                <div class="form-group">
                    <label>CVV</label>
                    <input type="text" required placeholder="123">
                </div>
            </div>

            <div class="payment-buttons">
                <button type="button" class="btn-cancel" id="cancelPayment">Cancel</button>
                <button type="submit" class="btn-pay" onclick="validateCardNumber(event)">Pay Now</button>
            </div>
        </div>
        <div class="value">
        <div class="form-group">
                <label>Cardholder Name</label>
                <input type="text" required placeholder="Your Full Name">
        </div>
        <div class="form-group">
                <label>Mobile Number</label>
                <input type="text" required placeholder="Mobile Number">
        </div>
        <div class="TotalPay">
            <p></p>
        </div>
        <div class="payment-buttons">
                <button type="button" class="btn-cancel" id="cancelPayment">Cancel</button>
                <button type="submit" class="btn-pay" onclick="validateCardNumber(event)">Pay Now</button>
            </div>
        </div>

        </form>
    </div>

    <div class="printing" style="display:none">
    <div class="top">
    <p class="title">Wait a second, your ticket is sending to your email</p>
    <div class="printer" >
    </div>
    <div class="receipts-wrapper">
        <div class="receipts">
            <div class="receipt">
                <img src="../images/logo.png">
                <div class="route">
                <h4> Thank you for using TickCarte</h4>
                </div>
                <div class="details">
                <div class="item">
                    <span>Ticket id</span>
                    <p><?php echo htmlspecialchars($ticket['Ticket_ID']); ?></p>
                </div>
                <div class="item">
                    <span><?php echo htmlspecialchars($_POST['Category']); ?></span>
                    <p><?php echo htmlspecialchars($_POST['name']); ?></p>
                </div>
                <div class="item">
                    <span>Seats</span>
                    <p ></p>
                </div>
                <div class="item">
                    <span>Date | Time</span>
                    <p><?php echo htmlspecialchars($dateTime); ?></p>
                </div>
                </div>
            </div>
            <div class="receipt qr-code">
                <svg class="qr" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.938 29.938">
                <path d="M7.129 15.683h1.427v1.427h1.426v1.426H2.853V17.11h1.426v-2.853h2.853v1.426h-.003zm18.535 12.83h1.424v-1.426h-1.424v1.426zM8.555 15.683h1.426v-1.426H8.555v1.426zm19.957 12.83h1.427v-1.426h-1.427v1.426zm-17.104 1.425h2.85v-1.426h-2.85v1.426zm12.829 0v-1.426H22.81v1.426h1.427zm-5.702 0h1.426v-2.852h-1.426v2.852zM7.129 11.406v1.426h4.277v-1.426H7.129zm-1.424 1.425v-1.426H2.852v2.852h1.426v-1.426h1.427zm4.276-2.852H.002V.001h9.979v9.978zM8.555 1.427H1.426v7.127h7.129V1.427zm-5.703 25.66h4.276V22.81H2.852v4.277zm14.256-1.427v1.427h1.428V25.66h-1.428zM7.129 2.853H2.853v4.275h4.276V2.853zM29.938.001V9.98h-9.979V.001h9.979zm-1.426 1.426h-7.127v7.127h7.127V1.427zM0 19.957h9.98v9.979H0v-9.979zm1.427 8.556h7.129v-7.129H1.427v7.129zm0-17.107H0v7.129h1.427v-7.129zm18.532 7.127v1.424h1.426v-1.424h-1.426zm-4.277 5.703V22.81h-1.425v1.427h-2.85v2.853h2.85v1.426h1.425v-2.853h1.427v-1.426h-1.427v-.001zM11.408 5.704h2.85V4.276h-2.85v1.428zm11.403 11.405h2.854v1.426h1.425v-4.276h-1.425v-2.853h-1.428v4.277h-4.274v1.427h1.426v1.426h1.426V17.11h-.004zm1.426 4.275H22.81v-1.427h-1.426v2.853h-4.276v1.427h2.854v2.853h1.426v1.426h1.426v-2.853h5.701v-1.426h-4.276v-2.853h-.002zm0 0h1.428v-2.851h-1.428v2.851zm-11.405 0v-1.427h1.424v-1.424h1.425v-1.426h1.427v-2.853h4.276v-2.853h-1.426v1.426h-1.426V7.125h-1.426V4.272h1.426V0h-1.426v2.852H15.68V0h-4.276v2.852h1.426V1.426h1.424v2.85h1.426v4.277h1.426v1.426H15.68v2.852h-1.426V9.979H12.83V8.554h-1.426v2.852h1.426v1.426h-1.426v4.278h1.426v-2.853h1.424v2.853H12.83v1.426h-1.426v4.274h2.85v-1.426h-1.422zm15.68 1.426v-1.426h-2.85v1.426h2.85zM27.086 2.853h-4.275v4.275h4.275V2.853zM15.682 21.384h2.854v-1.427h-1.428v-1.424h-1.427v2.851zm2.853-2.851v-1.426h-1.428v1.426h1.428zm8.551-5.702h2.853v-1.426h-2.853v1.426zm1.426 11.405h1.427V22.81h-1.427v1.426zm0-8.553h1.427v-1.426h-1.427v1.426zm-12.83-7.129h-1.425V9.98h1.425V8.554z"/>
                </svg>
                <div class="description">
                <p>Your QR code is generated</p>
                <p>check Your Email</p>
                </div>
            </div>
        </div>
    </div>
    </div>
                </div>
    <div class="info">
    <img src="../../../public/images/<?php echo htmlspecialchars($_POST["image"]); ?>" alt="Memo">
    <div class="details">
        <p class="name"><?php echo htmlspecialchars($_POST["name"]); ?></p>
        <p class="date"><?php echo htmlspecialchars($dateTime); ?></p>
    </div>
        <div class="containerr">
        <div class="steps">
            <div class="circle-wrapper">
                <div class="circle active">1</div><span>Select Ticket</span>
            </div>
            <div class="progress-bar active">
                <div class=try>
                <span class="indicator"></span>
                <span id="selectedSeatsDisplay" class="selected-seats-display"></span>
                </div>
            </div>
            <div class="circle-wrapper">
                <div class="circle">2</div><span>Review and Checkout</span>
            </div>
            <div class="progress-bar"><span class="indicator"></span></div>
            <div class="circle-wrapper">
                <div class="circle">3</div><span>Send Ticket</span>
            </div>
        </div>
        
        <div class="buttons">
            <button id="prev" disabled>Previous</button>
            <button id="next">Next</button>
        </div>
        
        <div id="totalCostDisplay" class="total-cost-display">
            Total: 0 LE
        </div>
    </div>
    </div>
        </div>
        <?php include "../Components/Footer.php"?>
        <script src="../../../public/js/paymentCheck.js"></script>
        <script src="../../../public/js/pay.js"></script>
        <script src="../../../public/js/progress.js"></script>
        <script>
    const seats = document.querySelectorAll('.seat:not(.disabled):not(.no-ticket)');  // Exclude 'no-ticket' seats
    const selectedSeats = [];  // Array to store selected seats
    const selectedSeatPrices = []; // Array to store prices of selected seats
    const paymentSeatsDisplay = document.getElementById('paymentSeatsDisplay');
    const paymentTotalDisplay = document.getElementById('paymentTotalDisplay');
    
    seats.forEach(seat => {
        seat.addEventListener('click', (e) => {
            // Check if seat is sold or no-ticket
            if (seat.classList.contains('sold') || seat.classList.contains('no-ticket')) {
                return; // Do nothing if seat is sold or has no ticket
            }

            // Toggle the selected class on seat
            seat.classList.toggle('selected');

            // Update the selected seats array
            const ticketId = seat.getAttribute('data-ticket-id');
            const price = parseFloat(seat.getAttribute('data-price'));

            // If the seat is selected, add it to the selectedSeats array
            if (seat.classList.contains('selected')) {
                selectedSeats.push(ticketId);
                selectedSeatPrices.push(price);
            } else {
                // If the seat is deselected, remove it from the selectedSeats array
                const index = selectedSeats.indexOf(ticketId);
                if (index > -1) {
                    selectedSeats.splice(index, 1);
                    selectedSeatPrices.splice(index, 1);
                }
            }

            // Update the display for selected seats and total cost
            updateSelectedSeatsDisplay();
            updateTotalCostDisplay();
        });
    });

    // Update the selected seats display in the payment container
    function updateSelectedSeatsDisplay() {
        if (selectedSeats.length === 0) {
            paymentSeatsDisplay.textContent = 'No seats selected';
        } else {
            paymentSeatsDisplay.textContent = selectedSeats.join(', ');
        }
    }

    // Update the total cost display in the payment container
    function updateTotalCostDisplay() {
        const totalCost = selectedSeatPrices.reduce((acc, price) => acc + price, 0);
        paymentTotalDisplay.textContent = `${totalCost.toFixed(2)} LE`;
    }

    // Example of how you can handle the next button functionality
    document.getElementById('next').addEventListener('click', () => {
    if (selectedSeats.length === 0) {
        // Show a warning if no seats are selected
        document.getElementById('warningModal').style.display = 'block';
    } else {
        // Show the payment container
        document.querySelector('.Payment').style.display = 'block';
        document.querySelector('.selectTicket').style.display = 'none';

        // Now also update the printing section with selected seats
        updatePrintingSection();
    }
});

    // Close warning modal when clicking OK
    document.getElementById('closeWarningBtn').addEventListener('click', () => {
        document.getElementById('warningModal').style.display = 'none';
    });
    function updatePrintingSection() {
    const printingSeatsDisplay = document.getElementById('paymentSeatsDisplay');  // Assuming you have this div
    const selectedSeatsString = selectedSeats.length === 0 ? 'No seats selected' : selectedSeats.join(', ');

    // Update the seat display in the "printing" div
    const printingSeatsElement = document.querySelector('.printing .item span');
    printingSeatsElement.textContent = selectedSeatsString;
}
</script>

    </body>
    </html>

