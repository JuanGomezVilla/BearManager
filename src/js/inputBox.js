//Form elements (to publish a post)
let buttonPublish, inputImage, inputTitle, inputObservations, inputContent;

//Function to update the state of the button
function togglePublishButton(){
    //Changes the state to enabled if the values ​​are valid
    buttonPublish.disabled = !(
        Utils.isValidURL(inputImage.value) &&
        !Utils.isHTML(inputTitle.value) &&
        !Utils.isHTML(inputObservations.value) &&
        !Utils.isHTML(inputContent.value)
    );
}

//As soon as the content of the DOM has been loaded
window.addEventListener("DOMContentLoaded", () => {
    buttonPublish = document.querySelector("#buttonPublish");
    inputImage = document.querySelector("#inputImage");
    inputTitle = document.querySelector("#inputTitle");
    inputObservations = document.querySelector("#inputObservations");
    inputContent = document.querySelector("#inputContent");

    inputImage.addEventListener("input", function(){
        this.className = Utils.isValidURL(this.value) ? "" : "error-input";
        togglePublishButton();
    });

    [
        inputTitle, inputObservations, inputContent
    ].forEach(item => {
        item.addEventListener("input", function(){
            this.className = Utils.isHTML(this.value) ? "error-input" : "";
            togglePublishButton();
        });
        
    })
});