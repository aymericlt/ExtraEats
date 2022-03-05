//Gestion des boutons et vérification que la valeur ne soit ni 0, ni supérieur à la quantité disponible

let counterDisplayElem = document.querySelector('.counterSection');
let counterMinusElem = document.querySelector('.counterMinus');
let counterPlusElem = document.querySelector('.counterPlus');
let quantityAvailableElem = document.querySelector('.quantityAvailable');

let quantityAvailable = parseInt(quantityAvailableElem.innerHTML);
let count = 1;

function updateDisplay() {
    counterDisplayElem.innerHTML = count;
    document.addToCartForm.hiddenQuantity.value = count;
};

counterMinusElem.addEventListener("click",()=>{
    if (count > 1) {
        count--;
        updateDisplay();
    }

});

counterPlusElem.addEventListener("click",()=>{
    if (count < quantityAvailable) {
        count++;
        updateDisplay();
    }
});