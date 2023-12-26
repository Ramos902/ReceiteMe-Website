let count = 1;
const interval = setInterval(nextImage, 5000);

function nextImage() {
    count++;
    if (count > 3) {
        count = 1;
    }
    document.getElementById("radio" + count).checked = true;
}

