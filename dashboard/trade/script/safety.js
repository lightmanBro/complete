const naira = sessionStorage.getItem("Naira");
const dollar = sessionStorage.getItem("Dollar");
const rand = sessionStorage.getItem("Rand");
const cedi = sessionStorage.getItem("Cedi");
const pound = sessionStorage.getItem('Pound')

function removeFee($select, orderUnit) {
  let p2pfee = orderUnit * 0.008;
  //Based on the currency posted for p2p, remove the post p2pfee;
  if ($select == "Dollar" && orderUnit < 100) p2pfee = 0.5;
  if ($select == "Dollar" && orderUnit >= 100) p2pfee = 1;
  return p2pfee;
}

// let $response = removeFee("Dollar",80);

// console.log($response);

console.log(naira, dollar, rand, cedi);
//Closing the modal box
window.addEventListener("click", function (event) {
  if (
    event.target == document.querySelector("#popup-container2") ||
    event.target == document.querySelector("#popup-container5") ||
    event.target == document.querySelector(".popup-container") ||
    event.target == document.querySelector("#popup-container4")
  ) {
    document.querySelector("#popup-container2").style.display = "none"; // Hide the modal when clicking outside of it
    document.querySelector("#popup-container5").style.display = "none";
    document.querySelector(".popup-container").style.display = "none";
    document.querySelector("#popup-container4").style.display = "none";
    //Once the window is clicked the disabled should be false;
    amount_buy.disabled = false;
    document.querySelector("#buy_wallet_confirm").disabled = false;
    amount_sell.disabled = false;
    document.querySelector("#sell_wallet_confirm").disabled = false;
  }
});

// BUY BY WALLET
// creating a variable that will receive data later and the data will be used to complete the order exchange;
let my_Wallet_buy,
  seller_wallet_buy_id,
  seller_wallet_buy,
  high_limits,
  low_limits,
  buy_order_refrence;
const amount_buy = document.querySelector("#buy_wallet #amount"),
  buy_cancel = document.querySelector("#buy_wallet #buy_wallet_cancel");

setTimeout(() => {
  document.querySelectorAll("#modal-btn").forEach((button) => {
    button.addEventListener("click", (e) => {
      const container = e.target.parentElement.parentElement.parentElement;
      const td = container.querySelectorAll("td");
      seller_wallet_buy_id = td[0].querySelector(".card-info1 #id").innerText;
      buy_order_refrence = td[0].querySelector("#trade_ind").value;
      console.log(buy_order_refrence);

      //Disable the input box if the seller id and the buyer id is thesame.
      if (seller_wallet_buy_id * 1 == sessionStorage.getItem("UserId") * 1) {
        amount_buy.disabled = true;
        document.querySelector("#buy_wallet_confirm").disabled = true;
        //Create a message on top of the order that you cannot purchase the order
        console.log("Thesame id");
      }

      // console.log(seller_wallet_buy_id,sessionStorage.getItem('UserId'));

      //Getting the data from the individual box clicked
      low_limits = td[2].querySelectorAll(".limit span")[0].innerHTML;
      high_limits = td[2].querySelectorAll(".limit span")[1].innerHTML;
      my_Wallet_buy = td[3].querySelector(".wallet .my_wallet").innerHTML;
      const exchange_rate =
        td[3].querySelector(".wallet .exchange_rate").innerHTML * 1;
      seller_wallet_buy = td[3].querySelector(
        ".wallet .seller_wallet"
      ).innerHTML;
      console.log(my_Wallet_buy, seller_wallet_buy);
      document.querySelector(".popup-container").style.display = "block";

      //Mapping the data to the modal box innerHtml
      document.querySelector(
        "#buy_wallet #my_wallet p"
      ).innerHTML = `${my_Wallet_buy}`;
      document.querySelector(
        "#buy_wallet #low_limit p"
      ).innerHTML = `${low_limits}`;
      document.querySelector(
        "#buy_wallet #high_limit p"
      ).innerHTML = `${high_limits}`;
      const wallet_balance = (document.querySelector(
        "#buy_wallet #wallet_balance p"
      ).innerHTML = `<b>${my_Wallet_buy}</b> ${sessionStorage.getItem(
        `${my_Wallet_buy}`
      )}`);
      const modal_seller_wallet = (document.querySelector(
        "#buy_wallet #seller_wallet p"
      ).innerHTML = `<b>${seller_wallet_buy}</b> Wallet`);
      const purchase_cost = document.querySelector(
        "#buy_wallet #purchase_cost p"
      );
      const rate = (document.querySelector(
        "#buy_wallet #exchange_rate p"
      ).innerHTML = `${exchange_rate}${seller_wallet_buy} <b>=</b> 1${my_Wallet_buy}`);
      const fee = document.querySelector("#buy_wallet #transaction_fee p");
      const receive_amount = document.querySelector(
        "#buy_wallet #receive_amount p"
      );
      const confirmBtn = document.querySelector(
        "#buy_wallet #buy_wallet_confirm"
      );
      console.log(exchange_rate);

      amount_buy.addEventListener("input", (e) => {
        //Checking ans sanitizing the input values
        const inputValue = e.target.value.trim();
        confirmBtn.disabled = false;
        confirmBtn.style.backgroundColor = "rgb(46, 204, 113)";
        if (!isNaN(inputValue)) {
          purchase_cost.innerHTML = e.target.value * 1 * exchange_rate;
          fee.innerHTML = purchase_cost.innerHTML * 1 * 0.008;
          receive_amount.innerHTML = (
            purchase_cost.innerHTML * 1 -
            fee.innerHTML * 1
          ).toFixed(2);
        } else {
          confirmBtn.disabled = true;
          confirmBtn.style.backgroundColor = "red";
        }
        // console.log(e.target.value);
      });

      buy_cancel.addEventListener("click", () => {
        purchase_cost.innerHTML = "";
        fee.innerHTML = "";
        receive_amount.innerHTML = "";
      });
    });
  });
}, 1000);

document.querySelector("#buy_wallet_confirm").addEventListener("click", (e) => {
  e.preventDefault();
  let purchaseCost = document.querySelector(
    "#buy_wallet #purchase_cost p"
  ).innerHTML;
  let transactionFee = document.querySelector(
    "#buy_wallet #transaction_fee p"
  ).innerHTML;
  let receiveAmount = document.querySelector(
    "#buy_wallet #receive_amount p"
  ).innerHTML;
  let orderUnit = document.querySelector("#buy_wallet #amount");
  // console.log(purchaseCost,transactionFee,receiveAmount,orderUnit.value);
  let buySuccess = document.querySelector('#buy_wallet #success');
  let buyFailed = document.querySelector('#buy_wallet #failed');
  e.target.disabled = false;

  if ((purchaseCost *1).toFixed(1) *1 < low_limits*1 ) {
    e.target.disabled = true;
    buyFailed.style.display = 'block';
    setTimeout(() => {
      buy_cancel.click();
    }, 500);
    buyFailed.innerHTML = "Purchase Cost is lower than the low limit";
    setTimeout(() => {
      buyFailed.style.display = 'none';
    }, 2500);
  }else if((purchaseCost *1).toFixed(1) *1 >high_limits*1){
    e.target.disabled = true;
    buyFailed.style.display = 'block';
    setTimeout(() => {
      buy_cancel.click();
    }, 500);
    buyFailed.innerHTML = "Purchase Cost is higher than the high limit";
    setTimeout(() => {
      buyFailed.style.display = 'none';
    }, 2500);
  } else if((purchaseCost *1).toFixed(2) *1 >= low_limits *1 || purchaseCost *1<=high_limits*1){
    //Checking the type of currency to determing the charges
    let checkFee = removeFee(seller_wallet_buy,purchaseCost);
    console.log(checkFee);
    e.target.disabled = false;
    const buyOrder = new FormData();
    buyOrder.append("Type", "buyWallet");
    buyOrder.append("Cost", purchaseCost);
    buyOrder.append("high_limit", high_limits);
    buyOrder.append("low_limit", low_limits);
    buyOrder.append("order_ref", buy_order_refrence);
    buyOrder.append("TransactionFee", checkFee);
    buyOrder.append("ReceiveAmount", receiveAmount * 1);
    buyOrder.append("SellerId", seller_wallet_buy_id);
    buyOrder.append("OrderUnit", orderUnit.value.trim() * 1);
    buyOrder.append("BuyerWallet", my_Wallet_buy);
    buyOrder.append("SellerWallet", seller_wallet_buy);
    const urlEncodedData = new URLSearchParams(buyOrder).toString();
    console.log(urlEncodedData);
    let url = "../php/exchange.php";
    let status = sendData(url, buyOrder);
    console.log(`Buy Order Status ${status}`);
    //Click the button to reset the form data to empty
    setTimeout(() => {
      buy_cancel.click();
    }, 500);

    //Display the inner container for receipt
    document.querySelector('#buyInnerpopup').style.display = 'block';
    setTimeout(() => {
      document.querySelector('#buyInnerpopup').style.display = 'none';
    }, 5000);

    //Display the success message
    let buySuccess = success(my_Wallet_buy,seller_wallet_buy,purchaseCost,receiveAmount,buy_order_refrence,checkFee);
    document.querySelector('#buyReceipt').insertAdjacentHTML('afterbegin',buySuccess);
  }
});

let sellerId, seller_wallet, my_Wallet, sell_order_refrence;
// SELL BY WALLET
const amount_sell = document.querySelector("#sell_wallet #amount"),
  sell_cancel = document.querySelector("#sell_wallet #sell_wallet_cancel"),
  wallet_balance = document.querySelector("#sell_wallet #wallet_balance p"),
  modal_seller_wallet = document.querySelector("#sell_wallet #seller_wallet p"),
  purchase_cost = document.querySelector("#sell_wallet #purchase_cost p"),
  rate = document.querySelector("#sell_wallet #exchange_rate p"),
  fee = document.querySelector("#sell_wallet #transaction_fee p"),
  receive_amount = document.querySelector("#sell_wallet #receive_amount p"),
  confirmBtn = document.querySelector("#sell_wallet #sell_wallet_confirm");

my_Wallet = document.querySelector("#sell_wallet #my_wallet p");
let low_limit = document.querySelector("#sell_wallet #low_limit p"),
  high_limit = document.querySelector("#sell_wallet #high_limit p");

setTimeout(() => {
  document.querySelectorAll("#modal-btn_sell").forEach((button) => {
    button.addEventListener("click", (e) => {
      console.log(e.target.parentElement.parentElement.parentElement);
      document.querySelector("#popup-container2").style.display = "block";

      const container = e.target.parentElement.parentElement.parentElement;
      const td = container.querySelectorAll("td");
      sellerId = td[0].querySelector(".card-info1 #id").innerText;
      sell_order_refrence = td[0].querySelector("#trade_ind").value;
      console.log(sell_order_refrence);
      //Disable the input box if the seller id and the buyer id is thesame.
      if (sellerId == sessionStorage.getItem("UserId")) {
        amount_sell.disabled = true;
        document.querySelector("#sell_wallet_confirm").disabled = true;
      }

      console.log(td[2], td[0], sellerId);
      const low_limits = td[2].querySelectorAll(".limit span")[0].innerHTML,
        high_limits = td[2].querySelectorAll(".limit span")[1].innerHTML;
      const my_wallet = td[3].querySelector(".wallet .my_wallet").innerHTML;
      const exchange_rate =
        td[3].querySelector(".wallet .exchange_rate").innerHTML * 1;
      seller_wallet = td[3].querySelector(".wallet .seller_wallet").innerHTML;
      console.log(
        high_limits,
        low_limits,
        exchange_rate,
        seller_wallet,
        my_wallet
      );

      my_Wallet.innerHTML = `${my_wallet}`;
      low_limit.innerHTML = `${low_limits}`;
      high_limit.innerHTML = `${high_limits}`;
      wallet_balance.innerHTML = `<b>${my_wallet}</b> ${sessionStorage.getItem(
        `${my_wallet}`
      )}`;
      modal_seller_wallet.innerHTML = `<b>${seller_wallet}</b> Wallet`;
      rate.innerHTML = `${exchange_rate}${seller_wallet} <b>=</b> 1${my_wallet}`;

      //Check the input values if its a number
      amount_sell.addEventListener("input", (e) => {
        console.log(e.target.value * 1 * exchange_rate);
        const inputValue = e.target.value.trim();
        confirmBtn.disabled = false;
        confirmBtn.style.backgroundColor = "rgb(46, 204, 113)";

        if (!isNaN(inputValue)) {
          console.log("value is a number");
          //Calculations for the trade and values;
          purchase_cost.innerHTML = e.target.value * 1 * exchange_rate;
          fee.innerHTML = purchase_cost.innerHTML * 1 * 0.008;
          receive_amount.innerHTML = (
            purchase_cost.innerHTML * 1 -
            fee.innerHTML * 1
          ).toFixed(2);
        } else {
          confirmBtn.disabled = true;
          confirmBtn.style.backgroundColor = "red";
        }
      });

      sell_cancel.addEventListener("click", () => {
        purchase_cost.innerHTML = "";
        fee.innerHTML = "";
        receive_amount.innerHTML = "";
      });
    });
  });
}, 1000);

document
  .querySelector("#sell_wallet_confirm")
  .addEventListener("click", (e) => {
    e.preventDefault();
    let purchaseCost = document.querySelector(
      "#sell_wallet #purchase_cost p"
    ).innerHTML;
    let sellSuccess = document.querySelector('#sell_wallet #success');
    let sellFailed = document.querySelector('#sell_wallet #failed');
    // let transactionFee = document.querySelector(
    //   "#sell_wallet #transaction_fee p"
    // ).innerHTML;
    let receiveAmount = document.querySelector(
      "#sell_wallet #receive_amount p"
    ).innerHTML;
    let orderUnit = document.querySelector("#sell_wallet #amount");
    
    e.target.disabled = false;
    if (
      low_limit.innerHTML * 1 > purchaseCost * 1
    ) {
      e.target.disabled = true;
      sellFailed.style.display = 'block';
      sellFailed.innerHTML ="Purchase Cost is lower than the low limit";
      setTimeout(() => {
        sell_cancel.click();
      }, 500);
      setTimeout(() => {
        sellFailed.style.display = 'none'
      }, 2500);
    }else if(purchaseCost*1 > high_limit.innerHTML*1){
      sellFailed.style.display = 'block';
      sellFailed.innerHTML = 'Purchase cost is higher than the high limit Please set a purchase cost that will be lower or equal to the high limit';
      e.target.disabled = true;
      setTimeout(() => {
        sell_cancel.click();
      }, 500);
      setTimeout(() => {
        sellFailed.style.display = 'none'
      }, 2500);
    } else if(purchaseCost*1 >= low_limit.innerHTML*1 || purchaseCost*1 <= high_limit.innerHTML*1){
      let checkFee = removeFee(seller_wallet_buy,purchaseCost);
      console.log(checkFee);

      const sellOrder = new FormData();
      sellOrder.append("Type", "sellWallet");
      sellOrder.append("order_ref", sell_order_refrence);
      sellOrder.append("Cost", purchaseCost);
      sellOrder.append("high_limit", high_limit.innerHTML);
      sellOrder.append("low_limit", low_limit.innerHTML);
      sellOrder.append("TransactionFee", checkFee * 1);
      sellOrder.append("ReceiveAmount", receiveAmount * 1);
      sellOrder.append("buyerId", sellerId);
      sellOrder.append("OrderUnit", orderUnit.value.trim() * 1);
      sellOrder.append("BuyerWallet", my_Wallet.innerHTML);
      sellOrder.append("SellerWallet", seller_wallet);
      const urlEncodedData = new URLSearchParams(sellOrder).toString();
      console.log(urlEncodedData);
      let url = "../php/exchange.php";
      sendData(url, sellOrder);
      setTimeout(() => {
        sell_cancel.click();
      }, 500);

      document.querySelector('#sellInnerpopup').style.display = 'block';
      setTimeout(() => {
        document.querySelector('#sellInnerpopup').style.display = 'none';
      }, 5000);

      let sentSuccess = success(my_Wallet.innerHTML,seller_wallet,purchaseCost,receiveAmount,sell_order_refrence,checkFee);
      document.querySelector('#sellReceipt').insertAdjacentHTML('afterbegin',sentSuccess);
    }
  });


  
let amount_buy_other = document.querySelector("#buy_other #amount"),
  buy_cancel_other = document.querySelector(
    "#buy_other #buy_other_cancel"
  ),
  wallet_balance_buy_other = document.querySelector(
    "#buy_other #wallet_balance p"
  ),
  modal_buyer_wallet_other = document.querySelector(
    "#buy_other #buyer_wallet p"
  ),
  purchase_cost_buy_other = document.querySelector(
    "#buy_other #purchase_cost p"
  ),
  rate_buy_other = document.querySelector("#buy_other #exchange_rate p"),
  fee_buy_other = document.querySelector(
    "#buy_other #transaction_fee p"
  ),
  receive_amount_buy_other = document.querySelector(
    "#buy_other #receive_amount p"
  ),
  confirmBtn_buy_other = document.querySelector(
    "#buy_other #buy_other_confirm"
  );

let my_Wallet_buy_other = document.querySelector(
    "#buy_other #my_wallet p"
  ),
  low_limit_buy_other = document.querySelector(
    "#buy_other #low_limit p"
  ),
  high_limit_buy_other = document.querySelector(
    "#buy_other #high_limit p"
  ),
  buyerId_other,
  buyer_wallet,
  order_ref,
  payment_method;

setTimeout(() => {
  document.querySelectorAll("#modal-btn_buy_other").forEach((button) => {
    button.addEventListener("click", (e) => {
     
      document.querySelector("#popup-container4").style.display = "block";
      const container = e.target.parentElement.parentElement.parentElement;
      const td = container.querySelectorAll("td");
      console.log(container);

      buyerId_other = td[0].querySelector("#id").value,
      order_ref = td[0].querySelector("#trade_ind").value;
      const low_limits = td[2].querySelectorAll(".limit span")[0].innerHTML,
        high_limit = td[2].querySelectorAll(".limit span")[1].innerHTML;
      const my_wallet = td[3].querySelector(".wallet .my_wallet").innerHTML;
      const exchange_rate =
        td[3].querySelector(".wallet .exchange_rate").innerHTML * 1;
      buyer_wallet = td[3].querySelector(".wallet .buyer_wallet").innerHTML;
      payment_method = td[4].querySelector(
        ".methods span .payment_method"
      ).innerHTML;

      //Mapping to the modal box inner
      modal_buyer_wallet_other.innerHTML = `<b>${buyer_wallet}</b>`;
      my_Wallet_buy_other.innerHTML = `${my_wallet} Wallet`;
      wallet_balance_buy_other.innerHTML = `<b>${my_wallet}</b> ${sessionStorage.getItem(`${my_wallet}`)}`;
      rate_buy_other.innerHTML = `${exchange_rate} ${ modal_buyer_wallet_other.innerHTML}<b>=</b> 1${my_wallet}`;
      low_limit_buy_other.innerHTML = `${low_limits} ${buyer_wallet}`;
      high_limit_buy_other.innerHTML = `${high_limit} ${buyer_wallet}`;

      amount_buy_other.addEventListener("input", (e) => {
        const inputValue = e.target.value.trim();
        console.log(e.target.value);
        confirmBtn_buy_other.disabled = false;
        confirmBtn_buy_other.style.backgroundColor = "rgb(46, 204, 113)";
        if (!isNaN(inputValue)) {
          console.log("value is a number");
          //Calculations for the trade and values;
          purchase_cost_buy_other.innerHTML =e.target.value * 1 * exchange_rate*1;
          fee_buy_other.innerHTML = (purchase_cost_buy_other.innerHTML * 1 * 0.008).toFixed(2);
          receive_amount_buy_other.innerHTML = (purchase_cost_buy_other.innerHTML *1 - fee_buy_other.innerHTML*1).toFixed(3);

          console.log(receive_amount_buy_other.innerHTML);         
        } else {
          confirmBtn_buy_other.disabled = true;
          confirmBtn_buy_other.style.backgroundColor = "red";
        }
      });

      //Displaying the Payment method inside the modal box instruction for buyer
      document.querySelector('#buyMethodDisplay').innerHTML = `<b>${payment_method}</b>`.toUpperCase();
      let methods = new FormData();
      // console.log(low_limits, order_ref,payment_method,buyerId_other);
      methods.append('sellerId',buyerId_other);
      methods.append('method',payment_method);
      let url = "../php/payment-methods-trade.php";
      getPaymentMethodDetails(url, methods);
      /*

      */
    });
  });
}, 1000);

confirmBtn_buy_other.addEventListener('click',(e)=>{
  e.preventDefault();
  console.log(high_limit_buy_other.innerHTML.split(' ')[0]*1,purchase_cost_buy_other.textContent*1);
  if(amount_buy_other.value*1<low_limit_buy_other.innerHTML.split(' ')[0]*1){
    alert('Low limit higher than purchase cost')
  }else if(purchase_cost_buy_other.textContent*1> high_limit_buy_other.innerHTML.split(' ')[0]*1){
    alert('Purchase cost is grearter than the high limit');
  }else{
    `sellOrder.append("Type", "sellWallet");
    sellOrder.append("order_ref", sell_order_refrence);
    sellOrder.append("Cost", purchaseCost);
    sellOrder.append("high_limit", high_limit.innerHTML);
    sellOrder.append("low_limit", low_limit.innerHTML);
    sellOrder.append("TransactionFee", checkFee * 1);
    sellOrder.append("ReceiveAmount", receiveAmount * 1);
    sellOrder.append("buyerId", sellerId);
    sellOrder.append("OrderUnit", orderUnit.value.trim() * 1);
    sellOrder.append("BuyerWallet", my_Wallet.innerHTML);
    sellOrder.append("SellerWallet", seller_wallet);`
    const formData = new FormData()
    formData.append('Type',"buyOther");
    formData.append('method',payment_method);
    formData.append('order_ref',order_ref);
    formData.append('seller_id',buyerId_other);
    formData.append('OrderUnit',amount_buy_other.value*1);
    formData.append('SellerWallet',modal_buyer_wallet_other.innerHTML);
    formData.append('my_wallet',my_Wallet_buy_other.innerHTML.split(' ')[0]);
    formData.append('transaction_fee',fee_buy_other.innerHTML)
    formData.append('exchange_rate',rate_buy_other.innerHTML.split(' ')[0]*1);

    const urlEncodedData = new URLSearchParams(formData).toString();
    console.log(urlEncodedData);
    let url = "../php/placeOrder.php";
    sendData(url,formData);
  }
})

const amount_sell_other = document.querySelector("#sell_wallet_other #amount"),
  sell_cancel_other = document.querySelector(
    "#sell_wallet_other #sell_wallet_other_cancel"
  ),
  wallet_balance_other = document.querySelector(
    "#sell_wallet_other #wallet_balance p"
  ),
  modal_seller_wallet_other = document.querySelector(
    "#sell_wallet_other #seller_wallet p"
  ),
  purchase_cost_sell_other = document.querySelector(
    "#sell_wallet_other #purchase_cost p"
  ),
  rate_sell_other = document.querySelector(
    "#sell_wallet_other #exchange_rate p"
  ),
  fee_sell_other = document.querySelector(
    "#sell_wallet_other #transaction_fee p"
  ),
  receive_amount_sell_other = document.querySelector(
    "#sell_wallet_other #receive_amount p"
  ),
  confirmBtn_sell_other = document.querySelector(
    "#sell_wallet_other #sell_wallet_other_confirm"
  );

let my_Wallet_sell_other = document.querySelector(
    "#sell_wallet_other #my_wallet p"
  ),
  low_limit_sell_other = document.querySelector(
    "#sell_wallet_other #low_limit p"
  ),
  high_limit_sell_other = document.querySelector(
    "#sell_wallet_other #high_limit p"
  );
let order_index, sellerId_other;
setTimeout(() => {
  document.querySelectorAll("#modal-btn_sell_other").forEach((button) => {
    button.addEventListener("click", (e) => {
      document.querySelector("#popup-container5").style.display = "block";
      const container = e.target.parentElement.parentElement.parentElement;
      const td = container.querySelectorAll("td");
      console.log(container);
      (sellerId_other = td[0].querySelector(".card-info1 #id").innerText),
        (order_index = td[0].querySelector("#trade_ind").value);
      const low_limits = td[2].querySelectorAll(".limit span")[0].innerHTML,
        high_limit = td[2].querySelectorAll(".limit span")[1].innerHTML;
      const my_wallet = td[3].querySelector(".wallet .my_wallet").innerHTML;
      const exchange_rate =
        td[3].querySelector(".wallet .exchange_rate").innerHTML * 1;
      seller_wallet = td[3].querySelector(".wallet .seller_wallet").innerHTML;
      const payment_method = td[4].querySelector(
        ".methods span .payment_method"
      ).innerHTML;

      modal_seller_wallet_other.innerHTML = seller_wallet;
      rate_sell_other.innerHTML = exchange_rate;
      my_Wallet_sell_other.innerHTML = my_wallet;
      wallet_balance_other.innerHTML = `<b>${my_wallet}</b> ${sessionStorage.getItem(
        `${my_wallet}`
      )}`;
      rate_sell_other.innerHTML = `${exchange_rate}${modal_seller_wallet_other.innerHTML}<b>=</b> 1${my_wallet}`;


      //Checking the input value while the user is typing
      amount_sell_other.addEventListener("input", (e) => {
        
        const inputValue = e.target.value.trim();
        confirmBtn_sell_other.disabled = false;
        confirmBtn_sell_other.style.backgroundColor = "rgb(46, 204, 113)";
        if (!isNaN(inputValue)) {
          console.log("value is a number");
          //Calculations for the trade and values;
          purchase_cost_sell_other.innerHTML =
            e.target.value * 1 * rate_sell_other;
          fee_sell_other.innerHTML =
            purchase_cost_sell_other.innerHTML * 1 * 0.008;
          receive_amount_sell_other.innerHTML = (
            purchase_cost_sell_other.innerHTML * 1 -
            fee_sell_other.innerHTML * 1
          ).toFixed(2);
          console.log(
            purchase_cost_sell_other,
            fee_sell_other,
            receive_amount_sell_other
          );
        } else {
          confirmBtn_sell_other.disabled = true;
          confirmBtn_sell_other.style.backgroundColor = "red";
        }
      });
    });
  });
}, 1000);


//Function to get the payment methods data inside the modal;
async function getPaymentMethodDetails(url, payMethod) {
  try {
    let response = await fetch(url, { method: "POST", body: payMethod });
    let data = await response.json();
    displayDetails(data);
  } catch (error) {
    console.log(error);
  }
}

//Function to send data to the database and process automatic p2p orders;
async function sendData(url, formData) {
  let statusDisplay = document.querySelector("#success");
  let failedDisplay = document.querySelector("#failed");
  try {
    const response = await fetch(url, { method: "POST", body: formData });
    let data = await response.text();
    console.log(data);
    //Response handlers
    if(data.type == 'sell'){

    }else if(data.type == 'buy'){
      if (data.Insufficient_Balance) {
        failedDisplay.innerHTML = `${data.Insufficient_Balance} 😥 `;
        failedDisplay.style.display = "block";
        //The timeout for the status messages
        setTimeout(() => {
          failedDisplay.style.display = "none";
        }, 5000);
      } else {
        statusDisplay.style.display = "block";
        statusDisplay.innerHTML = `You have Successfully ${data.Successful}`;
        //The timeout for the status messages
        setTimeout(() => {
          statusDisplay.style.display = "none";
        }, 5000);
      }
    }
    // console.log(data);
  } catch (error) {
    console.log(error);
  }
}

//Function to handle the payment data returned;
let methodDisplay = document.querySelector("#buy_other #method_display");

function displayDetails(data) {
  for (const key in data) {
    const curr = {
      walletName: key,
      walletValue: data[key],
    };
    console.log(curr);
    if (curr.walletName == "bank") {
      let html = `<div class="name-details">
        <h4 id="">Bank name:</h4>
        <p>${curr.walletValue.bank_name}</p>
        </div>
        <div class="name-details">
        <h4>Acc name:</h4>
        <p>${curr.walletValue.user_name}</p>
        </div>
        <div class="name-details">
        <h4>Account NO:</h4>
        <p>${curr.walletValue.account_number}</p>
      </div>`;
      methodDisplay.innerHTML = html;
    } else if (curr.walletName == "Skrill") {
      let html = `<div class="name-details">
        <h4 id="">Skrill Wallet</h4>
        <p>${curr.walletValue.skrill_address}</p>
      </div>
      `;
      methodDisplay.innerHTML = html;
      console.log(html);
    } else if (curr.walletName == "google") {
      let html = `<div class="name-details">
        <h4 id="">Google Wallet</h4>
        <p>${curr.walletValue.google_address}</p>
      </div>
      `;
      methodDisplay.innerHTML = html;
    } else if (curr.walletName == "momo") {
      let html = `<div class="name-details">
        <h4 id="">Mobile Money</h4>
        <p>${curr.walletValue.user_name}</p>
      </div>
      <div class="name-details">
        <h4>Acc name:</h4>
        <p>${curr.walletValue.account_number}</p>
        </div>
      `;
      methodDisplay.innerHTML = html;
    } else if (curr.walletName == "paypal") {
      let html = `<div class="name-details">
        <h4 id="">PayPal Wallet</h4>
        <p>${curr.walletValue.paypal_address}</p>
      </div>
      `;
      methodDisplay.innerHTML = html;
    }
  }
}

`function copyInnerHtmlToClipboard(elementId) {
  const element = document.getElementById(elementId);
  if (!element) {
    console.error(Element with id '' not found);
    return;
  }

  const htmlToCopy = element.innerHTML;

  const textarea = document.createElement('textarea');
  textarea.value = htmlToCopy;
  textarea.style.position = 'fixed';
  document.body.appendChild(textarea);
  textarea.select();

  try {
    const successful = document.execCommand('copy');
    const message = successful ? 'HTML copied to clipboard' : 'Failed to copy HTML to clipboard';
    console.log(message);
  } catch (error) {
    console.error('Error copying HTML to clipboard:', error);
  }

  document.body.removeChild(textarea);
}

// Usage:
const elementId = 'myElement';
copyInnerHtmlToClipboard(elementId);

`;

function success(myW, recW, amnt, rcv, ref,tFee) {
  let html = `<div class="name-details">
  <h4>MY WALLET:</h4>
  <p><span>${myW}</span> WALLET</p>
</div>
<div class="name-details">
  <h4>RECEIVER :</h4>
  <p> <span>${recW}</span> WALLET</p>
</div>

<div class="name-details">
  <h4>SOLD AMOUNT</h4>
  <p>${amnt}</p>
</div>
<div class="name-details">
  <h4>RECEIVED AMOUNT:</h4>
  <p>${rcv}</p>
</div>
<div class="name-details">
  <h4>TRANSACTION FEE:</h4>
  <p>${tFee}</p>
</div>
<div class="complete-card" id="complete-card">
  <h3>Transaction Ref: <span>${ref}</span></h3>
</div>`;
  return html;
}
