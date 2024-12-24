<?php include "../Components/NavBar.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/Events.css">
    <title>Events</title>
    
</head>
<body>
<div class="filter">
        <h3>Event Filter</h3>
        <div class="filter-box">
            <input type="text" id="startDate" placeholder="Start Date" onclick="openCalendar(startDateBox)">
            <input type="text" id="endDate" placeholder="End Date" onclick="openCalendar(endDateBox)">
            <input type="button" id="filterbtn" value="Filter">
        </div>
        
    </div>

    <!-- Calendar Modal -->
    <div class="overlay" id="overlay"></div>
    <div class="calendar-modal" id="calendarModal">
        <h3>Select Date</h3>
        <div id="calendar-header">
            <button id="prevMonth">&lt;</button>
            <span id="currentMonthYear"></span>
            <button id="nextMonth">&gt;</button>
        </div>
        <div id="calendar"></div>
        <button id="closeModal">Close</button>
    </div>
</div>
<!-- Row 1-->
<div class="row-tick">
    <!--Ticket 1-->
<div class="ticket">
  <div class="side front">
    <img src="../images/eventMemo.png">
    <div class="info bottom">
      <h1>Memo</h1>
      <span class="title address">Grand Nile Tower</span>
      <p><i class="fa-solid fa-circle" style="color: #03b300;"></i> Price Range: 500L.E - 2,500L.E</p>
      <dl>
        <dt>Date</dt>
        <dd>17 Oct</dd>
        <dt>Time</dt>
        <dd>9:00pm</dd>
      </dl>
    </div>
  </div>
  <div class="side back">
    <div class="top">
      <div class="span">
        <h2>Organized By</h2>
        <span>Craft Media</span>
      </div>
      <div class="span">
        <h2>Location</h2>
        <span>Grand Nile Tower</span>
        <p>Abdulaziz Al Saud, Old Cairo, Cairo</p>
      </div>
    </div>
    
    <div class="payment bottom">
    <button class="Book" onclick="handleBooking()">
  <i class="fa-solid fa-ticket"></i> Book Now
</button>
    </div>
  </div>
  
</div>
<!--Ticket 2-->
<div class="ticket">
  <div class="side front">
    <img src="../images/sahebna.jpg">
    <div class="info bottom">
      <h1>Sa7ebna</h1>
      <span class="title address">AUC Tahrir, Falaki Theater</span>
      <p><i class="fa-solid fa-circle" style="color: #03b300;"></i> Price: 280L.E</p>
      <dl>
        <dt>Date</dt>
        <dd>17 Oct</dd>
        <dt>Time</dt>
        <dd>8:00pm</dd>
      </dl>
    </div>
  </div>
  <div class="side back">
    <div class="top">
      <div class="span">
        <h2>Organized By</h2>
        <span>Act Theater Group</span>
      </div>
      <div class="span">
        <h2>Location</h2>
        <span>AUC Tahrir, Falaki Theater</span>
        <p>Al Falaki, Abdeen, Cairo Governorate</p>
      </div>
    </div>
    
    <div class="payment bottom">
     <button class="Book"><i class="fa-solid fa-ticket"></i> Book Now</button>
    </div>
  </div>
</div>
<!--Ticket 3-->
<div class="ticket">
  <div class="side front">
    <img src="../images/The Comedy Bunch Live at Boom Roomjpg.jpg">
    <div class="info bottom">
      <h1>The Comedy Bunch Live</h1>
      <span class="title address">Boom Room, Open Air Mall, Madinaty</span>
      <p><i class="fa-solid fa-circle" style="color: #03b300;"></i> Price: 350L.E</p>
      <dl>
        <dt>Date</dt>
        <dd>15 Oct</dd>
        <dt>Time</dt>
        <dd>9:00pm</dd>
      </dl>
    </div>
  </div>
  <div class="side back">
    <div class="top">
      <div class="span">
        <h2>Organized By</h2>
        <span>Boom Room</span>
      </div>
      <div class="span">
        <h2>Location</h2>
        <span>Boom Room, Open Air Mall, Madinaty</span>
        <p>Open Air Mall, parking Gate 3, Madinaty, Cairo</p>
      </div>
    </div>
    
    <div class="payment bottom">
     <button class="Book"><i class="fa-solid fa-ticket"></i> Book Now</button>
    </div>
  </div>
</div>
</div>
<!-- Row 1-->
<div class="row-tick">
    <!--Ticket 1-->
<div class="ticket">
  <div class="side front">
    <img src="../images/event5.png">
    <div class="info bottom">
      <h1>Memo</h1>
      <span class="title address">Grand Nile Tower</span>
      <p><i class="fa-solid fa-circle" style="color: #03b300;"></i> Price Range: 500L.E - 2,500L.E</p>
      <dl>
        <dt>Date</dt>
        <dd>17 Oct</dd>
        <dt>Time</dt>
        <dd>9:00pm</dd>
      </dl>
    </div>
  </div>
  <div class="side back">
    <div class="top">
      <div class="span">
        <h2>Organized By</h2>
        <span>Craft Media</span>
      </div>
      <div class="span">
        <h2>Location</h2>
        <span>Grand Nile Tower</span>
        <p>Abdulaziz Al Saud, Old Cairo, Cairo</p>
      </div>
    </div>
    
    <div class="payment bottom">
     <button class="Book"><i class="fa-solid fa-ticket"></i> Book Now</button>
    </div>
  </div>
  
</div>
<!--Ticket 2-->
<div class="ticket">
  <div class="side front">
    <img src="../images/event1.png">
    <div class="info bottom">
      <h1>Sa7ebna</h1>
      <span class="title address">AUC Tahrir, Falaki Theater</span>
      <p><i class="fa-solid fa-circle" style="color: #03b300;"></i> Price: 280L.E</p>
      <dl>
        <dt>Date</dt>
        <dd>17 Oct</dd>
        <dt>Time</dt>
        <dd>8:00pm</dd>
      </dl>
    </div>
  </div>
  <div class="side back">
    <div class="top">
      <div class="span">
        <h2>Organized By</h2>
        <span>Act Theater Group</span>
      </div>
      <div class="span">
        <h2>Location</h2>
        <span>AUC Tahrir, Falaki Theater</span>
        <p>Al Falaki, Abdeen, Cairo Governorate</p>
      </div>
    </div>
    
    <div class="payment bottom">
     <button class="Book"><i class="fa-solid fa-ticket"></i> Book Now</button>
    </div>
  </div>
</div>
<!--Ticket 3-->
<div class="ticket">
  <div class="side front">
    <img src="../images/event4.jpg">
    <div class="info bottom">
      <h1>The Comedy Bunch Live</h1>
      <span class="title address">Boom Room, Open Air Mall, Madinaty</span>
      <p><i class="fa-solid fa-circle" style="color: #03b300;"></i> Price: 350L.E</p>
      <dl>
        <dt>Date</dt>
        <dd>15 Oct</dd>
        <dt>Time</dt>
        <dd>9:00pm</dd>
      </dl>
    </div>
  </div>
  <div class="side back">
    <div class="top">
      <div class="span">
        <h2>Organized By</h2>
        <span>Boom Room</span>
      </div>
      <div class="span">
        <h2>Location</h2>
        <span>Boom Room, Open Air Mall, Madinaty</span>
        <p>Open Air Mall, parking Gate 3, Madinaty, Cairo</p>
      </div>
    </div>
    
    <div class="payment bottom">
     <button class="Book"><i class="fa-solid fa-ticket"></i> Book Now</button>
    </div>
  </div>
</div>
</div>
<!-- Row 1-->
<div class="row-tick">
    <!--Ticket 1-->
<div class="ticket">
  <div class="side front">
    <img src="../images/event6.jpg">
    <div class="info bottom">
      <h1>Memo</h1>
      <span class="title address">Grand Nile Tower</span>
      <p><i class="fa-solid fa-circle" style="color: #03b300;"></i> Price Range: 500L.E - 2,500L.E</p>
      <dl>
        <dt>Date</dt>
        <dd>17 Oct</dd>
        <dt>Time</dt>
        <dd>9:00pm</dd>
      </dl>
    </div>
  </div>
  <div class="side back">
    <div class="top">
      <div class="span">
        <h2>Organized By</h2>
        <span>Craft Media</span>
      </div>
      <div class="span">
        <h2>Location</h2>
        <span>Grand Nile Tower</span>
        <p>Abdulaziz Al Saud, Old Cairo, Cairo</p>
      </div>
    </div>
    
    <div class="payment bottom">
     <button class="Book"><i class="fa-solid fa-ticket"></i> Book Now</button>
    </div>
  </div>
  
</div>
<!--Ticket 2-->
<div class="ticket">
  <div class="side front">
    <img src="../images/event4.jpg">
    <div class="info bottom">
      <h1>Sa7ebna</h1>
      <span class="title address">AUC Tahrir, Falaki Theater</span>
      <p><i class="fa-solid fa-circle" style="color: #03b300;"></i> Price: 280L.E</p>
      <dl>
        <dt>Date</dt>
        <dd>17 Oct</dd>
        <dt>Time</dt>
        <dd>8:00pm</dd>
      </dl>
    </div>
  </div>
  <div class="side back">
    <div class="top">
      <div class="span">
        <h2>Organized By</h2>
        <span>Act Theater Group</span>
      </div>
      <div class="span">
        <h2>Location</h2>
        <span>AUC Tahrir, Falaki Theater</span>
        <p>Al Falaki, Abdeen, Cairo Governorate</p>
      </div>
    </div>
    
    <div class="payment bottom">
     <button class="Book"><i class="fa-solid fa-ticket"></i> Book Now</button>
    </div>
  </div>
</div>
<!--Ticket 3-->
<div class="ticket">
  <div class="side front">
    <img src="../images/event5.png">
    <div class="info bottom">
      <h1>The Comedy Bunch Live</h1>
      <span class="title address">Boom Room, Open Air Mall, Madinaty</span>
      <p><i class="fa-solid fa-circle" style="color: #03b300;"></i> Price: 350L.E</p>
      <dl>
        <dt>Date</dt>
        <dd>15 Oct</dd>
        <dt>Time</dt>
        <dd>9:00pm</dd>
      </dl>
    </div>
  </div>
  <div class="side back">
    <div class="top">
      <div class="span">
        <h2>Organized By</h2>
        <span>Boom Room</span>
      </div>
      <div class="span">
        <h2>Location</h2>
        <span>Boom Room, Open Air Mall, Madinaty</span>
        <p>Open Air Mall, parking Gate 3, Madinaty, Cairo</p>
      </div>
    </div>
    
    <div class="payment bottom">
     <button class="Book"><i class="fa-solid fa-ticket"></i> Book Now</button>
    </div>
  </div>
</div>
</div>  
<script src="../../../public/js/Events.js"></script>
</body>
</html>
<?php include "../Components/Footer.php"?>