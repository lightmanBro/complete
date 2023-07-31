/*
Php files
1. fetUser.php to fetch the user from the database.
2. Send.php to send the money
*/

const confirmBtn = document.querySelector('#confirm');
const receiverID = document.querySelector('#user_name'),
receiverName = document.querySelector('.name'),
amount = document.querySelector('#amount_to_send'),
sendMoneyBtn = document.querySelector('#form_btn'),
selected = document.querySelector('#send-select'),
confirmBox = document.querySelector('.confirm-details'),
id = document.querySelector('.details .id');


//Prevet the form from reloading the page when its submitted.
document.querySelector('#p2p-form').addEventListener('submit',(e)=>{
    e.preventDefault();
})

console.log(confirmBtn,sendMoneyBtn);
//by default the form button will be disabled.
confirmBtn.disabled = true;
//the confirm details box too will be invisible
confirmBox.classList.add('none');

sendMoneyBtn.classList.add('none');
//checking the values inside the amount input if it is a number
amount.addEventListener("input",()=>{
    const inputValue = amount.value.trim();
    if(/^\d+$/.test(inputValue)){
       console.log("value is a number")
       confirmBtn.disabled = false;
       confirmBtn.style.backgroundColor = "rgb(46, 204, 113)";
    }else{
        confirmBtn.disabled = true;
        confirmBtn.style.backgroundColor = "red";
    }
})

//checking the values inside the amount input if it is a number
receiverID.addEventListener('input',()=>{
    const inputValue = receiverID.value.trim();
    if(/^\d+$/.test(inputValue)){
       console.log("value is a number")
       confirmBtn.disabled = false;
       confirmBtn.style.backgroundColor = "rgb(46, 204, 113)";
    }else{
        confirmBtn.disabled = true;
        confirmBtn.style.backgroundColor = "red";
    }
})

// the button to confirm the user details
confirmBtn.addEventListener('click',(e)=>{
    e.preventDefault();
    fetchAsync('./php/fetchUser.php');
    console.log('confirm button');    
})

//The button to send the value.
sendMoneyBtn.addEventListener('click',(e)=>{
    e.preventDefault();
    if(amount.value == "" || selected.value ==""){
        e.preventDefault();
        window.alert('One or more value cannot be empty')
        sendMoneyBtn.disabled = true;
    }else if(parseFloat(amount.value) > parseFloat(selected.value)){
        alert('Insufficient Balance')
    }
    else{
        fetchAsyncSend('./php/Send.php');
        setTimeout(() => {
            amount.value = '';
            selected.value = '';
            receiverID.value = '';
        }, 1000);
    }
})

//function to confirm the user id if its correct.
async function fetchAsync(url) {
    const formData = new FormData();
    formData.append('user_name', receiverID.value)
    try{
        let response = await fetch(url,{
            method:'POST',
            body:formData
        });
        if(response.ok){
            let data = await response.json();
            receiverName.innerHTML= `${data.first_name} ${data.middle_name} ${data.last_name}`;
            confirmBox.classList.remove('none');
            sendMoneyBtn.classList.remove('none');
            confirmBtn.setAttribute('hidden',true);
            console.log(data);
        }
    } catch(error){
        console.log(error);
        sendMoneyBtn.setAttribute('hidden',true);
        confirmBtn.removeAttribute('hidden');
        confirmBox.classList.remove('none');
        receiverName.innerHTML= `<b>Id incorrect, Please check the user id and try again</b>`;
        sendMoneyBtn.removeAttribute('hidden');
    }
};



//function to send the data to the database
async function fetchAsyncSend(url) {
    const formData = new FormData();
    formData.append('amount', amount.value)
    formData.append('receiver_id', receiverID.value);
    formData.append('select', selected.value);
    // const urlEncodedData = new URLSearchParams(formData).toString();
    try {
        let response = await fetch(url, {
            method: 'POST',
            body:formData
        });
        if (response.ok) {
            let data =await  response.json();
            console.log(data);
            if(data['Insufficient Balance']){
                document.querySelector('.confirm-details p').innerHTML = '😥';
                receiverName.innerHTML = `<span>${data['Insufficient Balance']}</span>`;
                console.log('insufficient Balance')
            };
            if(data['Successful']){
                document.querySelector('.confirm-details p').innerHTML = '<span id="id">SUCCESFULL 😁</span>';
                receiverName.innerHTML = `<span>${data['Successful']}</span>`;
                sendMoneyBtn.setAttribute('hidden',true);
                confirmBox.classList.remove('none');
                setTimeout(() => {
                    document.querySelector('.confirm-details p').innerHTML = 'Confirm Receiver';
                    receiverName.innerHTML = '';
                }, 5000);
                confirmBtn.removeAttribute('hidden');
                // console.log(data['Succesfull']);
            };
        }
    } catch (error) {
        // confirmBox.classList.remove('none');
        receiverName.innerHTML= `<b>Id incorrect</b>`;
        console.log(error);
    }
}
