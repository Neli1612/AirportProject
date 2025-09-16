<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contasts</title>
    <link rel="stylesheet" href="contacts.css">
</head>
<body>

    
    <header>
        <div class="logo">Varna Airport</div>
  <nav>
    <ul>
      <li><a href="index.html">Home</a></li>
      <li>
        <a href="#">Flights ▾</a>
        <ul>
          <li><a href="arrivals.php">Arrivals</a></li>
          <li><a href="departures.php">Departures</a></li>
          
        </ul>
      </li>
      <li><a href="#">Transport ▾</a>
        <ul>
          <li><a href="transport.html">To and from airport</a></li>
          <li><a href="carRental.html">Car Rental</a></li>
          <li><a href="parking.html">Parking</a></li>
        </ul>
      </li>
      <li><a href="#">Airport guide ▾</a>
        <ul>
          <li><a href="shops.html">Shops</a></li>
          <li><a href="eat&drink.html">Eat&Drink</a></li>
          <li><a href="airportMap.html">Airport map</a></li>
          <li><a href="#">Services ▾</a>
            <ul>
          <li><a href="lost&found.html">Lost&Found</a></li>
          <li><a href="bank&atm.html">Bank&ATM</a></li>
          <li><a href="vip.html">VIP</a></li>
          <li><a href="med.html">Medical Services</a></li>
           </ul>
          </li>
        </ul>
      </li>
      <li><a href="#">Passenger info ▾</a>
        <ul>
          <li><a href="check-in.html">check-in</a></li>
          <li><a href="security.html">Security</a></li>
          <li><a href="passportControl.html">Passport Control</a></li>
          <li><a href="#">Special needs ▾</a>
            <ul>
          <li><a href="accessibleTravel.html">Accessible travel</a></li>
          <li><a href="travelWithKids.html">Travel with kids</a></li>
          <li><a href="travelWithPets.html">Travel with pets</a></li>
           </ul>
          </li>
        </ul>
      </li>
      <li><a href="contacts.php">Contacts</a></li>
        
      </li>
    </ul>
  </nav>
    </header>

 <h2>Contacts</h2>
  <div class="div1">

    

     <div class="info">
         
      <div class="card" style="margin-bottom:10px">
          <h3>Information</h3>
          <hr>
          <p>+359 52 573 323</p>
        </div>
       

        <div class="card">
           <h3>Security</h3>
          <hr>
          <p>+359 52 573 389<br>
             VNSEC01@varna-airport.com</p>
        </div>
      
          
        <div class="card">
          <h3>Baggage</h3>
          <hr>
          <p>+359 52 573 423<br>
            ll@varna-airport.bg</p>
        </div>

        <div class="card">
          <h3>Border police</h3>
          <hr>
          <p>+359 52 573 421<br>
           airp-gkpp-vn@mvr.bg</p>
        </div>
      </div> 
        

     <div class="contact" >
            
            <form id="contactForm">
                <label for="full_name">Full name:</label><br>
                <input type="text" name="full_name"><br>
                <label for="phone">Phone number:</label><br>
                <input type="tel" name="phone"><br>
                <label for="email">Email:</label><br>
                <input type="email" name="email"><br>
                <label for="messageText">Message:</label><br>
                <textarea name="messageText" rows="10" cols="30"></textarea><br>   
                <input type="submit" value="Send" id="sendbtn">
            </form>
    </div>
</div>

  <div id="myModal" class="modal">

     
    <div class="modal-content">
    <span class="close">&times;</span>
    <p>Your message is sent</p>
    </div>

  </div>


<script>
    document.getElementById("contactForm").addEventListener("submit", function(e) {
    e.preventDefault(); 

    const formData = new FormData(this);

    fetch('insert_contactTable.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("Your message is sent")) {
            document.getElementById("myModal").style.display = "block";
            this.reset(); 
        } else {
            alert("Грешка при изпращането: " + data);
        }
    })
    .catch(error => {
        alert("Грешка при заявката: " + error);
    });
});


var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>



    <footer>
        <p>&copy; 2025 Varna Airport. All rights reserved.</p>
    </footer>



</body>
</html>