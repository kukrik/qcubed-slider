
const widthInput = document.querySelector(".width");
const heightInput = document.querySelector(".height");

const edit_clicks = document.querySelectorAll(".edit");
//console.log(edit_clicks)

edit_clicks.forEach(function (event) {
    event.addEventListener("click", function() {

        const img = document.querySelector("img");

        console.log("IMG: " + img)

        //const imgId = document.querySelector("img");

        const aspectRatio = img.naturalWidth / img.naturalHeight;

        widthInput.value = img.naturalWidth;
        heightInput.value = img.naturalHeight;

        console.log("WIDTH: " + img.naturalWidth)
        console.log("HEIGHT: " + img.naturalHeight)

    });
});

widthInput.addEventListener("keyup", function() {
    if (widthInput.value === "") {
    const img = document.querySelector("img");
    const aspectRatio = img.naturalWidth / img.naturalHeight;
    const height = widthInput.value / aspectRatio;
    heightInput.value = Math.floor(height);
    }
});

heightInput.addEventListener("keyup", function() {
    //if (heightInput.value === "") {
    const img = document.querySelector("img");
    const aspectRatio = img.naturalWidth / img.naturalHeight;
    const width = heightInput.value * aspectRatio;
    widthInput.value = Math.floor(width);
    //}
});



for (let i = 0, len = image.length; i < len; i++) {
    let aspectRatio = image[i].naturalWidth / image[i].naturalHeight;

    widthInput.value = image[i].naturalWidth;
    heightInput.value = image[i].naturalHeight;

    console.log("WIDTH: " + image[i].naturalWidth)
    console.log("HEIGHT: " + image[i].naturalHeight)
}

widthInput.addEventListener("keyup", function() {
    //if (widthInput.value === "") {
    const height = widthInput.value / aspectRatio;
    heightInput.value = Math.floor(height);
    //}
});

heightInput.addEventListener("keyup", function() {
    //if (heightInput.value === "") {
    const width = heightInput.value * aspectRatio;
    widthInput.value = Math.floor(width);
    //}
});