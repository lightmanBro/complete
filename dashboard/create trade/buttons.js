

const buyContainer = document.querySelector('#buy_btn'),
sellContainer = document.querySelector('#sell_btn'),
other_method_sell = document.querySelector('#other_method_sell'),
wallet_method_sell = document.querySelector('#wallet_method_sell'),
other_method_sell_btn = document.querySelector('#sell_other'),
wallet_method_sell_btn = document.querySelector('#sell_wallet'),

other_method_buy = document.querySelector('#other_method_buy'),
wallet_method_buy = document.querySelector('#wallet_method_buy'),
wallet_method_buy_btn = document.querySelector('#buy_wallet'),
other_method_buy_btn = document.querySelector('#buy_other');

other_method_buy.classList.add('none');
other_method_sell.classList.add('none');
document.querySelector('#sell_post').classList.add('none');

sellContainer.addEventListener('click',()=>{
    console.log('working sell button')
    document.querySelector('#buy_post').classList.add('none');
    document.querySelector('#sell_post').classList.remove('none');
    sellContainer.classList.add('active');
    buyContainer.classList.remove('active');
})

buyContainer.addEventListener('click',()=>{
    console.log('working buy button')
    document.querySelector('#buy_post').classList.remove('none');
    document.querySelector('#sell_post').classList.add('none');
    buyContainer.classList.add('active');
    sellContainer.classList.remove('active');
})

other_method_sell_btn.addEventListener('click',()=>{
    wallet_method_sell.classList.add('none');
    other_method_sell.classList.remove('none');
})
wallet_method_sell_btn.addEventListener('click',()=>{
    other_method_sell.classList.add('none');
    wallet_method_sell.classList.remove('none');
})

other_method_buy_btn.addEventListener('click',()=>{
    other_method_buy.classList.remove('none');
    wallet_method_buy.classList.add('none');
})

wallet_method_buy_btn.addEventListener('click',()=>{
    wallet_method_buy.classList.remove('none');
    other_method_buy.classList.add('none');
})
