//VER INFORMACION SOBRE PRODUCTOS
(function(){
    const infoProduct = $("#infoProduct");
    $( "a.open-info-product" ).click(function(event) {
        event.preventDefault();
        const id = $( this ).attr('data-id');
        const href = `/api/show/${id}`;

        $.get( href, function(data) {
            $( infoProduct ).find( "#productName" ).text(data.name);
            $( infoProduct ).find( "#productPrice" ).text(data.price);
            $( infoProduct ).find( "#productImage" ).attr("src", "/img/" + data.photo);
            infoProduct.modal('show');
        })
    });
    $(".closeInfoProduct").click(function (e) {
        infoProduct.modal('hide');
    });
})();

// AÑADIR UN PRODUCTO AL CARRITO
(function(){
    const cart = $("#cart-modal");
    $( "a.open-cart-product" ).click(function(event) {
        event.preventDefault();
        const id = $( this ).attr('data-id');
        const href = `/cart/add/${id}`;

        $.get( href, function(data) {
            $( cart ).find( "#productImage" ).attr("src", "/img/" + data.photo);
            cart.modal('show');
        })
    });
    $(".closeCart").click(function (e) {
        cart.modal('hide');
    });
})();

// ACTUALIZAR UN PRODUCTO DEL CARRITO
(function(){
    const totalItems = $("#totalItems");
    const cart = $("#cart-modal");
    $( "a.open-cart-product" ).click(function(event) {
        event.preventDefault();
        const id = $( this ).attr('data-id');
        const href = `/cart/add/${id}`;

        $.get( href, function(data) {
            $( cart ).find( "#productImage" ).attr("src", "/img/" + data.photo);
            cart.modal('show');
            const updateButton = $( cart ).find( "#data-container .update" )
            updateButton.unbind();
            updateButton.click(function(event){
                event.preventDefault();
                let hrefUpdate = "/cart/update/" + id;
                //Hacer un post a update con la cantidad introducida por el usuario
                hrefUpdate += "/" + $( cart ).find( "#quantity" ).val();
                $.post( hrefUpdate, {}, function(data) {
                    totalItems.text(data.totalItems);
                });
            });
        })
    });
    $(".closeCart").click(function (e) {
        cart.modal('hide');
    });
})();

// ELIMINAR UN PRODUCTO DEL CARRITO
(function(){
    const totalItems = $("#totalItems");
    $( "a.remove-item" ).click(function(event) {
        const id = $( this ).attr('data-id');
        const href = `/cart/remove/${id}`;

        $.post( href, function(data) {
            totalItems.text(data.totalItems);
            $( "#totalCart" ).text(new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(data.total));
            $(`#item-${id}`).hide('slow', function(){ $(`#item-${id}`).remove(); });
        })
    });
})();