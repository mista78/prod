const {Locale} = window.cegos_scripts;
console.log(Locale('ago'));

const selector = (element, root) => {
    const select = root || document;
    return [...select.querySelectorAll(element)];
};
const useCustomFetch = async (url, callback) => {
    const request = await fetch(url);
    const response = await request.text();
    callback && callback(response);
};

const buildHead = (data) => {
    const div = document.createElement('div');
    div.innerHTML = data;
    const style = selector("link[rel~='stylesheet']", div);

    document.head.prepend(...style);

};

const btn = document.getElementById("btn-toggle");
const tags = [...document.getElementsByClassName("Section__mag-tag")];
if (tags.length !== 0) {
    tags.map(tag => {
        if (tag.innerHTML !== "") {
            btn.addEventListener("click", function (event) {
                if (!btn.classList.contains("active-header")) {
                    btn.classList.add("active-header");
                    tags[0].classList.add("hide");
                    btn.innerText = Locale('Show more');
                    btn.style.color = "#056F9F";
                } else {
                    btn.classList.remove("active-header");
                    tags[0].classList.remove("hide");
                    btn.innerText = Locale('Show less');
                    btn.style.color = "black";

                }
            });
        }
    });
} else {
    btn?.remove();
};