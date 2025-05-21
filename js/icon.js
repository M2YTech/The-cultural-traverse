function myFunction(){
    var x = document.getElementById("myInput");
    var y = document.getElementById("hide1");
    var z = document.getElementById("hide2");

    if(x.type === 'password'){
        x.type = "text";
        z.style.display = "block";
        y.style.display = "none";
    }else{
        x.type = "password";
        z.style.display = "none";
        y.style.display = "block";
    }
}