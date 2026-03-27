        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        const container = document.getElementById("cart-items");

        function renderCart() {
            container.innerHTML = "";

            if (cart.length === 0) {
                container.innerHTML = "<h2>Your cart is empty</h2>";
                updateSummary(0);

                localStorage.removeItem("cart");

                return;
            }

            let total = 0;

            cart.forEach((item, index) => {
                let itemTotal = item.price * item.quantity;
                total += itemTotal;

                let div = document.createElement("div");
                div.classList.add("cart-item");

                div.innerHTML = `
            <div class="item-image">
                <img src="${item.image}" alt="${item.name}">
            </div>

            <div class="item-details">
                <h4>${item.name}</h4>
                <p class="item-price">₹${item.orgprice}</p>
                <small>${item.size || ""} ${item.milk || ""}</small>
            </div>

            <div class="item-quantity">
                <button class="qty-btn minus" onclick="decreaseQty(${index})">-</button>
                <span class="qty">${item.quantity}</span>
                <button class="qty-btn plus" onclick="increaseQty(${index})">+</button>
            </div>

            <div class="item-total">
                <p>₹${itemTotal}</p>
            </div>

            <button class="remove-btn" onclick="removeItem(${index})">x</button>
        `;

                container.appendChild(div);
            });

            updateSummary(total);
            localStorage.setItem("cart", JSON.stringify(cart));
        }

        function increaseQty(index) {
            cart[index].quantity++;
            localStorage.setItem("cart", JSON.stringify(cart));
            renderCart();
        }

        function decreaseQty(index) {
            if (cart[index].quantity > 1) {
                cart[index].quantity--;
            } else {
                cart.splice(index, 1);
            }

            localStorage.setItem("cart", JSON.stringify(cart));
            renderCart();
        }

        function removeItem(index) {
            cart.splice(index, 1);

            localStorage.setItem("cart", JSON.stringify(cart));

            renderCart();
        }

        function updateSummary(total) {
            let subtotal = total;
            let delivery = cart.length > 0 ? 50 : 0;
            let finalTotal = subtotal + delivery;

            document.querySelector(".cart-summary").innerHTML = `
        <div class="summary-row">
            <span>Subtotal</span>
            <span>₹${subtotal}</span>
        </div>
        <div class="summary-row">
            <span>Delivery Fee</span>
            <span>₹${delivery}</span>
        </div>
        <div class="summary-row total">
            <span>Total</span>
            <span>₹${finalTotal}</span>
        </div>
    `;
        }

        renderCart();