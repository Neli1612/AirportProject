<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: emp_index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Varna Airport</title>
    <link rel="stylesheet" href="emp_homepage.css">
</head>
<body>

    
    <header>
        <div class="logo">
            <a href="emp_homepage.php"><h1>Varna Airport</h1></a>
        </div>
        <nav>
            <ul>
                <li><a href="logout.php">Log out</a></li>
                
            </ul>
        </nav>
    </header>

    <div class="sidenav">
      <!--<a href="emp_homepage.html">Home</a>-->
      <!--<a href="#">Personal info</a>-->
      <a href="#" onclick="loadTable('airline')">Airline</a>
    <a href="#" onclick="loadTable('arrivalflights')">Arrival Flights</a>
    <a href="#" onclick="loadTable('arrivals')">Arrivals</a>
    <a href="#" onclick="loadTable('departureflights')">Departure Flights</a>
    <a href="#" onclick="loadTable('departures')">Departures</a>
    <a href="#" onclick="loadTable('destination')">Destination</a>
    <a href="#" onclick="loadTable('flightstatus')">Flight Status</a>
    <a href="#" onclick="loadTable('contacts')">Contacts</a>
    <a href="#" onclick="loadTable('employee')">Employees</a>
      
   </div>


   <div class="table" id="table">
        <h2 style="margin-left:150px">Welcome, <?= htmlspecialchars($_SESSION['emp_name']) ?></h2>
        <div class="fly">
          <h2>Fly high!</h2>
        </div>
        <img src="images/plane.webp">
        
   </div>

   <div id="popupForm" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeForm()">X</span>
    <h3 id="formTitle">Insert Record</h3>
    <form id="dataForm">
      <div id="formFieldsContainer">
      <div id="formFields"></div>
      </div>
      <input type="hidden" name="action" id="formAction">
      <input type="hidden" name="table" id="formTable">
      <div class="modal-buttons">
      <button type="submit">Save</button>
      <button type="button" class="cancel-btn" onclick="closeForm()">Cancel</button>
      </div>
    </form>
  </div>
 </div>
   
    <footer>
        <p>&copy; 2025 Varna Airport. All rights reserved.</p>
    </footer>

    <script>

      function closeForm() {
    document.getElementById('popupForm').style.display = 'none';
  }
        
        var currentTable = '';
        function loadTable(table) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_table.php?table=' + table, true);
       xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      document.getElementById('table').innerHTML = xhr.responseText;
      currentTable = table;
    }
  };
  xhr.send();
}

    function openForm(action, data) {
  document.getElementById('popupForm').style.display = 'block';
  document.getElementById('formTitle').innerHTML = action === 'insert' ? 'Insert Record' : 'Update Record';
  document.getElementById('formAction').value = action;
  document.getElementById('formTable').value = currentTable;

  var formFields = document.getElementById('formFields');
  formFields.innerHTML = '';

  var headers = document.querySelectorAll('.table table th');
  for (var i = 0; i < headers.length - 1; i++) {
    var key = headers[i].innerText;
    var value = data && data[key] ? data[key] : '';
    formFields.innerHTML += '<label>' + key + '</label><br><input type="text" name="' + key + '" value="' + value + '"><br><br>';
  }
}

function closeForm() {
  document.getElementById('popupForm').style.display = 'none';
}

document.getElementById('dataForm').onsubmit = function (e) {
  e.preventDefault();
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'actions.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  var inputs = this.querySelectorAll('input');
  var data = '';
  for (var i = 0; i < inputs.length; i++) {
    if (inputs[i].name) {
      data += encodeURIComponent(inputs[i].name) + '=' + encodeURIComponent(inputs[i].value) + '&';
    }
  }

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      closeForm();
      loadTable(currentTable);
    }
  };
  xhr.send(data);
};

function insertRow() {
  openForm('insert');
}

function editRow(btn) {
  var row = btn.parentNode.parentNode;
  var cells = row.getElementsByTagName('td');
  var headers = document.querySelectorAll('.table table th');
  var data = {};
  for (var i = 0; i < headers.length - 1; i++) {
    data[headers[i].innerText] = cells[i].innerText;
  }
  openForm('update', data);
}

  function deleteRow(btn) {
  if (!confirm('Delete this row?')) return;

  var row = btn.parentNode.parentNode;
  var pkField = row.getElementsByTagName('th')[0] 
                  ? row.getElementsByTagName('th')[0].innerText 
                  : row.parentNode.parentNode.querySelector('th').innerText;

  var pk = row.getElementsByTagName('td')[0].innerText;

  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'actions.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      loadTable(currentTable);
    }
  };
  xhr.send('action=delete&table=' + currentTable +
           '&pk=' + encodeURIComponent(pk) +
           '&pkField=' + encodeURIComponent(pkField));
}


    </script>



      <!--
function deleteRow(btn) {
  if (!confirm('Delete this row?')) return;
  var row = btn.parentNode.parentNode;
  var cells = row.getElementsByTagName('td');
  var pk = cells[0].innerText;

  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'actions.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      loadTable(currentTable);
    }
  };
  xhr.send('action=delete&table=' + currentTable + '&pk=' + encodeURIComponent(pk));
}








      function loadTable(tableName) {
          fetch('fetch_table.php?table=' + tableName)
        .then(response => response.text())
        .then(html => {
          document.getElementById('table').innerHTML = html;
        });
    }
       let currentTable = "";
      function loadTable(tableName) {
        currentTable = tableName;
      const xhr = new XMLHttpRequest();
      xhr.open("GET", "load_table.php?table=" + tableName, true);
      xhr.onload = function () {
      if (xhr.status === 200) {
       document.querySelector(".table").innerHTML =` 
       <button onclick="showInsertForm('${tableName}')">Add New</button>
       ${xhr.responseText}`;
      } else {
       document.querySelector(".table").innerHTML = "Error loading table.";
      }
     };
     xhr.send();
     }

     function showInsertForm(tableName) {
    const formDiv = document.querySelector(".table");
    const formHtml = `
        <form id="insertForm">
            <div id="formFields">Loading form fields...</div>
            <button type="submit">Insert</button>
        </form>
    `;
    formDiv.innerHTML = formHtml;

    fetch("get_table_columns.php?table=" + tableName)
        .then(res => res.json())
        .then(columns => {
            let inputs = '';
            columns.forEach(col => {
                if (col !== 'id') { // ако 'id' e auto_increment
                    inputs += `<label>${col}: <input name="${col}" required></label><br>`;
                }
            });
            document.getElementById("formFields").innerHTML = inputs;
        });

    document.getElementById("insertForm").onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("table", tableName);
        console.log([...formData]);
        fetch("insert.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(result => {
            alert(result);
            loadTable(tableName);
        });
    };
}

    //Updаte
    function editRow(row) {
    const cells = row.querySelectorAll("td");
    const headers = row.closest("table").querySelectorAll("th");
    let data = {};

    for (let i = 0; i < headers.length - 1; i++) { // -1 за да игнорираш "Actions"
        const key = headers[i].innerText.trim();
        const value = cells[i].innerText.trim();
        data[key] = value;
    }

    // Създаване на формата
    let form = '<form id="editForm">';
    for (let key in data) {
        const readonly = key.toLowerCase().includes("id") ? 'readonly' : '';
        form += `<label>${key}: <input name="${key}" value="${data[key]}" ${readonly}></label><br>`;
    }
    form += `<button type="submit">Update</button>`;
    form += `</form>`;

    document.querySelector(".table").innerHTML = form;

    // Submit обработка
    document.getElementById("editForm").onsubmit = function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("table", currentTable); // или подай по друг начин името на таблицата

        fetch("update.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            loadTable(currentTable); // презарежда таблицата
        });
    };
}

   //Delete
   function deleteRow(id) {
    if (!confirm("Are you sure you want to delete this record?")) return;

    const formData = new FormData();
    formData.append("id", id);
    formData.append("table", currentTable);

    fetch("delete.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        loadTable(currentTable); // презарежда таблицата
    });
}-->



</body>
</html>