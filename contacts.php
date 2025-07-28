<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperBank</title>
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
      <li><a href="#">Transport</a>
        <ul>
          <li><a href="transport.html">To and from airport</a></li>
          <li><a href="carRental.html">Car Rental</a></li>
          <li><a href="parking.html">Parking</a></li>
        </ul>
      </li>
      <li><a href="#">Airport guide</a>
        <ul>
          <li><a href="shops.html">Shops</a></li>
          <li><a href="eat&drink.html">Eat&Drink</a></li>
          <li><a href="airportMap.html">Airport map</a></li>
        </ul>
      </li>
      <li><a href="#">Passenger info</a>
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
      <li id="langSelect"><a href="#">Language</a>
        
      </li>
    </ul>
  </nav>
    </header>


<div id="div1">
    <div class="contact" id="info">
        <h2>Contacts</h2>
        <p>Connect with us for more information.</p>
        <ul>
            <li>Phone: 089 123 456</li>
            <li>Email: info@superBank.bg</li>
            <li>Address: Varna, Levski Bul. 123 </li>
            <li>Working time: Monday-Friday 08:00-18:00</li>
            </li>
        </ul>
    </div>

    <div class="contact" >
            
            <form id="contactForm">
                <label for="fullname">Full name:</label><br>
                <input type="text" name="fullname"><br>
                <label for="phone">Phone number:</label><br>
                <input type="tel" name="phone"><br>
                <label for="email">Email:</label><br>
                <input type="email" name="email"><br>
                <label for="message">Message:</label><br>
                <textarea name="message" rows="10" cols="30"></textarea><br>
                <label for="mdate">Date:</label><br>
                <input type="date" name="mdate"><br>    
                <input type="submit" value="Send" id="sendbtn">
            </form>
    </div>


    <div id="myModal" class="modal">

  <!-- Modal content -->
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


</div>
    <footer>
        <p>&copy; 2025 Varna Airport. All rights reserved.</p>
    </footer>



</body>
</html>