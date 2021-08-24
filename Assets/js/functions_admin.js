
//FUNCION QUE PERMITE SOLO INGRESAR NUMEROS
function controlTag(e) {
    //KEYCODE CAPTURAMOS LO QUE ESCRIBIMOS
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; 
    else if (tecla==0||tecla==9)  return true;
    //permite numeros del 0 al 9
    patron =/[0-9\s]/;
    //PASAMOS COMO PARAMETRO LAS TECLAS QUE PRESIONAMOS
    n = String.fromCharCode(tecla);
    //VERIFICA QUE INGRESAMOS
    return patron.test(n); 
}

//FUNCION QUE PERMITE SOLO INGRESAR LETRAS MAYUSCULAS Y MINUSCULAS
function testText(txtString){
    var stringText = new RegExp(/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]+$/);
    if(stringText.test(txtString)){
        return true;
    }else{
        return false;
    }
}

//FUNCION QUE PERMITE SOLO INGRESAR NUMEROS DEL 0 AL 9
function testEntero(intCant){
    var intCantidad = new RegExp(/^([0-9])*$/);
    if(intCantidad.test(intCant)){
        return true;
    }else{
        return false;
    }
}

//FUNCION PARA VALIDAR EL CORREO ELECTRONICO
function fntEmailValidate(email){
    var stringEmail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    if (stringEmail.test(email) == false){
        return false;
    }else{
        return true;
    }
}
//VALIDA TEXTO
function fntValidText(){
    let validText = document.querySelectorAll(".validText");
    validText.forEach(function(validText) {
        validText.addEventListener('keyup', function(){
            let inputValue = this.value;
            if(!testText(inputValue)){
                this.classList.add('is-invalid');
            }else{
                this.classList.remove('is-invalid');
            }                
        });
    });
}
//VALIDA NUMERO
function fntValidNumber(){
    let validNumber = document.querySelectorAll(".validNumber");
    validNumber.forEach(function(validNumber) {
        validNumber.addEventListener('keyup', function(){
            let inputValue = this.value;
            if(!testEntero(inputValue)){
                this.classList.add('is-invalid');
            }else{
                this.classList.remove('is-invalid');
            }                
        });
    });
}


function fntValidEmail(){
    let validEmail = document.querySelectorAll(".validEmail");
    validEmail.forEach(function(validEmail) {
        validEmail.addEventListener('keyup', function(){
            let inputValue = this.value;
            if(!fntEmailValidate(inputValue)){
                this.classList.add('is-invalid');
            }else{
                this.classList.remove('is-invalid');
            }                
        });
    });
}


window.addEventListener('load', function() {
    fntValidText();
    fntValidEmail(); 
    fntValidNumber();
}, false);