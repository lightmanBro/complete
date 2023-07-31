const naira = sessionStorage.getItem("Naira");
const dollar = sessionStorage.getItem("Dollar");
const rand = sessionStorage.getItem("Rand");
const cedi = sessionStorage.getItem("Cedi");

console.log(naira,dollar,rand,cedi)
//Closing the modal box
window.addEventListener("click", function (event) {
  if (
    event.target == document.querySelector("#popup-container2") ||
    event.target == document.querySelector("#popup-container5")||
    event.target== document.querySelector('.popup-container') || 
    event.target ==  document.querySelector('#popup-container4')
  ) {
    document.querySelector("#popup-container2").style.display = "none"; // Hide the modal when clicking outside of it
    document.querySelector("#popup-container5").style.display = "none";
    document.querySelector('.popup-container').style.display = "none";
    document.querySelector('#popup-container4').style.display = 'none';
    //Once the window is clicked the disabled should be false;
    amount_buy.disabled = false;
    document.querySelector('#buy_wallet_confirm').disabled = false;
    amount_sell.disabled = false;
    document.querySelector('#sell_wallet_confirm').disabled = false;
  }
});




// BUY BY WALLET
let my_Wallet_buy,seller_wallet_buy_id,seller_wallet_buy,high_limits,low_limits;
const amount_buy = document.querySelector('#buy_wallet #amount'),
buy_cancel = document.querySelector('#buy_wallet #buy_wallet_cancel')

setTimeout(() => {
  document.querySelectorAll("#modal-btn").forEach((button) => {
    button.addEventListener("click", (e) => {
      const container = e.target.parentElement.parentElement.parentElement;
      const td = container.querySelectorAll('td');
      seller_wallet_buy_id = td[0].querySelector('.card-info1 #id').innerText;

      //Disable the input box if the seller id and the buyer id is thesame.
      if(seller_wallet_buy_id*1 == sessionStorage.getItem('UserId')*1){
        amount_buy.disabled = true;
        document.querySelector('#buy_wallet_confirm').disabled = true;
        console.log('Thesame id');
      }

      console.log(seller_wallet_buy_id,sessionStorage.getItem('UserId'));

      low_limits = td[2].querySelectorAll('.limit span')[0].innerHTML;
      high_limits = td[2].querySelectorAll('.limit span')[1].innerHTML;
      my_Wallet_buy = td[3].querySelector('.wallet .my_wallet').innerHTML;
      const exchange_rate = td[3].querySelector('.wallet .exchange_rate').innerHTML*1;
      seller_wallet_buy = td[3].querySelector('.wallet .seller_wallet').innerHTML;
      console.log(my_Wallet_buy,seller_wallet_buy);
      document.querySelector('.popup-container').style.display = "block";


      document.querySelector('#buy_wallet #my_wallet p').innerHTML = `${my_Wallet_buy}`;
      document.querySelector('#buy_wallet #low_limit p').innerHTML = `${low_limits}`;
      document.querySelector('#buy_wallet #high_limit p').innerHTML = `${high_limits}`;
      const wallet_balance = document.querySelector('#buy_wallet #wallet_balance p').innerHTML = `<b>${my_Wallet_buy}</b> ${sessionStorage.getItem(`${my_Wallet_buy}`)}`;
      const modal_seller_wallet = document.querySelector('#buy_wallet #seller_wallet p').innerHTML =`<b>${ seller_wallet_buy}</b> Wallet`;
      const purchase_cost = document.querySelector('#buy_wallet #purchase_cost p');
      const rate =  document.querySelector('#buy_wallet #exchange_rate p').innerHTML = `${exchange_rate}${seller_wallet_buy} <b>=</b> 1${my_Wallet_buy}`;
      const fee = document.querySelector('#buy_wallet #transaction_fee p');
      const receive_amount = document.querySelector("#buy_wallet #receive_amount p");
      const confirmBtn = document.querySelector('#buy_wallet #buy_wallet_confirm');
      console.log(exchange_rate);


      amount_buy.addEventListener('input',(e)=>{
        const inputValue = e.target.value.trim();
        if(/^\d+$/.test(inputValue)){
          console.log("value is a number")
          confirmBtn.disabled = false;
          confirmBtn.style.backgroundColor = "rgb(46, 204, 113)";
        }else{
            confirmBtn.disabled = true;
            confirmBtn.style.backgroundColor = "red";
        }
        
        purchase_cost.innerHTML = (e.target.value *1)*exchange_rate;
        fee.innerHTML =( purchase_cost.innerHTML *1)*0.01;
        receive_amount.innerHTML = ((purchase_cost.innerHTML *1) - (fee.innerHTML*1)).toFixed(2);
        // console.log(e.target.value);
      })
      buy_cancel.addEventListener('click',()=>{
        purchase_cost.innerHTML = '';
        fee.innerHTML = '';
        receive_amount.innerHTML = '';
      })
    });
  });
}, 1000);

document.querySelector('#buy_wallet_confirm').addEventListener('click',(e)=>{
  e.preventDefault();
  let purchaseCost = document.querySelector('#buy_wallet #purchase_cost p').innerHTML;
  let transactionFee = document.querySelector('#buy_wallet #transaction_fee p').innerHTML;
  let receiveAmount = document.querySelector("#buy_wallet #receive_amount p").innerHTML;
  let orderUnit = document.querySelector('#buy_wallet #amount');
  // console.log(purchaseCost,transactionFee,receiveAmount,orderUnit.value);

  e.target.disabled = false
  if(orderUnit.value.trim()*1>high_limits*1||orderUnit.value.trim()<low_limits){
    e.target.disabled = true;
    alert('Value is lower than the low limit')
    setTimeout(() => {
      buy_cancel.click();
    }, 500);
  }else{
     e.target.disabled = false
    const buyOrder = new FormData();
    buyOrder.append('Type','buyWallet');
    buyOrder.append("Cost",purchaseCost);
    buyOrder.append("TransactionFee",transactionFee);
    buyOrder.append('ReceiveAmount',receiveAmount*1);
    buyOrder.append('SellerId',seller_wallet_buy_id);
    buyOrder.append('OrderUnit',orderUnit.value.trim()*1);
    buyOrder.append('BuyerWallet',my_Wallet_buy);
    buyOrder.append('SellerWallet',seller_wallet_buy);  
    const urlEncodedData = new URLSearchParams(buyOrder).toString();
    console.log(urlEncodedData);
    let url = '../php/order_request.php';
    sendData(url,buyOrder);
  
    setTimeout(() => {
      buy_cancel.click();
    }, 500);
  }

})









let sellerId,seller_wallet,my_Wallet;
// SELL BY WALLET
const amount_sell = document.querySelector('#sell_wallet #amount'),
sell_cancel = document.querySelector('#sell_wallet #sell_wallet_cancel'),
wallet_balance = document.querySelector('#sell_wallet #wallet_balance p'),
modal_seller_wallet = document.querySelector('#sell_wallet #seller_wallet p'),
purchase_cost = document.querySelector('#sell_wallet #purchase_cost p'),
rate =  document.querySelector('#sell_wallet #exchange_rate p'),
fee = document.querySelector('#sell_wallet #transaction_fee p'),
receive_amount = document.querySelector("#sell_wallet #receive_amount p"),
confirmBtn = document.querySelector('#sell_wallet #sell_wallet_confirm');

my_Wallet = document.querySelector('#sell_wallet #my_wallet p');
let low_limit = document.querySelector('#sell_wallet #low_limit p'),
high_limit = document.querySelector('#sell_wallet #high_limit p');


setTimeout(() => {
  document.querySelectorAll('#modal-btn_sell').forEach(button=>{
    button.addEventListener('click',(e)=>{
      console.log(e.target.parentElement.parentElement.parentElement);
      document.querySelector('#popup-container2').style.display = 'block';

      const container = e.target.parentElement.parentElement.parentElement;
      const td = container.querySelectorAll('td');
      sellerId = td[0].querySelector('.card-info1 #id').innerText;

      //Disable the input box if the seller id and the buyer id is thesame.
      if(sellerId == sessionStorage.getItem('UserId')){
        amount_sell.disabled = true;
        document.querySelector('#sell_wallet_confirm').disabled = true;
      }

      console.log(td[2],td[0],sellerId);
      const low_limits = td[2].querySelectorAll('.limit span')[0].innerHTML,
      high_limits = td[2].querySelectorAll('.limit span')[1].innerHTML;
      const my_wallet = td[3].querySelector('.wallet .my_wallet').innerHTML;
      const exchange_rate = td[3].querySelector('.wallet .exchange_rate').innerHTML*1;
      seller_wallet = td[3].querySelector('.wallet .seller_wallet').innerHTML;
      console.log(high_limits,low_limits,exchange_rate,seller_wallet,my_wallet);
      
      my_Wallet.innerHTML = `${my_wallet}`;
      low_limit.innerHTML = `${low_limits}`;
      high_limit.innerHTML = `${high_limits}`;
      wallet_balance.innerHTML = `<b>${my_wallet}</b> ${sessionStorage.getItem(`${my_wallet}`)}`;
      modal_seller_wallet.innerHTML =`<b>${ seller_wallet}</b> Wallet`;
      rate.innerHTML = `${exchange_rate}${seller_wallet} <b>=</b> 1${my_wallet}`;
      

      //Check the input values if its a number
      amount_sell.addEventListener('input',(e)=>{
        console.log(e.target.value*1*exchange_rate)
        const inputValue = e.target.value.trim();
        if(/^\d+$/.test(inputValue)){
          console.log("value is a number")
          confirmBtn.disabled = false;
          confirmBtn.style.backgroundColor = "rgb(46, 204, 113)";
        }else{
            confirmBtn.disabled = true;
            confirmBtn.style.backgroundColor = "red";
        }

        //Calculations for the trade and values;
        purchase_cost.innerHTML = (e.target.value *1)*exchange_rate;
        fee.innerHTML =( purchase_cost.innerHTML *1)*0.01;
        receive_amount.innerHTML = ((purchase_cost.innerHTML *1) - (fee.innerHTML*1)).toFixed(2);
      })

      sell_cancel.addEventListener('click',()=>{
        purchase_cost.innerHTML = '';
        fee.innerHTML = '';
        receive_amount.innerHTML = '';
      })

    })
  })

}, 1000);





document.querySelector('#sell_wallet_confirm').addEventListener('click',(e)=>{
    e.preventDefault();
    let purchaseCost = document.querySelector('#sell_wallet #purchase_cost p').innerHTML;
    let transactionFee = document.querySelector('#sell_wallet #transaction_fee p').innerHTML;
    let receiveAmount = document.querySelector("#sell_wallet #receive_amount p").innerHTML;
    let orderUnit = document.querySelector('#sell_wallet #amount');
    console.log(document.querySelector('#sell_wallet #wallet_balance p b').innerHTML,sellerId);
    // let buyerWallet = sessionStorage.getItem(`${document.querySelector('#sell_wallet #my_wallet p').innerHTML.split(' ')[0]}`);
    e.target.disabled = false;
    if(orderUnit.value.trim()*1<low_limit.innerHTML*1||orderUnit.value.trim()>high_limit.innerHTML*1){
      e.target.disabled = true;
      alert('too loẇor too high');
      setTimeout(() => {
        sell_cancel.click();
      }, 500);
    }else{
      const sellOrder = new FormData();
      sellOrder.append('Type','sellWallet');
      sellOrder.append('Cost',purchaseCost);
      sellOrder.append('TransactionFee',transactionFee*1);
      sellOrder.append('ReceiveAmount',receiveAmount*1);
      sellOrder.append('SellerId',sellerId);
      sellOrder.append('OrderUnit',orderUnit.value.trim()*1);
      sellOrder.append('BuyerWallet',my_Wallet.innerHTML);
      sellOrder.append('SellerWallet',seller_wallet);  
      const urlEncodedData = new URLSearchParams(sellOrder).toString();
      console.log(urlEncodedData);
      let url = '../php/order_request.php';
      sendData(url,sellOrder);
  
      setTimeout(() => {
        sell_cancel.click();
      }, 500);
    }
    
  })


setTimeout(() => {
  document.querySelectorAll('#modal-btn_buy_other').forEach(button=>{
    button.addEventListener('click',(e)=>{
      document.querySelector('#popup-container4').style.display = 'block';
      console.log(e.target)
    })
  })
}, 1000);




const amount_sell_other = document.querySelector('#sell_wallet_other #amount'),
sell_cancel_other = document.querySelector('#sell_wallet_other #sell_wallet_other_cancel'),
wallet_balance_other = document.querySelector('#sell_wallet_other #wallet_balance p'),
modal_seller_wallet_other = document.querySelector('#sell_wallet_other #seller_wallet p'),
purchase_cost_sell_other = document.querySelector('#sell_wallet_other #purchase_cost p'),
rate_sell_other =  document.querySelector('#sell_wallet_other #exchange_rate p'),
fee_sell_other = document.querySelector('#sell_wallet_other #transaction_fee p'),
receive_amount_sell_other = document.querySelector("#sell_wallet_other #receive_amount p"),
confirmBtn_sell_other = document.querySelector('#sell_wallet_other #sell_wallet_other_confirm');

let my_Wallet_sell_other = document.querySelector('#sell_wallet_other #my_wallet p'),
low_limit_sell_other = document.querySelector('#sell_wallet_other #low_limit p'),
high_limit_sell_other = document.querySelector('#sell_wallet_other #high_limit p');

setTimeout(() => {
  document.querySelectorAll("#modal-btn_sell_other").forEach((button) => {
    button.addEventListener("click", (e) => {
      document.querySelector('#popup-container5').style.display = 'block';
     const container = e.target.parentElement.parentElement.parentElement;
      const td = container.querySelectorAll('td');
      sellerId = td[0].querySelector('.card-info1 #id').innerText;
      const low_limits = td[2].querySelectorAll('.limit span')[0].innerHTML,
      high_limit = td[2].querySelectorAll('.limit span')[1].innerHTML;
      const my_wallet = td[3].querySelector('.wallet .my_wallet').innerHTML;
      const exchange_rate = td[3].querySelector('.wallet .exchange_rate').innerHTML*1;
      seller_wallet = td[3].querySelector('.wallet .seller_wallet').innerHTML;
      const payment_method = td[4].querySelector('.methods span .payment_method').innerHTML

      modal_seller_wallet_other.innerHTML = seller_wallet;
      rate_sell_other.innerHTML = exchange_rate;
      my_Wallet_sell_other.innerHTML = my_wallet;
      wallet_balance_other.innerHTML = `<b>${my_wallet}</b> ${sessionStorage.getItem(`${my_wallet}`)}`;
      rate_sell_other.innerHTML = `${exchange_rate}${modal_seller_wallet_other.innerHTML}<b>=</b> 1${my_wallet}`
      



      //Function to get user payment method details from the database;
      let methods = new FormData();
      methods.append('method',payment_method);
      methods.append('sellerId',sellerId);
      let url = '../php/payment-methods-trade.php';
      getPaymentMethodDetails(url,methods);
      console.log(payment_method);

    });
  });
}, 1000);


//Function to get the payment methods data;
function getPaymentMethodDetails(url,payMethod){
  try {
    fetch(url,{ method: "POST", body: payMethod })
    .then(response=>response.json())
    .then(data=>displayDetails(data))
  } catch (error) {
    console.log(error);
  }
}

//Function to send data to the database and process;
async function sendData(url, formData) {
  try {
    fetch(url, { method: "POST", body: formData })
      .then((response) => response.text())
      .then((data) => {
        console.log(data);
      });
  } catch (error) {
    console.log(error);
  }
}

//Function to handle the payment data returned;
let methodDisplay = document.querySelector('#method_display');
function displayDetails(data) {
  for (const key in data) {
    const curr = {
      walletName: key,
      walletValue: data[key],
    };
    console.log(curr);
    if (curr.walletName == "bank") {
      let html =
      `<div class="name-details">
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
      // console.log(curr.walletValue.bank_name);
    }else if(curr.walletName == "skrill"){
      let html =
      `<div class="name-details">
        <h4 id="">Skrill Wallet</h4>
        <p>${curr.walletValue.skrill_address}</p>
      </div>
      `;
      methodDisplay.innerHTML = html;
      console.log(html);
    }else if(curr.walletName == 'google'){
      let html =
      `<div class="name-details">
        <h4 id="">Google Wallet</h4>
        <p>${curr.walletValue.google_address}</p>
      </div>
      `;
      methodDisplay.innerHTML = html;
    }else if(curr.walletName == 'momo'){
      let html =
      `<div class="name-details">
        <h4 id="">Mobile Money</h4>
        <p>${curr.walletValue.user_name}</p>
      </div>
      <div class="name-details">
        <h4>Acc name:</h4>
        <p>${curr.walletValue.account_number}</p>
        </div>
      `;
      methodDisplay.innerHTML = html;
    }else if(curr.walletName == 'paypal'){
      let html =
      `<div class="name-details">
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
    console.error(Element with id '${elementId}' not found);
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

`