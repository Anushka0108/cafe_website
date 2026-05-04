function selectItem(name, price, image, type){
    const item={
        name:name,
        price:price,
        image:image,
        type:type
    };
    localStorage.setItem("selectedItem",JSON.stringify(item));
    window.location.href="item.php";
}

function searchItem() {
    let input = document.getElementById("searchInput").value.toLowerCase().trim();
    let items = document.querySelectorAll(".item");
    let categories = document.querySelectorAll(".menu-category");

    items.forEach(item => {
        let name = item.querySelector("h4").innerText.toLowerCase();

        if (name.includes(input)) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });

    categories.forEach(cat => {
        let visible = false;
        let catItems = cat.querySelectorAll(".item");

        catItems.forEach(item => {
            if (item.style.display !== "none") {
                visible = true;
            }
        });

        cat.style.display = visible ? "flex" : "none";
    });
}