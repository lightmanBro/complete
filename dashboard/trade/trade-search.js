const searchInput = document.querySelector('#content3 input[type="search"]');
console.log(searchInput);
searchInput.addEventListener('keyup',(e)=>{
    console.log(e.target);
    const limit =[];
    setTimeout(() => {
        limit.push(document.querySelectorAll(".buy_other_methods tr td .limit span")[0].innerHTML *1);
        if(limit.includes((e.target.value *1))){
            
        };
       
    }, 1000);
})

function showResult(str) {
    // if (str.length === 0) {
    //   document.getElementById("livesearch").innerHTML = "";
    //   document.getElementById("livesearch").style.border = "0px";
    //   return;
    // }
  
    fetch("../php/live-search.php?q=" + str)
      .then(function(response) {
        if (response.ok) {
          return response.text();
        } else {
          throw new Error("Error: " + response.status);
        }
      })
      .then(function(data) {
        // document.getElementById("livesearch").innerHTML = data;
        // document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
        console.log(data)
      })
      .catch(function(error) {
        console.log(error);
      });
  }
  