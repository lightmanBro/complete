// //Do all the dom manipulations here and call the function
// // BUY BY WALLET
// let my_Wallet_buy,seller_wallet_buy_id,seller_wallet_buy,high_limits,low_limits,buy_order_index;
// const amount_buy = document.querySelector('#buy_wallet #amount'),
// buy_cancel = document.querySelector('#buy_wallet #buy_wallet_cancel')

// setTimeout(() => {
//   document.querySelectorAll("#modal-btn").forEach((button) => {
//     button.addEventListener("click", (e) => {
//       const container = e.target.parentElement.parentElement.parentElement;
//       const td = container.querySelectorAll('td');
//       seller_wallet_buy_id = td[0].querySelector('.card-info1 #id').innerText;
//       buy_order_index = td[0].querySelector('#trade_ind').value;
//       console.log(buy_order_index);

//       //Disable the input box if the seller id and the buyer id is thesame.
//       if(seller_wallet_buy_id*1 == sessionStorage.getItem('UserId')*1){
//         amount_buy.disabled = true;
//         document.querySelector('#buy_wallet_confirm').disabled = true;
//         console.log('Thesame id');
//       }

//       console.log(seller_wallet_buy_id,sessionStorage.getItem('UserId'));

//       low_limits = td[2].querySelectorAll('.limit span')[0].innerHTML;
//       high_limits = td[2].querySelectorAll('.limit span')[1].innerHTML;
//       my_Wallet_buy = td[3].querySelector('.wallet .my_wallet').innerHTML;
//       const exchange_rate = td[3].querySelector('.wallet .exchange_rate').innerHTML*1;
//       seller_wallet_buy = td[3].querySelector('.wallet .seller_wallet').innerHTML;
//       console.log(my_Wallet_buy,seller_wallet_buy);
//       document.querySelector('.popup-container').style.display = "block";


//       document.querySelector('#buy_wallet #my_wallet p').innerHTML = `${my_Wallet_buy}`;
//       document.querySelector('#buy_wallet #low_limit p').innerHTML = `${low_limits}`;
//       document.querySelector('#buy_wallet #high_limit p').innerHTML = `${high_limits}`;
//       const wallet_balance = document.querySelector('#buy_wallet #wallet_balance p').innerHTML = `<b>${my_Wallet_buy}</b> ${sessionStorage.getItem(`${my_Wallet_buy}`)}`;
//       const modal_seller_wallet = document.querySelector('#buy_wallet #seller_wallet p').innerHTML =`<b>${ seller_wallet_buy}</b> Wallet`;
//       const purchase_cost = document.querySelector('#buy_wallet #purchase_cost p');
//       const rate =  document.querySelector('#buy_wallet #exchange_rate p').innerHTML = `${exchange_rate}${seller_wallet_buy} <b>=</b> 1${my_Wallet_buy}`;
//       const fee = document.querySelector('#buy_wallet #transaction_fee p');
//       const receive_amount = document.querySelector("#buy_wallet #receive_amount p");
//       const confirmBtn = document.querySelector('#buy_wallet #buy_wallet_confirm');
//       console.log(exchange_rate);


//       amount_buy.addEventListener('input',(e)=>{
//         const inputValue = e.target.value.trim();
//         if(/^\d+$/.test(inputValue)){
//           console.log("value is a number")
//           confirmBtn.disabled = false;
//           confirmBtn.style.backgroundColor = "rgb(46, 204, 113)";
//         }else{
//             confirmBtn.disabled = true;
//             confirmBtn.style.backgroundColor = "red";
//         }
        
//         purchase_cost.innerHTML = (e.target.value *1)*exchange_rate;
//         fee.innerHTML =( purchase_cost.innerHTML *1)*0.01;
//         receive_amount.innerHTML = ((purchase_cost.innerHTML *1) - (fee.innerHTML*1)).toFixed(2);
//         // console.log(e.target.value);
//       })
//       buy_cancel.addEventListener('click',()=>{
//         purchase_cost.innerHTML = '';
//         fee.innerHTML = '';
//         receive_amount.innerHTML = '';
//       })
//     });
//   });
// }, 1000);


// document.querySelector('#buy_wallet_confirm').addEventListener('click',(e)=>{
//     e.preventDefault();
//     let purchaseCost = document.querySelector('#buy_wallet #purchase_cost p').innerHTML;
//     let transactionFee = document.querySelector('#buy_wallet #transaction_fee p').innerHTML;
//     let receiveAmount = document.querySelector("#buy_wallet #receive_amount p").innerHTML;
//     let orderUnit = document.querySelector('#buy_wallet #amount');
//     // console.log(purchaseCost,transactionFee,receiveAmount,orderUnit.value);
  
//     e.target.disabled = false
//     if(orderUnit.value.trim()*1>high_limits*1||orderUnit.value.trim()<low_limits){
//       e.target.disabled = true;
//       alert('Value is lower than the low limit')
//       setTimeout(() => {
//         buy_cancel.click();
//       }, 500);
//     }else{
//        e.target.disabled = false
//       const buyOrder = new FormData();
//       buyOrder.append('Type','buyWallet');
//       buyOrder.append("Cost",purchaseCost);
//       buyOrder.append('order_index',buy_order_index);
//       buyOrder.append("TransactionFee",transactionFee);
//       buyOrder.append('ReceiveAmount',receiveAmount*1);
//       buyOrder.append('SellerId',seller_wallet_buy_id);
//       buyOrder.append('OrderUnit',orderUnit.value.trim()*1);
//       buyOrder.append('BuyerWallet',my_Wallet_buy);
//       buyOrder.append('SellerWallet',seller_wallet_buy);  
//       const urlEncodedData = new URLSearchParams(buyOrder).toString();
//       console.log(urlEncodedData);
//       let url = '../php/order_request.php';
//       let status = sendData(url,buyOrder);
//       console.log(`Buy Order Status ${status}`);
    
//       setTimeout(() => {
//         buy_cancel.click();
//       }, 500);
//     }
  
//   })

// let buyId_other,order_index;
  
//   setTimeout(() => {
//     document.querySelectorAll("#modal-btn_buy_other").forEach((button) => {
//       button.addEventListener("click", (e) => {
//         document.querySelector('#popup-container4').style.display = 'block';
//        const container = e.target.parentElement.parentElement.parentElement;
//         const td = container.querySelectorAll('td');
//         console.log(container);
//         buyId_other = td[0].querySelector('.card-info1 #id').innerText,
//         order_index = td[0].querySelector('#trade_ind').value;
//         const low_limits = td[2].querySelectorAll('.limit span')[0].innerHTML,
//         high_limit = td[2].querySelectorAll('.limit span')[1].innerHTML;
//         const my_wallet = td[3].querySelector('.wallet .my_wallet').innerHTML;
//         const exchange_rate = td[3].querySelector('.wallet .exchange_rate').innerHTML*1;
//         my_wallet = td[3].querySelector('.wallet .buy_wallet').innerHTML;
//         const payment_method = td[4].querySelector('.methods span .payment_method').innerHTML
  
//         modal_buy_wallet_other.innerHTML = buy_wallet;
//         rate_sell_other.innerHTML = exchange_rate;
//         my_Wallet_sell_other.innerHTML = my_wallet;
//         wallet_balance_other.innerHTML = `<b>${my_wallet}</b> ${sessionStorage.getItem(`${my_wallet}`)}`;
//         rate_sell_other.innerHTML = `${exchange_rate}${modal_buy_wallet_other.innerHTML}<b>=</b> 1${my_wallet}`
        
  
//         amount_sell_other.addEventListener('input',(e)=>{
//           console.log(e.target.value*1*exchange_rate)
//           const inputValue = e.target.value.trim();
//           if(/^\d+$/.test(inputValue)){
//             console.log("value is a number")
//             // confirmBtn_sell_other.disabled = false;
//             // confirmBtn_sell_other.style.backgroundColor = "rgb(46, 204, 113)";
//           }else{
//               confirmBtn_sell_other.disabled = true;
//               confirmBtn_sell_other.style.backgroundColor = "red";
//           }
  
//           //Calculations for the trade and values;
//           purchase_cost_sell_other.innerHTML = (e.target.value *1)*exchange_rate;
//           fee_sell_other.innerHTML =( purchase_cost_sell_other.innerHTML *1)*0.01;
//           receive_amount_sell_other.innerHTML = ((purchase_cost_sell_other.innerHTML *1) - (fee_sell_other.innerHTML*1)).toFixed(2);
//           console.log(purchase_cost_sell_other,fee_sell_other,receive_amount_sell_other);
//         })
  
//         //Function to get user payment method details from the database;
//         let methods = new FormData();
//         methods.append('method',payment_method);
//         methods.append('sellerId',sellerId_other);
//         let url = '../php/payment-methods-trade.php';
//         getPaymentMethodDetails(url,methods);
//         console.log(payment_method);
  
//       });
//     });
//   }, 1000);
// // walletConfirm()