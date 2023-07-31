console.log("fetchData trades");
/*
1 fetch all the transaction histories and paste it inside respective html

*/

async function fetchData(url) {
  try {
    let response = await fetch(url);
    if (response.ok) {
      let data = await response.json();
      console.log(data);
      if (data.active) {
        data.active.forEach(active=>{
          const activeTrade = `<div class="table-info" id="4000">
          <h4>Wallet: <span class="green-text">${active.wallet}</span></h4>
          <h4>Buyer ID: <span class="green-text">${active.buyer_id}</span></h4>
          <h4>Order unit: <span class="green-text">${active.order_unit}</span></h4>
          <h4>Rate:1 ${active.wallet}/ ₦${active.exchange_rate}</h4>
          <h4>Trade Value: <span class="green-text">₦${active.receive_amount}</span></h4>
          <h4>Trade ID: <span class="text-faded">${active.ind}</span></h4>
          <h4>Date: <span class="green-text">${active.time}</span></h4>
          <h4>Status: <button id="releaseBtn">Accept</button>
           <button id="declineBtn"style="background-color: var(--secondary); cursor: pointer;">Reject</button></h4>
          </div>`;
          document
            .querySelector("#active")
            .insertAdjacentHTML("afterbegin", activeTrade);

        })
      }
      if (data.pending) {
        data.pending.forEach(pending=>{
          const pendingTrade = 
          `<div class="table-info">
          <h4>Wallet: <span class="green-text">${pending.wallet}</span></h4>
          <h4>Rate: <span class="green-text">${pending.user_rate}</span></h4>
          <h4>Highest: <span class="green-text">${pending.highest_rate}</span></h4>
          <h4>Lowest: <span class="green-text">${pending.lowest_rate}</span></h4>
          <h4>Posted on: <span class="green-text">${pending.date}</span></h4>
          <h4>Payment Receiving Methods: <span><b>${pending.payment_method}</b></span>
          <h4><button style="background-color: var(--secondary); cursor: pointer;">Cancel</button></h4>
          `;
          document.querySelector('#pending').insertAdjacentHTML('beforeend',pendingTrade);
          })
      }
      if (data.completed) {
        console.log(data.completed);
        data.completed.forEach(completed=>{
          const completedTrade = `<div class="table-info">
          <h4>My Wallet: <span class="green-text">${completed.wallet}</span></h4>
          <h4>Seller Wallet: <span class="green-text">${completed.wallet}</span></h4>
          <h4>Seller ID: <span class="green-text">${completed.sender_id}</span></h4>
          <h4>Trade Value: <span class="green-text">${completed.order_unit}</span> [BUY]</h4>
          <h4>Trade Cost: <span class="green-text">₦${completed.trade_cost}</span> </h4>
          <h4>Rate:1 ${completed.wallet}/${completed.exchange_rate} NGN</h4>
          <h4>Fee: ₦${completed.transaction_fee}</h4>
          <h4>Amount Recieved: ₦${completed.received_amount}</h4>
          <h4>Trade ID: <span class="text-faded">#213298</span></h4>
          <h4>Date: <span class="green-text">${completed.date}</span></h4>
          <h4>Status: <button>Completed</button> <button id="print">Print</button></h4>
          </div>`;
          document
            .querySelector("#completed")
            .insertAdjacentHTML("afterbegin", completedTrade);

        })
      }

      if (data.disputed) {
        console.log(data.disputed);
      }
      if (data.cancelled) {
        console.log(data.cancelled);
      }
    }
  } catch (error) {
    console.log("Error", error);
  }
}
fetchData("../php/TransactionHistory/history.php");

//Set timeout function helps avoid undefined error;
//Completed Trade Print button
setTimeout(() => {
  const printBtn = document.querySelector("#completed #print");
  printBtn.addEventListener("click", (e) => {
    console.log("Print Button clicked from timeout");
  });
}, 500);



//Active trade Release and Cancel button
setTimeout(() => {
  const releaseBtn = document.querySelector("#active #releaseBtn");
  const declineBtn = document.querySelector("#active #declineBtn");
  //Release the trade to the trade maker
  releaseBtn.addEventListener("click", (e) => {
    console.log("Release Button Clicked");
    //The release logics
    //The trade released will be removed from this account and be added to the person's account and it will be a completed trade.
    async function releseTrade(url, formData) {
      try {
        let response = await fetch(url, {
          method: "POST",
          body: formData,
        });
        if (response.ok) {
          let data = await response.json();
          console.log(data);
        }
      } catch (error) {}
    }
  });
  
  
  //Decline the trade
  declineBtn.addEventListener("click", (e) => {
    console.log("Decline Button clicked");
    async function cancelTrade(url, formData) {
      try {
        let response = await fetch(url, {
          method: "POST",
          body: formData,
        });
        if (response.ok) {
          let data = await response.json();
          console.log(data);
        }
      } catch (error) {}
    }
    //The cancel logic
    //The trade will be cancelled and the notification will be shown on the buyer's account as cancelled trade.
  });
}, 1000);
