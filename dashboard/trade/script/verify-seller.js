//Connected to 
//gotten from the orderActions page;
let orderRef = sessionStorage.getItem('buyOrderRef');
let buyerId,wallet_to_receive,unit,rate,wallet_to_remove_from;
document.querySelector('#ref').innerHTML = orderRef;


//To get available order details
// document.addEventListener('DOMContentLoaded')
async function getOrderDetails(url){
    const ref = new FormData();
    ref.append('order_refrence',orderRef)
    try {
        await fetch(url,{ method: "POST", body: ref })
      .then(response=>response.text())
      .then(data=>{
        console.log(data,orderRef);
        OrderData(data)
      })
    } catch (error) {
      console.log(error);
    }
  }
const url =  '../php/orderActions/order-release.php'

setTimeout(() => {
  getOrderDetails(url);
}, 1000);


// console.log(orderRef);
// console.log('From verify seller')

 function OrderData(data) {
    console.log(data.order);
    let {buyer_id,exchange_rate,order_unit,payment_method,wallet,wallet_to,date}= data.order;
    wallet_to_receive = wallet;
    wallet_to_remove_from = wallet;
    wallet_to_receive = wallet_to;
    buyerId = buyer_id;
    unit = order_unit;
    rate = exchange_rate;
    let html = `
    <div class="payment-method-bank">
    <div class="name-details">
      <h4>Buyer Id</h4>
      <p>${buyer_id}</p>
    </div>
    <div class="name-details">
      <h4>Order unit:</h4>
      <p>${order_unit}</p>
    </div>
    <div class="name-details">
      <h4>Payment method:</h4>
      <p>${payment_method}</p>
    </div>
    <div class="name-details">
      <h4>Wallet from:</h4>
      <p>${wallet}</p>
    </div>
    <div class="name-details">
      <h4>Wallet to:</h4>
      <p>${wallet_to}</p>
    </div>
    <div class="name-details">
      <h4>Exchange Rate:</h4>
      <p>${exchange_rate} ${wallet} = 1 ${wallet_to}</p>
    </div>
    <div class="name-details">
      <h4>Expected Amount:</h4>
      <p>${order_unit/exchange_rate} ${wallet_to}</p>
    </div>
    <div class="name-details">
      <h4>DateTime</h4>
      <p>${date}</p>
    </div>
  </div>`
  document.querySelector('#orderData').insertAdjacentHTML('beforeend',html);
}

document.querySelector('#release_fund').addEventListener('click',(e)=>{
    const dataSell = new FormData();
    dataSell.append('release_fund','Yes')
    dataSell.append('order_unit',unit);
    dataSell.append('wallet',wallet_to_receive)
    dataSell.append('wallet_to_remove_from',wallet_to_remove_from);
    dataSell.append('exchange_rate',rate)
    dataSell.append('buyer_id',buyerId);
    dataSell.append('order_refrence',orderRef)
    releaseOrder('../php/orderActions/fund_release.php',dataSell);
    console.log('clicked release button')
    
})

async function releaseOrder(url,dataS){
    try {
        await fetch(url,{ method: "POST", body: dataS })
      .then(response=>response.text())
      .then(data=>{
        console.log(data)
        // data.success ? window.location.assign('../transactionHistory/transaction-history.html'):console.log('Failed')
        if(data.success){
            document.querySelector('#success').style.display = 'block';
            setTimeout(() => {
                window.location.assign('../transactionHistory/transaction-history.html#completed')
            }, 2500);
        }else if(failed){
            document.querySelector('#failed').style.display = 'block';
            setTimeout(() => {
                window.location.assign('./completed/index.php');
            }, 1500);
        }
    });
    } catch (error) {
        console.log(error)
    }
}

//To get the proof image as per this order from the database;
async function proofImages(url){
  let ref = new FormData();
  ref.append('order_refrence',orderRef);
  console.log(orderRef);
    try {
        await fetch(url,{method: "POST", body: ref})
        .then(response=>response.json())
        .then(data=>{console.log(data,orderRef)
        let {buyer,path,order_ref} = data;
        //Set the image as the src images of this img container
        document.querySelector('#proofImage').setAttribute('src',`../php/upload/proofImg/${path}`);
        })
        
    } catch (error) {
        console.log(error)
    }
}

// document.querySelector('#check_image').addEventListener('click',(e)=>{
// })
proofImages('../php/orderActions/check_proof.php'); 