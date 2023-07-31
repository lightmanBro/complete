const xmlhttp = new XMLHttpRequest();
xmlhttp.onload=function(){
    const data = JSON.parse(this.responseText);
    const user ={
        firstName:data.first_name,
        middleName:data.middle_name,
        lastName:data.last_name,
        Id:data.user_id
    }
    document.querySelector('.h2').innerHTML = `${user.firstName} ${user.middleName} ${user.lastName}`;
    document.querySelector('#id span').innerHTML = user.Id;
    const userId = user.Id;
    sessionStorage.setItem('UserId',userId);;
}
xmlhttp.open("GET","./php/Home.php");//this can make us send both request and response on thesame page
xmlhttp.send()

// xmlhttp.open("GET","./php/active-trade.php")