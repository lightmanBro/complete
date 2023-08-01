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
        data.active.forEach(active => {
          const activeTrade = `<div class="table-info" id="4000">
          <h4>Wallet: <span class="green-text">${active.wallet}</span></h4>
          <h4>Lowest Rate: <span class="green-text">${active.lowest_rate}</span></h4>
          <h4>Highest Rate: <span class="green-text">${active.highest_rate}</span></h4>
          <h4>Exchange Rate: ${active.user_rate} ${active.wallet} = 1 ${active.payment_method}</h4>
          <h4>Date: <span class="green-text">${active.date}</span></h4>`;
          document
            .querySelector("#active")
            .insertAdjacentHTML("afterbegin", activeTrade);

        })
      }
      if (data.pendingSell) {
        data.pendingSell.forEach(pending => {
          const {buyer_id,exchange_rate,order_unit,type,wallet,wallet_to,date,transaction_refrence
          }= pending;
          const pendingTrade =
            `<div class="table-info" id="pendingSell">
            <h4 hidden>${buyer_id}</h4>
            <h4>Order Type: <span class="green-text">${type}</span></h4>
            <h4>Wallet From: <span class="green-text">${wallet}</span></h4>
            <h4>Wallet To: <span class="green-text">${wallet_to}</span></h4>
            <h4>Rate: <span class="green-text">${exchange_rate}</span></h4>
            <h4>Unit: <span class="green-text">${order_unit}</span></h4>
            <h4>Lowest: <span class="green-text">${order_unit *1 * exchange_rate*1}</span></h4>
            <h4>Posted on: <span class="green-text">${date}</span></h4>
            <h4>Reference: <span class="green-text" id="order_ref">${transaction_refrence}</span></h4>
            <h4>Payment Receiving Methods: <span><b>${pending.payment_method}</b></span>
            <h4><button style="cursor: pointer;">View Proof</button></h4>
            </div>
          `;
          document.querySelector('#pending').insertAdjacentHTML('beforeend', pendingTrade);
        })
      }
      if (data.pendingBuy) {
        data.pendingBuy.forEach(pending => {
          //The link out of this page will be to the upload receipt page
          const {buyer_id,exchange_rate,order_unit,type,wallet,wallet_to,date,transaction_refrence}= pending;
          const pendingTrade =
            `<div class="table-info" id="pendingBuy">
            <h4 hidden>${buyer_id}</h4>
            <h4>Order Type: <span class="green-text">${type}</span></h4>
          <h4>Wallet From: <span class="green-text">${wallet}</span></h4>
          <h4>Wallet To: <span class="green-text">${wallet_to}</span></h4>
          <h4>Rate: <span class="green-text">${exchange_rate}</span></h4>
          <h4>Unit: <span class="green-text">${order_unit}</span></h4>
          <h4>Lowest: <span class="green-text">${order_unit *1 * exchange_rate*1}</span></h4>
          <h4>Posted on: <span class="green-text">${date}</span></h4>
          <h4>Reference: <span class="green-text" id="order_ref">${transaction_refrence}</span></h4>
          <h4>Payment Receiving Methods: <span><b>${pending.payment_method}</b></span>
          <h4><button style="cursor: pointer;">Upload Proof</button></h4>
          </div>
          `;
          document.querySelector('#pending').insertAdjacentHTML('beforeend', pendingTrade);
        })
      }
      if (data.completed) {
        console.log(data.completed);
        data.completed.forEach(completed => {
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
      if (data.sell_other) {
        data.sell_other
          .forEach(sell_o => {
            let { wallet, lowest_rate, highest_rate, payment_method, time, wallet_to } = sell_o;
            const activeSell_other = `<div class="table-info" id="4000">
            <h4>Type: <span class="green-text">Sell</span></h4>
            <h4>Wallet: <span class="green-text">${wallet}</span></h4>
            <h4>Wallet for payment: <span class="green-text">${wallet_to}</span></h4>
            <h4>Lowest Rate: <span class="green-text">${lowest_rate}</span></h4>
            <h4>Highest Rate: <span class="green-text">${highest_rate}</span></h4>
            <h4>Payment Method:${payment_method}</h4>
            <h4>Posted on: <span class="green-text">${time}</span></h4>`;
            document
              .querySelector("#active")
              .insertAdjacentHTML("afterbegin", activeSell_other);

          })
      }
      if (data.p2p) {
        data.p2p.forEach(p2p => {
          const { amount, seller_id, exchange_rate, transaction_fee, transaction_reference, unit, wallet_from, wallet_to, date } = p2p;
          const p2pTrade = `<div class="table-info">
          <h4>My Wallet: <span class="green-text">${wallet_from}</span></h4>
          <h4>Seller Wallet: <span class="green-text">${wallet_to}</span></h4>
          <h4>Seller ID: <span class="green-text">${seller_id}</span></h4>
          <h4>Unit: <span class="green-text">${unit}</span></h4>
          <h4>Exchange Rate:1 ${wallet_from}/ ${exchange_rate} ${wallet_to}</h4>
          <h4>Trade Cost: <span class="green-text">${amount*1 + transaction_fee*1} ${wallet_to}</span> </h4>
          <h4>Fee: ${transaction_fee} ${wallet_to}</h4>
          <h4>Amount Recieved: ${amount} ${wallet_to}</h4>
          <h4>Reference: <span class="text-faded">${transaction_reference}</span></h4>
          <h4>Date: <span class="green-text">${date}</span></h4>
          <h4><button id="print">Print</button></h4>
          </div>`;
          document
            .querySelector("#completed")
            .insertAdjacentHTML("afterbegin", p2pTrade);
        })
      }
      if (data.p2p_other) {
        data.p2p_other.forEach(p2p_other => {
          console.log(p2p_other['buyer_id']);
          const { amount, buyer_id, exchange_rate, transaction_fee, transaction_reference, unit, wallet_from, wallet_to, date } = p2p_other;
          const p2p_other_Trade = `<div class="table-info">
          <h4>My Wallet: <span class="green-text">${wallet_from}</span></h4>
          <h4>Seller Wallet: <span class="green-text">${wallet_to}</span></h4>
          <h4>Seller ID: <span class="green-text">${buyer_id}</span></h4>
          <h4>Unit: <span class="green-text">${unit}</span></h4>
          <h4>Exchange Rate:1 ${wallet_from}/ ${exchange_rate} ${wallet_to}</h4>
          <h4>Trade Cost: <span class="green-text">${amount*1 + transaction_fee*1} ${wallet_to}</span> </h4>
          <h4>Fee: ${transaction_fee} ${wallet_to}</h4>
          <h4>Amount Recieved: ${amount} ${wallet_to}</h4>
          <h4>Reference: <span class="text-faded">${transaction_reference}</span></h4>
          <h4>Date: <span class="green-text">${date}</span></h4>
          <h4><button id="print">Print</button></h4>
          </div>`;
          document
            .querySelector("#completed")
            .insertAdjacentHTML("afterbegin", p2p_other_Trade);
        })
      }
  }
}
catch (error) {
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
