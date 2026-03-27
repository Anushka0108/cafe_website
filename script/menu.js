function selectItem(name, price, image,type){
    const item={
        name:name,
        price:price,
        image:image,
        type:type
    };
    localStorage.setItem("selectedItem",JSON.stringify(item));
    window.location.href="item.html";
}