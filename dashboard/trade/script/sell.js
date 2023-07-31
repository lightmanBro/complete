//Cut the sell
// import { sell_tableBody } from "./script";
const sell_tableBody = document.querySelector(".sell_other_methods")
function loadData2(method, url) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      const datas = JSON.parse(this.response);
      console.log(datas['wallet']);
      datas['wallet'].forEach((data) => {
        if (data.payment_method == "Cedi") {
          const byWallet = `<tr>
          <td>
            <div class="card-info1">
            <input type="tel" value="${data.user_id}" hidden>
            <input type="tel" id="trade_ind" value="${data.ind}" hidden>
              <h3>${data.user_name} ID <span id="id">${data.user_id}</span></h3>
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
                <button class="btn2 sell_btn" id="modal-btn_sell" style="background-color: var(--secondary);"> Sell GHC</button>
            </div>
          </td>
        </tr>`;
          document
            .querySelector("#sellByWallet")
            .insertAdjacentHTML("afterbegin", byWallet);
        }
        if (data.payment_method == "Dollar") {
          const byWallet = `<tr>
          <td>
            <div class="card-info1">
            <input type="tel" value="${data.user_id}" hidden>
            <input type="tel" id="trade_ind" value="${data.ind}" hidden>
              <h3>${data.user_name} ID <span id="id">${data.user_id}</span></h3>
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
              <button class="btn2 sell_btn" id="modal-btn_sell" style="background-color: var(--secondary);"> Sell USD</button>
            </div>
          </td>
        </tr>`;
          document
            .querySelector("#sellByWallet")
            .insertAdjacentHTML("afterbegin", byWallet);
        }
        if (data.payment_method == "Rand") {
          const byWallet = `<tr>
          <td>
            <div class="card-info1">
            <input type="tel" value="${data.user_id}" hidden>
            <input type="tel" id="trade_ind" value="${data.ind}" hidden>
              <h3>${data.user_name} ID <span id="id">${data.user_id}</span></h3>
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
              <button class="btn2 sell_btn" id="modal-btn_sell" style="background-color: var(--secondary);"> Sell ZAR</button>
            </div>
          </td>
        </tr>`;
          document.querySelector("#sellByWallet").innerHTML += byWallet;
        }
        if (data.payment_method == "Naira") {
          const byWallet = `<tr>
          <td>
            <div class="card-info1">
            <input type="tel" value="${data.user_id}" hidden>
            <input type="tel" id="trade_ind" value="${data.ind}" hidden>
              <h3>${data.user_name} ID <span id="id">${data.user_id}</span></h3>
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
          <h3 class="wallet"> 
          <span class="exchange_rate">${data.user_rate}</span>  
          <span class="seller_wallet">${data.wallet}
          </span><i class="fa-solid fa-right-left"></i>
          1<span class="my_wallet">${data.payment_method}</span></h3>
        </div>
          </td>
          <td>
            <div class="card-buy">
              <button class="btn2 sell_btn" id="modal-btn_sell" style="background-color: var(--secondary);"> Sell NGN</button>
            </div>
          </td>
        </tr>`;
          document
            .querySelector("#sellByWallet")
            .insertAdjacentHTML("afterbegin", byWallet);
        }
        /*
        End of payment by wallet        
        */
      });
      datas['other'].forEach(other_method=>{
        console.log(datas['other']);
        const html = `<tr>
        <td>
          <div class="card-info1">
          <input type="tel" value="${other_method.user_id}" hidden>
            <h3> ${other_method.user_name} -- <span id="id">${other_method.user_id}</span></h3>
    
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
          <h3 class="limit">Limit: <span>${other_method.lowest_rate}</span> - <span>${other_method.highest_rate}</span></h3>    
          </div>
        </td>
        <td>
          <div class="card-info2">
            <h3 class="wallet">
            1<span class="seller_wallet">${other_method.wallet}</span><i class="fa-solid fa-right-left"></i>
            <span class="exchange_rate">${other_method.user_rate}</span> 
            <span class="my_wallet">${other_method.wallet_to}</span></h3>
          </div>
        </td>
        <td>
            <div class="methods">
            <span><b class="payment_method">${other_method.payment_method}</b> </span>
            </div>
        </td>
        <td>
          <div class="card-buy">
            <button class="btn2" id="modal-btn_sell_other" style="background-color: var(--secondary);"> Sell ${other_method.wallet}</button>
          </div>
        </td>
      </tr>`;
        sell_tableBody.innerHTML += html;
      })
    };
    xhr.send();
  }
  
  //////////////SELL POST//////////////
  const method_sell = "POST",
    url_sell = "../php/order_sell.php";
  loadData2(method_sell, url_sell);
  