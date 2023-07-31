var thisuserID = 0; //initializing the userId variable.
console.log(sessionStorage.getItem("Dollar"));
//Retrieve the payment methods the poster adds to his trades and paste it on individual trade modal boxes

 const buy_tableBody = document.querySelector(".buy_other_methods"),
 popContainer = document.querySelector("popup-container4");

// console.log(document.querySelector(".buy_other_methods").innerHTML)
function loadData(method, url) {
  //Use javascript to set the attribute of the form, both the me
  /**/
  const xhr = new XMLHttpRequest();
  xhr.open(method, url);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  //The floating container form HTML string.
    xhr.onload = function () {
    const datas = JSON.parse(this.response);

    //Retrieving the current user id and checking it against the trades id to prevent user from interracting with the trade they post.
    thisuserID = datas["three"];
    console.log(datas);
    console.log(datas["three"]);
    //Looping through the trade data and posting it on the trade page
    datas.one.forEach((data) => {
      // Putting the trades data inside the container by their method;
      if (data.payment_method == "Naira") {
        const buy_order_wallet = `<tr>
        <td>
          <div class="card-info1">
          <input type="tel" value="${data.user_id}" hidden>
          <input type="tel" id="trade_ind" value="${data.ind}" hidden>
          <div class="card-info1">

          <h3> ${data.user_name} Id<span id="id"> ${data.user_id} </span></h3>

          </div>
        </td>
        <td>
          <div class="card-info2">
            <h3>Trade(s): <span>
                <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i>
              </span></h3>

          </div>
        </td>
        <td>
          <div class="card-info2">
          <h3 class="limit">Limit: <span>${data.lowest_rate}</span> - <span>${data.highest_rate}</span></h3>
          </div>
        </td>
        <td>
          <div class="card-info2">
          <h3 class="wallet"> <span class="exchange_rate">${data.user_rate}</span>  <span class="seller_wallet">${data.wallet}
          </span><i class="fa-solid fa-right-left"></i>1<span class="my_wallet">${data.payment_method}</span></h3>
          </div>
        </td>
        <td>
          <div class="card-buy">
            <button class="btn" id="modal-btn"> Buy ${data.wallet}</button>
          </div>
        </td>
      </tr>`;
        document
          .querySelector("#PayByWallet")
          .insertAdjacentHTML("afterbegin", buy_order_wallet);
      }
      else if (data.payment_method == "Dollar") {
        const buy_order_wallet = `<tr>
        <td>
          <div class="card-info1">
          <input type="tel" value="${data.user_id}" hidden>
          <input type="tel" id="trade_ind" value="${data.ind}" hidden>
          <div class="card-info1">

          <h3> ${data.user_name} Id<span id="id"> ${data.user_id} </span></h3>

          </div>
        </td>
        <td>
          <div class="card-info2">
            <h3>Trade(s): <span>
                <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i>
              </span></h3>

          </div>
        </td>
        <td>
          <div class="card-info2">
          <h3 class="limit">Limit: <span>${data.lowest_rate}</span> - <span>${data.highest_rate}</span></h3>
          </div>
        </td>
        <td>
          <div class="card-info2">
          <h3 class="wallet"> <span class="exchange_rate">${data.user_rate}</span>  <span class="seller_wallet">${data.wallet}
          </span><i class="fa-solid fa-right-left"></i>1<span class="my_wallet">${data.payment_method}</span></h3>
          </div>
        </td>
        <td>
          <div class="card-buy">
          <button class="btn" id="modal-btn"> Buy ${data.wallet}</button>
          </div>
        </td>
      </tr>`;
        document
          .querySelector("#PayByWallet")
          .insertAdjacentHTML("afterbegin", buy_order_wallet);
      }
      else if (data.payment_method == "Rand") {
        const buy_order_wallet = `<tr>
        <td>
          <div class="card-info1">
          <input type="tel" value="${data.user_id}" hidden>
          <input type="tel" id="trade_ind" value="${data.ind}" hidden>
          <div class="card-info1">

          <h3> ${data.user_name} Id<span id="id"> ${data.user_id} </span></h3>

          </div>
        </td>
        <td>
          <div class="card-info2">
            <h3>Trade(s): <span>
                <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i>
              </span></h3>

          </div>
        </td>
        <td>
          <div class="card-info2">
          <h3 class="limit">Limit: <span>${data.lowest_rate}</span> - <span>${data.highest_rate}</span></h3>
          </div>
        </td>
        <td>
          <div class="card-info2">
          <h3 class="wallet"> <span class="exchange_rate">${data.user_rate}</span>  <span class="seller_wallet">${data.wallet}
          </span><i class="fa-solid fa-right-left"></i>1<span class="my_wallet">${data.payment_method}</span></h3>
          </div>
        </td>
        <td>
          <div class="card-buy">
          <button class="btn" id="modal-btn"> Buy ${data.wallet}</button>
          </div>
        </td>
      </tr>`;
        document
          .querySelector("#PayByWallet")
          .insertAdjacentHTML("afterbegin", buy_order_wallet);
      }
      else if (data.payment_method == "Cedi") {
        const buy_order_wallet = `<tr>
        <td>
          <div class="card-info1">
          <input type="tel" value="${data.user_id}" hidden>
          <input type="tel" id="trade_ind" value="${data.ind}" hidden>
          <div class="card-info1">

          <h3> ${data.user_name} Id<span id="id"> ${data.user_id} </span></h3>

          </div>
        </td>
        <td>
          <div class="card-info2">
            <h3>Trade(s): <span>
                <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i>
              </span></h3>

          </div>
        </td>
        <td>
          <div class="card-info2">
          <h3 class="limit">Limit: <span>${data.lowest_rate}</span> - <span>${data.highest_rate}</span></h3>
          </div>
        </td>
        <td>
          <div class="card-info2">
          <h3 class="wallet"> <span class="exchange_rate">${data.user_rate}</span>  <span class="seller_wallet">${data.wallet}
          </span><i class="fa-solid fa-right-left"></i>1<span class="my_wallet">${data.payment_method}</span></h3>
          </div>
        </td>
        <td>
          <div class="card-buy">
          <button class="btn" id="modal-btn"> Buy ${data.wallet}</button>
          </div>
        </td>
      </tr>`;
        document
          .querySelector("#PayByWallet")
          .insertAdjacentHTML("afterbegin", buy_order_wallet);
      }
      else{
        datas.other.forEach(other_method=>{
          console.log(datas.other);
          const html = `<tr>
        <td>
          <div class="card-info1">
          <input type="tel" value="${other_method.user_id}" hidden>
          <input type="tel" id="trade_ind" value="${other_method.ind}" hidden>
            <h3> ${other_method.user_name} -- (${other_method.user_id})</h3>
    
          </div>
        </td>
        <td>
          <div class="card-info2">
            <h3>Trade(s): <span>
                <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                  class="fa-regular fa-star"></i>
              </span></h3>
    
          </div>
        </td>
        <td>
          <div class="card-info2">
            <h3>Limit: ${other_method.lowest_rate} - ${other_method.highest_rate}</h3>
    
          </div>
        </td>
        <td>
          <div class="card-info2">
            <h3>1${other_method.wallet}<i class="fa-solid fa-right-left"></i> ${other_method.user_rate}${other_method.wallet_to}</h3>
          </div>
        </td>
        <td>
            <div class="methods">
            <span><b>${other_method.payment_method}</b> </span>
            </div>
        </td>
        <td>
          <div class="card-buy">
            <button class="btn2" id="modal-btn_buy_other"> Buy ${other_method.wallet}</button>
          </div>
        </td>
      </tr>`;
      //Adding the html into the dom;
      buy_tableBody.innerHTML += html;
        })
      }
    });
  };


  /////////////////The function that handles the click on individual container///////////////////////////
  xhr.send();
}

///////////////BUY POST/////////////////////
const method_buy = "POST",
  url_buy = "../php/order_buy.php";
loadData(method_buy, url_buy);
/////////////////////////////////////
