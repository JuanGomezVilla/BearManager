//Post table
let postTable;

//When the DOM content has been loaded
window.addEventListener("DOMContentLoaded", () => {
    //Capture of document elements
    postTable = document.querySelector("#postTable");

    //Click detector on the table
    postTable.addEventListener("click", function(event){
        //If the element clicked is a button
        if(event.target.nodeName === "BUTTON"){
            //The clicked button is saved in a variable, and its "id-post" attribute too
            let buttonPressed = event.target;
            let idPostAttributeChangeStatus = buttonPressed.getAttribute("id-post-change-status");
            let idPostAttributeRemove = buttonPressed.getAttribute("id-post-remove");

            //If the content of the attribute is not null
            if(idPostAttributeChangeStatus != null){
                //Disable the button
                buttonPressed.disabled = true;

                //Make a JSON call to modify the data
                getJSON("http://localhost/api/changeStatusPost/", "PUT", {idpost:idPostAttributeChangeStatus}, function(data){
                    //Set an icon based on the result and enable the button
                    buttonPressed.innerHTML = data.result ? "&#128077;" : "&#128078;";
                    buttonPressed.disabled = false;
                });
            } else if(idPostAttributeRemove != null){
                //Disable the button
                buttonPressed.disabled = true;

                //Make a JSON call to modify the data
                getJSON("http://localhost/api/deletePost/", "DELETE", {idpost:idPostAttributeRemove}, function(data){  
                    //Reload the webpage
                    location.reload();
                });
            }
        }
    });
});