const currencySelect = document.getElementById('currency-select');
const currencyForms = document.querySelectorAll('.form');

currencySelect.addEventListener('change', function() {
    const selectedCurrency = this.value;
    currencyForms.forEach(function(form) {
        form.style.display = 'none';
    });

    const selectedForm = document.querySelector(`.${selectedCurrency.toLowerCase()}-wallet.form`);
    if (selectedForm) {
        selectedForm.style.display = 'block';
    }
});
const bankTransferBtn = document.getElementById('bank-transfer-btn');
const cardBtn = document.getElementById('card-btn');
const bankTransferForm = document.querySelector('.bank-transfer-form.form');
const cardForm = document.querySelector('.card-form.form');

bankTransferBtn.addEventListener('click', function() {
    bankTransferForm.style.display = 'block';
    cardForm.style.display = 'none';
});

cardBtn.addEventListener('click', function() {
    bankTransferForm.style.display = 'none';
    cardForm.style.display = 'block';
});