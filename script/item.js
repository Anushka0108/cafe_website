const item = JSON.parse(localStorage.getItem("selectedItem"));

    if (item) {
        document.querySelector("h2").innerText = item.name;
        document.querySelector(".info-section p").innerText = "Base Price: ₹" + item.price;
        document.querySelector(".image-section img").src = item.image;

        // All sections
        const sections = {
            size: document.getElementById("size-section"),
            milk: document.getElementById("milk-section"),
            sugar: document.getElementById("sugar-section"),
            extras: document.getElementById("extras-section"),
            pizza: document.getElementById("pizza-section"),
            quantity: document.getElementById("quantity-section"),
            instruction: document.getElementById("instruction-section")
        };

        // Hide everything first
        Object.values(sections).forEach(sec => sec.classList.add("hidden"));

        // Show based on type
        if (item.type === "coffee") {
            sections.size.classList.remove("hidden");
            sections.milk.classList.remove("hidden");
            sections.sugar.classList.remove("hidden");
            sections.quantity.classList.remove("hidden");
            sections.instruction.classList.remove("hidden");
        }

        else if (item.type === "pizza") {
            sections.size.classList.remove("hidden");
            sections.pizza.classList.remove("hidden");
            sections.quantity.classList.remove("hidden");
            sections.instruction.classList.remove("hidden");
        }

        else if (item.type === "sandwich") {
            sections.size.classList.remove("hidden");
            sections.extras.classList.remove("hidden");
            sections.quantity.classList.remove("hidden");
            sections.instruction.classList.remove("hidden");
        }

        else if (item.type === "bakery") {
            sections.quantity.classList.remove("hidden");
            sections.instruction.classList.remove("hidden");
        }
    }
    function calculatePrice(item) {
        let price = item.price;

        // SIZE
        let sizeEl = document.querySelector('input[name="size"]:checked');
        if (sizeEl) {
            let text = sizeEl.nextSibling.textContent;
            if (text.includes("+₹30")) price += 30;
            if (text.includes("+₹60")) price += 60;
        }

        // MILK
        let milkEl = document.querySelector('input[name="milk"]:checked');
        if (milkEl) {
            let text = milkEl.nextSibling.textContent;
            if (text.includes("+₹20")) price += 20;
        }

        // EXTRAS
        document.querySelectorAll('#extras-section input:checked').forEach(e => {
            let text = e.nextSibling.textContent;
            let match = text.match(/\+₹(\d+)/);
            if (match) price += Number(match[1]);
        });

        // PIZZA TOPPINGS
        document.querySelectorAll('#pizza-section input:checked').forEach(e => {
            let text = e.nextSibling.textContent;
            let match = text.match(/\+₹(\d+)/);
            if (match) price += Number(match[1]);
        });

        return price;
    }


    function addToCart(btn) {

        if (btn) btn.disabled = true;

        const item = JSON.parse(localStorage.getItem("selectedItem"));

        let quantity = Number(document.querySelector("#quantity-section input").value);

        let size = "";
        let milk = "";
        let sugar = "";
        let extras = [];
        let toppings = [];

        if (item.type === "coffee") {
            size = document.querySelector('input[name="size"]:checked')?.nextSibling.textContent.trim() || "";
            milk = document.querySelector('input[name="milk"]:checked')?.nextSibling.textContent.trim() || "";
            sugar = document.querySelector('input[name="sugar"]:checked')?.nextSibling.textContent.trim() || "";

            document.querySelectorAll('#extras-section input:checked').forEach(e => {
                extras.push(e.nextSibling.textContent.trim());
            });
        }

        else if (item.type === "pizza") {
            size = document.querySelector('input[name="size"]:checked')?.nextSibling.textContent.trim() || "";

            document.querySelectorAll('#pizza-section input:checked').forEach(e => {
                toppings.push(e.nextSibling.textContent.trim());
            });
        }

        else if (item.type === "sandwich") {
            size = document.querySelector('input[name="size"]:checked')?.nextSibling.textContent.trim() || "";

            document.querySelectorAll('#extras-section input:checked').forEach(e => {
                extras.push(e.nextSibling.textContent.trim());
            });
        }

        let finalPrice = calculatePrice(item);

        const cartItem = {
            name: item.name,
            orgprice: item.price,
            price: finalPrice,
            quantity: quantity,
            size: size,
            milk: milk,
            sugar: sugar,
            extras: extras,
            toppings: toppings,
            image: item.image,
            type: item.type
        };

        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        cart.push(cartItem);

        localStorage.setItem("cart", JSON.stringify(cart));

        setTimeout(() => {
            window.location.href = "menu.html";
        }, 300);
    }