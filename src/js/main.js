let Utils = {
    regex: {
        validUrl: /^(https?|http):\/\/([A-Z0-9-]+\.)+[A-Z]{2,6}\/?/i,
        isHTML: /<\s?[^\>]*\/?\s?>/i
    },
    isValidURL: function(url){
        return this.regex.validUrl.test(url);
    },
    isHTML: function(content){
        return this.regex.isHTML.test(content);
    },
    getJSON: async function(url, method, body, callback){
        let data = await fetch(url, {method:method, body:JSON.stringify(body)});
        callback(await data.json());
    }
}

document.addEventListener("DOMContentLoaded", () => {
    //Capture the navigation bar and set a click listener
    let navbar = document.querySelector("#navbar");
    let icon = document.querySelector("#icon");

    icon.addEventListener("click", () => {
        if(navbar.className === "navbar") navbar.className += " show";
        else navbar.className = "navbar";
            document.body.focus();
    });
});
