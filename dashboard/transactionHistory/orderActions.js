let orderRef;
setTimeout(() => {
    let button = document.querySelectorAll('#pendingSell button');
    button.forEach(btn=>{
        btn.addEventListener('click',(e)=>{
            e.preventDefault();
            orderRef = e.target.parentElement.parentElement.querySelector('#order_ref').innerHTML;
            window.location.assign('../trade/verify-seller.html')
            sessionStorage.setItem('sellOrderRef',orderRef);
            console.log(orderRef);
        })
    })
}, 1000);

setTimeout(() => {
    let button = document.querySelectorAll('#pendingBuy button');
    button.forEach(btn=>{
        btn.addEventListener('click',(e)=>{
            e.preventDefault();
            orderRef = e.target.parentElement.parentElement.querySelector('#order_ref').innerHTML;
            window.location.assign('../trade/verify.html')
            sessionStorage.setItem('buyOrderRef',orderRef);
            console.log(orderRef);
        })
    })
}, 1000);