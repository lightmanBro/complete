// code to toggle the button //
/*
1. Retrieve data from the database and use it here.
2. Active trades from the database will be displayed inside the active modal which can be cancelled.
3. Other trades will be shown here.

*/

function toggleButtons() {
  var button1 = document.getElementById("button1");
  var button2 = document.getElementById("button2");
  var body = document.getElementsByClassName("body2");

  button1.classList.toggle("active", function () {
    body.style.display = "none";
  });
  button2.classList.toggle("active");
}

// code to toggle the table //

window.addEventListener("load", function () {
  var tableContent = document.getElementsByClassName("table-content");
  var tableHeaders = document.getElementsByTagName("th");
  tableContent[0].classList.add("show");
  tableHeaders[0].classList.add("active");

  for (var i = 0; i < tableHeaders.length; i++) {
    tableHeaders[i].addEventListener("click", function () {
      var colIndex = Array.prototype.indexOf.call(tableHeaders, this);
      for (var j = 0; j < tableContent.length; j++) {
        if (j !== colIndex) {
          tableContent[j].classList.remove("show");
        }
      }
      for (var j = 0; j < tableHeaders.length; j++) {
        if (j !== colIndex) {
          tableHeaders[j].classList.remove("active");
        }
      }
      tableContent[colIndex].classList.toggle("show");
      tableHeaders[colIndex].classList.add("active");
    });
  }
});
