console.log("fetchData trades");
/*
Connected to walletHistory.php
1 fetch all the transaction histories and paste it inside respective html
*/

async function fetchData(url) {
  try {
    let response = await fetch(url);
    if (response.ok) {
      let data = await response.json();
      console.log(data);
      if (data.received) {
        data.received.forEach(received=>{
          const receivedMoney = `<div class="table-info" id="4000">
          <h4>Type <span class="green-text"> RECEIVED</span></h4>
          <h4>Wallet: <span class="green-text">${received.currency_wallet}</span></h4>
          <h4>Amount: <span class="green-text">${received.amount}</span></h4>
          <h4>Sender Id <span class="green-text">${received.sender_id}</span></h4>
          <h4>Transaction Ref: <span class="text-faded">${received.transaction_refrence}</span></h4>
          <h4>Date: <span class="green-text">${received.date}</span></h4>
          `;
          document
            .querySelector("#received")
            .insertAdjacentHTML("afterbegin",receivedMoney);

        })
      }
      if (data.sent) {
        data.sent.forEach(sent=>{
          const sentMoney = `<div class="table-info sent" id="4000">
          <h4>Type <span class="green-text"> SENT</span></h4>
          <h4>Wallet: <span class="green-text">${sent.currency_wallet}</span></h4>
          <h4>Amount: <span class="green-text">${sent.amount}</span></h4>
          <h4>Receiver Id <span class="green-text">${sent.receiver_id}</span></h4>
          <h4>Transaction Fee <span class="green-text">${sent.transaction_charge} ${sent.currency_wallet}</span></h4>
          <h4>Transaction Ref: <span class="text-faded">${sent.transaction_refrence}</span></h4>
          <h4>Date: <span class="green-text">${sent.date}</span> <button id="print">Print</button></h4>
          `;
          document
            .querySelector("#sent")
            .insertAdjacentHTML("afterbegin",sentMoney);

        })
      }
      
      
    }
  } catch (error) {
    console.log("Error", error);
  }
}
fetchData("../php/TransactionHistory/walletHistory.php");

//Set timeout function helps avoid undefined error;
//Completed Trade Print button
setTimeout(() => {
  const printBtn = document.querySelectorAll("#print").forEach(button=>{
    button.addEventListener("click", (e) => {
      console.log("Print Button clicked from timeout");
    });

  });
}, 500);