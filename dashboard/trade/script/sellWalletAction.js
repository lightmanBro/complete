// //Do all the dom manipulations here and call the function

// let sellerId,seller_wallet,my_Wallet,sell_order_index;
// // SELL BY WALLET
// const amount_sell = document.querySelector('#sell_wallet #amount'),
// sell_cancel = document.querySelector('#sell_wallet #sell_wallet_cancel'),
// wallet_balance = document.querySelector('#sell_wallet #wallet_balance p'),
// modal_seller_wallet = document.querySelector('#sell_wallet #seller_wallet p'),
// purchase_cost = document.querySelector('#sell_wallet #purchase_cost p'),
// rate =  document.querySelector('#sell_wallet #exchange_rate p'),
// fee = document.querySelector('#sell_wallet #transaction_fee p'),
// receive_amount = document.querySelector("#sell_wallet #receive_amount p"),
// confirmBtn = document.querySelector('#sell_wallet #sell_wallet_confirm');

// my_Wallet = document.querySelector('#sell_wallet #my_wallet p');
// let low_limit = document.querySelector('#sell_wallet #low_limit p'),
// high_limit = document.querySelector('#sell_wallet #high_limit p');


// setTimeout(() => {
//   document.querySelectorAll('#modal-btn_sell').forEach(button=>{
//     button.addEventListener('click',(e)=>{
//       console.log(e.target.parentElement.parentElement.parentElement);
//       document.querySelector('#popup-container2').style.display = 'block';

//       const container = e.target.parentElement.parentElement.parentElement;
//       const td = container.querySelectorAll('td');
//       sellerId = td[0].querySelector('.card-info1 #id').innerText;
//       sell_order_index = td[0].querySelector('#trade_ind').value;
//       console.log(sell_order_index);
//       //Disable the input box if the seller id and the buyer id is thesame.
//       if(sellerId == sessionStorage.getItem('UserId')){
//         amount_sell.disabled = true;
//         document.querySelector('#sell_wallet_confirm').disabled = true;
//       }

//       console.log(td[2],td[0],sellerId);
//       const low_limits = td[2].querySelectorAll('.limit span')[0].innerHTML,
//       high_limits = td[2].querySelectorAll('.limit span')[1].innerHTML;
//       const my_wallet = td[3].querySelector('.wallet .my_wallet').innerHTML;
//       const exchange_rate = td[3].querySelector('.wallet .exchange_rate').innerHTML*1;
//       seller_wallet = td[3].querySelector('.wallet .seller_wallet').innerHTML;
//       console.log(high_limits,low_limits,exchange_rate,seller_wallet,my_wallet);
      
//       my_Wallet.innerHTML = `${my_wallet}`;
//       low_limit.innerHTML = `${low_limits}`;
//       high_limit.innerHTML = `${high_limits}`;
//       wallet_balance.innerHTML = `<b>${my_wallet}</b> ${sessionStorage.getItem(`${my_wallet}`)}`;
//       modal_seller_wallet.innerHTML =`<b>${ seller_wallet}</b> Wallet`;
//       rate.innerHTML = `${exchange_rate}${seller_wallet} <b>=</b> 1${my_wallet}`;
      

//       //Check the input values if its a number
//       amount_sell.addEventListener('input',(e)=>{
//         console.log(e.target.value*1*exchange_rate)
//         const inputValue = e.target.value.trim();
//         if(/^\d+$/.test(inputValue)){
//           console.log("value is a number")
//           confirmBtn.disabled = false;
//           confirmBtn.style.backgroundColor = "rgb(46, 204, 113)";
//         }else{
//             confirmBtn.disabled = true;
//             confirmBtn.style.backgroundColor = "red";
//         }

//         //Calculations for the trade and values;
//         purchase_cost.innerHTML = (e.target.value *1)*exchange_rate;
//         fee.innerHTML =( purchase_cost.innerHTML *1)*0.01;
//         receive_amount.innerHTML = ((purchase_cost.innerHTML *1) - (fee.innerHTML*1)).toFixed(2);
//       })

//       sell_cancel.addEventListener('click',()=>{
//         purchase_cost.innerHTML = '';
//         fee.innerHTML = '';
//         receive_amount.innerHTML = '';
//       })

//     })
//   })

// }, 1000);





// document.querySelector('#sell_wallet_confirm').addEventListener('click',(e)=>{
//     e.preventDefault();
//     let purchaseCost = document.querySelector('#sell_wallet #purchase_cost p').innerHTML;
//     let transactionFee = document.querySelector('#sell_wallet #transaction_fee p').innerHTML;
//     let receiveAmount = document.querySelector("#sell_wallet #receive_amount p").innerHTML;
//     let orderUnit = document.querySelector('#sell_wallet #amount');
//     console.log(document.querySelector('#sell_wallet #wallet_balance p b').innerHTML,sellerId);
//     // let buyerWallet = sessionStorage.getItem(`${document.querySelector('#sell_wallet #my_wallet p').innerHTML.split(' ')[0]}`);
//     e.target.disabled = false;
//     if(orderUnit.value.trim()*1<low_limit.innerHTML*1||orderUnit.value.trim()>high_limit.innerHTML*1){
//       e.target.disabled = true;
//       alert('too low or too high');
//       setTimeout(() => {
//         sell_cancel.click();
//       }, 500);
//     }else{
//       const sellOrder = new FormData();
//       sellOrder.append('Type','sellWallet');
//       sellOrder.append('order_index',sell_order_index);
//       sellOrder.append('Cost',purchaseCost);
//       sellOrder.append('TransactionFee',transactionFee*1);
//       sellOrder.append('ReceiveAmount',receiveAmount*1);
//       sellOrder.append('SellerId',sellerId);
//       sellOrder.append('OrderUnit',orderUnit.value.trim()*1);
//       sellOrder.append('BuyerWallet',my_Wallet.innerHTML);
//       sellOrder.append('SellerWallet',seller_wallet);  
//       const urlEncodedData = new URLSearchParams(sellOrder).toString();
//       console.log(urlEncodedData);
//       let url = '../php/order_request.php';
//       sendData(url,sellOrder);
  
//       setTimeout(() => {
//         sell_cancel.click();
//       }, 500);
//     }
    
//   })




//   setTimeout(() => {
//     document.querySelectorAll("#modal-btn_sell_other").forEach((button) => {
//       button.addEventListener("click", (e) => {
//         document.querySelector('#popup-container5').style.display = 'block';
//        const container = e.target.parentElement.parentElement.parentElement;
//         const td = container.querySelectorAll('td');
//         console.log(container);
//         sellerId_other = td[0].querySelector('.card-info1 #id').innerText,
//         order_index = td[0].querySelector('#trade_ind').value;
//         const low_limits = td[2].querySelectorAll('.limit span')[0].innerHTML,
//         high_limit = td[2].querySelectorAll('.limit span')[1].innerHTML;
//         const my_wallet = td[3].querySelector('.wallet .my_wallet').innerHTML;
//         const exchange_rate = td[3].querySelector('.wallet .exchange_rate').innerHTML*1;
//         seller_wallet = td[3].querySelector('.wallet .seller_wallet').innerHTML;
//         const payment_method = td[4].querySelector('.methods span .payment_method').innerHTML
  
//         modal_seller_wallet_other.innerHTML = seller_wallet;
//         rate_sell_other.innerHTML = exchange_rate;
//         my_Wallet_sell_other.innerHTML = my_wallet;
//         wallet_balance_other.innerHTML = `<b>${my_wallet}</b> ${sessionStorage.getItem(`${my_wallet}`)}`;
//         rate_sell_other.innerHTML = `${exchange_rate}${modal_seller_wallet_other.innerHTML}<b>=</b> 1${my_wallet}`
        
  
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

// // walletConfirm();