function showResult(str) {
    if (str.length === 0) {
      document.getElementById("livesearch").innerHTML = "";
      document.getElementById("livesearch").style.border = "0px";
      return;
    }
  
    fetch("livesearch.php?q=" + str)
      .then(function(response) {
        if (response.ok) {
          return response.text();
        } else {
          throw new Error("Error: " + response.status);
        }
      })
      .then(function(data) {
        document.getElementById("livesearch").innerHTML = data;
        document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
      })
      .catch(function(error) {
        console.log(error);
      });
  }
  