//Get the model
var model = document.getElementById("myModel");

//Button that opens the model
var btn = document.getElementById("book_table");

//<span> element that closes the model
var span = document.getElementsByClassName("close")[0];

//open the model
btn.onclick = function() {
    model.style.display = "block";
}

//close the model
span.onclick = function() {
    model.style.display = "none";
}

//clicks anywhere outside of model,close it
window.onclick = function(event) {
    if(event.target == model) {
        model.style.display = "none";
    }
}
