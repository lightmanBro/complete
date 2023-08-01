function walletConfirm(
    order_index,
    low_limit,
    high_limit,
    Id,
    wallet_type,
    purchaseCost,
    transactionFee,
    receiveAmount,
    orderUnit,
    cancel_callback
  ) {
    const confirm_btn = document.querySelector(`#${wallet_type}_wallet_confirm`);
    confirm_btn.addEventListener('click', (e) => {
      e.preventDefault();
  
      confirm_btn.disabled = false;
      if (orderUnit * 1 < low_limit * 1 || orderUnit * 1 > high_limit * 1) {
        confirm_btn.disabled = true;
        alert('Too low or too high');
        setTimeout(() => {
          sell_cancel_callback();
        }, 500);
      } else {
        const p2pOrder = new FormData();
        p2pOrder.append('Type', `${wallet_type}Wallet`);
        p2pOrder.append('order_index', order_index);
        p2pOrder.append('Cost', purchaseCost);
        p2pOrder.append('TransactionFee', transactionFee * 1);
        p2pOrder.append('ReceiveAmount', receiveAmount * 1);
        p2pOrder.append(`${seller}`, Id);
        p2pOrder.append('OrderUnit', orderUnit * 1);
        p2pOrder.append('BuyerWallet', my_Wallet.innerHTML);
        p2pOrder.append('SellerWallet', seller_wallet);
  
        const urlEncodedData = new URLSearchParams(p2pOrder).toString();
        console.log(urlEncodedData);
  
        let url = '../php/order_request.php';
        sendData(url, p2pOrder);
  
        setTimeout(() => {
          cancel_callback();
        }, 500);
      }
    });
  }
  
//   // Usage:
//   const sell_order_index_value = ...; // Set the appropriate value
//   const sellerId_value = ...; // Set the appropriate value
//   const low_limit_value = ...; // Set the appropriate value
//   const high_limit_value = ...; // Set the appropriate value
//   const purchaseCost_value = ...; // Set the appropriate value
//   const transactionFee_value = ...; // Set the appropriate value
//   const receiveAmount_value = ...; // Set the appropriate value
//   const orderUnit_value = ...; // Set the appropriate value
//   const sell_cancel_callback = () => {
//     // Implement your sell_cancel functionality here
//   };
  
//   sellWalletConfirm(
//     sell_order_index_value,
//     sellerId_value,
//     low_limit_value,
//     high_limit_value,
//     purchaseCost_value,
//     transactionFee_value,
//     receiveAmount_value,
//     orderUnit_value,
//     sell_cancel_callback
//   );
  