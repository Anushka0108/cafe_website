function selectItem(name, price, image, type){
    const item = {
        name: name,
        price: price,
        image: image,
        type: type
    };
    localStorage.setItem("selectedItem", JSON.stringify(item));
    window.location.href = "item.php";
}

let searchTimeout;

function searchItem() {
    clearTimeout(searchTimeout);

    const input = document.getElementById("searchInput").value.trim().toLowerCase();
    const noResults = document.getElementById("noResults");
    const searchTermSpan = document.getElementById("searchTerm");

    // Reset layout
    document.querySelectorAll(".item").forEach(item => {
        item.style.display = "";
    });
    document.querySelectorAll(".menu-category").forEach(cat => {
        cat.style.display = "";
    });
    if (noResults) noResults.style.display = "none";

    if (input.length < 2) return;

    searchTimeout = setTimeout(() => {

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "search_menu.php?q=" + encodeURIComponent(input), true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    const matches = JSON.parse(xhr.responseText);

                    if (matches.length === 0) {
                        if (noResults && searchTermSpan) {
                            searchTermSpan.textContent = input;
                            noResults.style.display = "block";
                        }

                        document.querySelectorAll(".item").forEach(item => item.style.display = "none");
                        document.querySelectorAll(".menu-category").forEach(cat => cat.style.display = "none");
                        return;
                    }

                    const matchNames = new Set(
                        matches.map(m => m.itemname.toLowerCase())
                    );

                    document.querySelectorAll(".item").forEach(item => {
                        const name = item.querySelector("h4").innerText.toLowerCase();
                        item.style.display = matchNames.has(name) ? "" : "none";
                    });

                    // Handle categories
                    document.querySelectorAll(".menu-category").forEach(cat => {
                        const catItems = cat.querySelectorAll(".item");
                        const hasVisible = Array.from(catItems).some(
                            item => item.style.display !== "none"
                        );
                        cat.style.display = hasVisible ? "" : "none";
                    });

                } catch (error) {
                    console.error("JSON parse error:", error);
                }
            }
        };

        xhr.send();

    }, 300);
}