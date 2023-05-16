//Objects in the document
let buttonMoreInfo;

//When the DOM content is loaded
window.addEventListener("DOMContentLoaded", () => {
    //Capturing objects in the document
    buttonMoreInfo = document.querySelector("#buttonMoreInfo");

    //Mouse click listener
    buttonMoreInfo.addEventListener("click", () => {
        //Send to the Frequent Asked Questions section
        location.href = "/faq";
    });
});