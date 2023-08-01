var nairaform = document.getElementById('bank_account_settings'),
Chipper = document.getElementById('chipper_account_settings'),
applePay = document.getElementById('apple_pay_account_settings'),
momoAcc = document.getElementById('momo_account_settings'),
piWallet = document.getElementById('pi_account_settings'),
PayPal = document.getElementById('paypal_account_settings'),
Skrill = document.getElementById('skrill_account_settings'),
cashApp = document.getElementById('cashApp_account_settings'),
googlePay = document.getElementById('googlpay_account_settings')



const payments = [
  (bank = document.querySelector("#bank")),
  (chipper = document.querySelector("#chipper")),
  (apple = document.querySelector("#apple")),
  (momo = document.querySelector("#momo")),
  (pi = document.querySelector("#pi")),
  (payPal = document.querySelector("#paypal")),
  (skrill = document.querySelector("#skrill")),
  (google = document.querySelector("#google")),
];

payments.forEach((payment) => {
  payment.style.display = "none";
});

const selected = document
  .querySelector("#select")
  .addEventListener("click", (e) => {
    switch (e.target.value) {
      case "bank":
        payments[0].style.display = "block";
        console.log("bank");
        break;
      case "chipper":
        payments[1].style.display = "block";
        console.log("chipper");
        break;
      case "apple_pay":
        payments[2].style.display = "block";
        console.log("apple");
        break;
      case "momo":
        payments[3].style.display = "block";
        break;
      case "pi":
        payments[4].style.display = "block";
        break;
      case "paypal":
        payments[5].style.display = "block";
        break;
      case "skrill":
        payments[6].style.display = "block";
        break;
      case "googlepay":
        payments[7].style.display = "block";
      default:
        break;
    }
  });

function isNumber(inpt,formBtn,warning){
  const inputValue = inpt.value.trim();
  warning.style.display = 'none';
  if (/^\d+$/.test(inputValue)) {
    console.log("value is a number");
    formBtn.disabled = false;
    formBtn.style.backgroundColor = "rgb(46, 204, 113)";
    warning.style.display = 'none';
  } else {
    formBtn.disabled = true;
    warning.style.display = 'block'
    formBtn.style.backgroundColor = "red";
  }
}
const warning = document.querySelector('#warning');
const bankwarning = document.querySelector('#acc_warning');
const acc_btn = document.querySelector('#acc_btn');
const momoBtn = document.querySelector('#momo_btn');
const bankNum = document.querySelector('#acc_number')
const number = document.querySelector('#number_momo')


number.addEventListener('input',()=>{
isNumber(number,momoBtn,warning);
})
bankNum.addEventListener('input',()=>{
  isNumber(bankNum,acc_btn,bankwarning)
})
