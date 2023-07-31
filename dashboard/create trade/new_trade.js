/*1. fix the css of the sell modal post button
2. fix how to fetch the user saved payment methods and show it on the trade post modal box
3. Fix the buy modal receive amount calculations.
4. Set the data inside the buy input box as the highest limit to buy.
5. Fetch the currency monitoring Api and retrieve the live data to be set as current exchange rate / dollar.*/

const receivedAmntDisplay = document.querySelector(
  ".value_display_calculated span"
);
//The success or failed modal
document.querySelector(".success").style.display = "none";

const buy_wallet_form = document.querySelector("#trade_post_buy_wallet"),
  buy_wallet_select = document.querySelector("#buy_wallet_select"),
  buy_wallet_rate = document.querySelector("#wallet_buying_rate"),
  buy_wallet_high_limit = document.querySelector("#wallet_high_limit"),
  buy_wallet_low_limit = document.querySelector("#wallet_low_limit"),
  buy_wallet_receive_slect = document.querySelector("#wallet_to_receive"),
  buy_wallet_submit = document.querySelector("#wallet_submit"),
  buy_wallet_cancel = document.querySelector("#wallet_cancel");

const buy_other_form = document.querySelector("#trade_post_buy_other"),
  buy_other_select = document.querySelector("#other_buy_select"),
  buy_other_buying_rate = document.querySelector("#other_buying_rate"),
  buy_other_low_limit = document.querySelector("#other_low_limit"),
  buy_other_high_limit = document.querySelector("#other_high_limit"),
  buy_other_wallet_to = document.querySelector("#buy_select_wallet_to"),
  buy_other_payment_method = document.querySelector("#other_payment_methods"),
  buy_other_submit = document.querySelector("#buy_other_submit"),
  buy_other_cancel = document.querySelector("#buy_other_cancel");

const sell_wallet_form = document.querySelector("#wallet_method_sell"),
  sell_wallet_select = document.querySelector("#sell_wallet_select"),
  sell_wallet_seling_rate = document.querySelector("#wallet_selling_rate"),
  sell_wallet_low_limit = document.querySelector("#sell_wallet_low_limit"),
  sell_wallet_high_limit = document.querySelector("#sell_wallet_high_limit"),
  sell_wallet_receive_select = document.querySelector(
    "#sell_wallet_to_receive"
  ),
  sell_wallet_submit = document.querySelector("#sell_wallet_submit"),
  sell_wallet_cancel = document.querySelector("#sell_wallet_cancel");

const sell_other_form = document.querySelector("#wallet_sell_other"),
  sell_other_select = document.querySelector("#sell_other_select"),
  sell_other_selling_rate = document.querySelector("#sell_other_selling_rate"),
  sell_other_low_limit = document.querySelector("#sell_other_low_limit"),
  sell_other_high_limit = document.querySelector("#sell_other_high_limit"),
  sell_other_wallet_to = document.querySelector("#sell_other_select_to"),
  sell_other_payment_method = document.querySelector(
    "#sell_other_payment_methods"
  ),
  sell_other_submit = document.querySelector("#sell_other_submit"),
  sell_other_cancel = document.querySelector("#sell_other_cancel");

const inputs = [
  buy_wallet_rate,
  buy_wallet_high_limit,
  buy_wallet_low_limit,
  buy_other_buying_rate,
  buy_other_low_limit,
  buy_other_high_limit,
  sell_wallet_seling_rate,
  sell_wallet_low_limit,
  sell_wallet_high_limit,
  sell_other_selling_rate,
  sell_other_low_limit,
  sell_other_high_limit,
];

inputs.forEach((input) => {
  input.addEventListener("input", () => {
    const inputValue = input.value.trim();
    if (/^\d+$/.test(inputValue)) {
      console.log("value is a number");
      document.querySelectorAll(' button[type="submit"]').forEach((button) => {
        button.style.backgroundColor = "rgb(46, 204, 113)";
      });
    } else {
      document.querySelectorAll(' button[type="submit"]').forEach((button) => {
        button.disabled = true;
        button.style.backgroundColor = "red";
      });
    }
  });
});

////////////////////////////////////////////////////////////
//Function to get and send data to and from database.
async function loadData(frm) {
  try {
    const response = await fetch("../php/new_trade.php", {
      method: "POST",
      body: new URLSearchParams(frm).toString(),
    });
    console.log(frm);
    const data = await response.text();
    console.log(data);
  } catch (error) {
    console.error(error);
  }
}

//Fetching the curency wallet values and using it on the page;
const walletAvailable = document.querySelector(".buy_wallet_value_display");

//Displaying the value of the selected currency inside a container;
const select = [
  document.querySelector("#other_buy_select"),
  document.querySelector("#buy_wallet_select"),
  document.querySelector("#sell_wallet_select"),
  document.querySelector("#sell_other_select"),
];
select[3].addEventListener("click", (e) => {
  document.querySelector(".sell_other_value_display").innerHTML =
    e.target.value;
  console.log(e.target.value);
});
select[2].addEventListener("click", (e) => {
  document.querySelector(".sell_wallet_value_display").innerHTML =
    e.target.value;
  console.log(e.target.value);
});
select[1].addEventListener("click", (e) => {
  document.querySelector(".buy_wallet_value_display").innerHTML =
    e.target.value;
  console.log(e.target.value);
});
select[0].addEventListener("click", (e) => {
  document.querySelector(".buy_other_value_display span").innerHTML =
    e.target.value;
  console.log(e.target.value);
});

const currency = [
  document.querySelector("#available_curr"),
  document.querySelector("#other_available_curr"),
  document.querySelector("#sell_available_curr"),
  document.querySelector("#sell_other_available_curr"),
  document.querySelector("#buy_other_available_curr"),
  document.querySelector("#sell_other_available_curr_to"),
];
console.log(currency[1]);

//Setting wallet value data as option values;
const useData = function (data) {
  for (const key in data) {
    const curr = {
      walletName: key,
      walletValue: data[key],
    };
    console.table(curr);
    var availableCurrOption = `<option  name="${curr.walletName}" value="${curr.walletName} ${curr.walletValue}">${curr.walletName}</option>`;
    currency.forEach((available) =>
      available.insertAdjacentHTML("afterend", availableCurrOption)
    );
  }
};

fetch("../php/user-wallet.php")
  .then((response) => response.json())
  .then((data) => useData(data))
  .catch((err) => console.log(err));

//Submitting the form function
const submitForm = function (high_limit, rate, low_limit, btn) {
  if (
    parseFloat(high_limit.value) >
    parseFloat(walletAvailable.innerHTML.split(" ")[1])
  ) {
    e.preventDefault();
    alert("You cannot post a sell order higher than your balance 😥");
    btn.disabled = true;
    return false;
  } else if (
    high_limit.value == "" ||
    low_limit.value == "" ||
    rate.value == ""
  ) {
    alert("Make sure all fields are filled");
    return false;
  } else {
    return true;
  }
};

//The setimeout function makes sure the whole dom content is loaded before the values is submitted;
buy_wallet_submit.addEventListener("click", (e) => {
  e.preventDefault();
  async function loadData() {
    const buyformData = new FormData();
    buyformData.append("type", "buy");
    buyformData.append("wallet", walletAvailable.innerHTML.split(" ")[0]);
    buyformData.append("rate", buy_wallet_rate.value.trim());
    buyformData.append("low_limit", buy_wallet_low_limit.value.trim());
    buyformData.append("high_limit", buy_wallet_high_limit.value.trim());
    buyformData.append("method", buy_wallet_receive_slect.value.trim());
    try {
      fetch("../php/new_trade.php", { method: "POST", body: buyformData })
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          if (data.Successful) {
            console.log(data.Successful);
            document.querySelector("#buy_container").style.display = "none";
            document.querySelector(".success").style.display = "block";
            document.querySelector(
              ".posted_display"
            ).innerHTML = `<div><span>Currency</span>   <span>${data.Successful.wallet}</span></div>
			  <div><span>Rate</span>   <span>${data.Successful.rate}</span></div>
			  <div><span>Lowest</span>   <span>${data.Successful.low_limit}</span></div>
			  <div><span>Highest</span>   <span>${data.Successful.high_limit}</span></div>
			  <div><span>Receive</span> <span>${data.Successful.payment_method}</span></div>`;
            setTimeout(() => {
              document.querySelector("#buy_container").style.display = "block";
              document.querySelector(".success").style.display = "none";
            }, 5000);
          } else if (data.Failed) {
            document.querySelector("#buy_container").classList.add("none");
            document.querySelector(".success").style.display = "block";
            document.querySelector(".success").innerHTML = data.Failed;
            setTimeout(() => {
              document.querySelector(".success").style.display = "none";
            }, 5000);
            document.querySelector("#buy_container").remove("none");
          }
        });
    } catch (error) {
      console.error(error);
    }
  }
  submitForm(
    buy_wallet_high_limit,
    buy_wallet_rate,
    buy_wallet_low_limit,
    buy_wallet_submit
  );
  loadData();
  // buy_wallet_form.reset();
});

buy_other_submit.addEventListener("click", (e) => {
  e.preventDefault();
  const buy_otherformData = new FormData();
  buy_otherformData.append("type", "buy_other");
  buy_otherformData.append("wallet", buy_other_select.value.split(" ")[0]);
  buy_otherformData.append("rate", buy_other_buying_rate.value.trim());
  buy_otherformData.append("low_limit", buy_other_low_limit.value.trim());
  buy_otherformData.append("high_limit", buy_other_high_limit.value.trim());
  buy_otherformData.append(
    "wallet_to",
    buy_other_wallet_to.value.split(" ")[0]
  );
  buy_otherformData.append("method", buy_other_payment_method.value.trim());
  console.log(walletAvailable.innerHTML.split(" ")[0]);
  async function loadData() {
    try {
      fetch("../php/new_trade.php", { method: "POST", body: buy_otherformData })
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          if (data.Successful) {
            console.log(data.Successful);
            document.querySelector("#buy_container").classList.add("none");
            document.querySelector(".success").style.display = "block";
            document.querySelector(
              ".posted_display"
            ).innerHTML = `<div><span>Currency</span>   <span>${data.Successful.wallet}</span></div>
					<div><span>Rate</span>   <span>${data.Successful.rate}</span></div>
					<div><span>Lowest</span>   <span>${data.Successful.low_limit}</span></div>
					<div><span>Highest</span>   <span>${data.Successful.high_limit}</span></div>
					<div><span>Receive</span> <span>${data.Successful.payment_method}</span></div>`;
            setTimeout(() => {
              document.querySelector(".success").style.display = "none";
            }, 5000);
            document.querySelector("#buy_container").classList.remove("none");
          } else if (data.Failed) {
            document.querySelector("#buy_container").classList.add("none");
            document.querySelector(".success").style.display = "block";
            document.querySelector(".success").innerHTML = data.Failed;
            setTimeout(() => {
              document.querySelector(".success").style.display = "none";
            }, 5000);
            // document.querySelector('#buy_container').remove('none');
          }
        });
    } catch (error) {
      console.error(error);
    }
  }
  submitForm(
    buy_other_high_limit,
    buy_other_buying_rate,
    buy_other_low_limit,
    buy_other_submit,
    e.target
  );
  buy_other_form.reset();
  loadData();
});
// Buy other method;

//Calculating the input values onClick
sell_wallet_high_limit.addEventListener("input", (e) => {
  document.querySelector("#sell_wallet_receive span").innerHTML =
    sell_wallet_seling_rate.value * 1 * (e.target.value * 1);
});
setTimeout(() => {
  sell_wallet_submit.addEventListener("click", (e) => {
    e.preventDefault();
    const sellformData = new FormData();
    sellformData.append("type", "sell");
    sellformData.append("wallet", sell_wallet_select.value.split(" ")[0]);
    sellformData.append("rate", sell_wallet_seling_rate.value.trim());
    sellformData.append("low_limit", sell_wallet_low_limit.value.trim());
    sellformData.append("high_limit", sell_wallet_high_limit.value.trim());
    sellformData.append("method", sell_wallet_receive_select.value.trim());
    console.log(sell_wallet_receive_select.value.trim());
    async function loadData() {
      try {
        fetch("../php/new_trade.php", { method: "POST", body: sellformData })
          .then((response) => response.text())
          .then((data) => {
            console.log(data);
            if (data.Successful) {
              console.log(data.Successful);
              document.querySelector("#buy_container").style.display = "none";
              document.querySelector(".success").style.display = "block";
              document.querySelector(
                ".posted_display"
              ).innerHTML = `<div><span>Currency</span>   <span>${data.Successful.wallet}</span></div>
			<div><span>Rate</span>   <span>${data.Successful.rate}</span></div>
			<div><span>Lowest</span>   <span>${data.Successful.low_limit}</span></div>
			<div><span>Highest</span>   <span>${data.Successful.high_limit}</span></div>
			<div><span>Receive</span> <span>${data.Successful.payment_method}</span></div>`;
              setTimeout(() => {
                document.querySelector("#buy_container").style.display =
                  "block";
                document.querySelector(".success").style.display = "none";
              }, 5000);
            } else if (data.Failed) {
              document.querySelector("#buy_container").classList.add("none");
              document.querySelector(".success").style.display = "block";
              document.querySelector(".success").innerHTML = data.Failed;
              setTimeout(() => {
                document.querySelector(".success").style.display = "none";
              }, 5000);
              document.querySelector("#buy_container").remove("none");
            }
          });
      } catch (error) {
        console.log(error);
      }
    }
    submitForm(
      sell_wallet_high_limit,
      sell_wallet_seling_rate,
      sell_wallet_low_limit,
      sell_wallet_submit,
      e.target
    );
    loadData();
    // sell_wallet_form.reset();
  });
}, 1000);

//Sell other method
sell_other_submit.addEventListener("click", (e) => {
  e.preventDefault();
  const sell_other_formData = new FormData();
  sell_other_formData.append("type", "sell_other");
  sell_other_formData.append("wallet", sell_other_select.value.split(" ")[0]);
  sell_other_formData.append("rate", sell_other_selling_rate.value.trim());
  sell_other_formData.append("low_limit", sell_other_low_limit.value.trim());
  sell_other_formData.append("high_limit", sell_other_high_limit.value.trim());
  sell_other_formData.append(
    "wallet_to",
    sell_other_wallet_to.value.split(" ")[0]
  );
  sell_other_formData.append("method", sell_other_payment_method.value.trim());
  async function loadData() {
    try {
      fetch("../php/new_trade.php", {
        method: "POST",
        body: sell_other_formData,
      })
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          if (data.Successful) {
            console.log(data.Successful);
            document.querySelector("#sell").style.display = "none";
            document.querySelector(".sell_success").style.display = "block";
            document.querySelector(
              ".sell_posted_display"
            ).innerHTML = `<div><span>Currency</span>   <span>${data.Successful.wallet}</span></div>
				<div><span>Rate</span>   <span>${data.Successful.rate}</span></div>
				<div><span>Lowest</span>   <span>${data.Successful.low_limit}</span></div>
				<div><span>Highest</span>   <span>${data.Successful.high_limit}</span></div>
				<div><span>Receive</span> <span>${data.Successful.payment_method}</span></div>`;
            setTimeout(() => {
              document.querySelector("#sell").style.display = "block";
              document.querySelector(".success").style.display = "none";
            }, 5000);
          } else if (data.Failed) {
            document.querySelector("#sell").classList.add("none");
            document.querySelector(".sell_success").style.display = "block";
            document.querySelector(".sell_success").innerHTML = data.Failed;
            setTimeout(() => {
              document.querySelector(".sell_success").style.display = "none";
            }, 5000);
            document.querySelector("#sell").remove("none");
          }
        });
    } catch (error) {
      console.log(error);
    }
  }
  submitForm(
    sell_other_high_limit,
    sell_other_selling_rate,
    sell_other_low_limit,
    sell_other_submit,
    e.target
  );
  sell_other_form.reset();
  loadData();
});

//Getting user saved payment receiving methods from the database
const paymentOptions = document.querySelector("#other_payment_methods");
const sellPaymentOptions = document.querySelector(
  "#sell_other_payment_methods"
);
//Set this as the other method select option values
fetch("../php/payment-options.php")
  .then((response) => response.json())
  .then((data) => {
    console.table(data);
    data.forEach((datas) => {
      if (datas["apple_email"]) {
        var html = ` <option value="Apple">Apple pay</option>`;
        paymentOptions.insertAdjacentHTML("afterbegin", html);
        sellPaymentOptions.insertAdjacentHTML("beforeend", html);
      }
      if (datas["bank_name"]) {
        var html = `<option value="bank">Bank</option>`;
        paymentOptions.insertAdjacentHTML("beforeend", html);
        sellPaymentOptions.insertAdjacentHTML("beforeend", html);
        console.log(datas["bank_name"]);
      }
      if (datas["google_address"]) {
        var html = ` <option value="Google pay">Google Pay</option>`;
        paymentOptions.insertAdjacentHTML("beforeend", html);
        sellPaymentOptions.insertAdjacentHTML("beforeend", html);
      }
      if (datas["wallet_Address"]) {
        var html = ` <option value="pix">Pi coin</option>`;
        paymentOptions.insertAdjacentHTML("beforeend", html);
        sellPaymentOptions.insertAdjacentHTML("beforeend", html);
      }
      if (datas["user_name"]) {
        var html = ` <option value="Mobile money">Mobile money</option>`;
        paymentOptions.insertAdjacentHTML("beforeend", html);
        sellPaymentOptions.insertAdjacentHTML("beforeend", html);
      }
      if (datas["chipper"]) {
        var html = ` <option value="Chipper cash">Chipper cash</option>`;
        paymentOptions.insertAdjacentHTML("beforeend", html);
        sellPaymentOptions.insertAdjacentHTML("beforeend", html);
      }
      if (datas["paypal_address"]) {
        var html = ` <option value="Paypal">PayPal</option>`;
        paymentOptions.insertAdjacentHTML("beforeend", html);
        sellPaymentOptions.insertAdjacentHTML("beforeend", html);
      }
      if (datas["skrill_address"]) {
        var html = ` <option value="Skrill">Skrill</option>`;
        paymentOptions.insertAdjacentHTML("beforeend", html);
        sellPaymentOptions.insertAdjacentHTML("beforeend", html);
      }
    });
    // console.log(data);
  })
  .catch((err) => console.log(err));
