async function getJSON(url, method, body, callback){
    let data = await fetch(url, {method:method, body:JSON.stringify(body)});
    callback(await data.json());
}
