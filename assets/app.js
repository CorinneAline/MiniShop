/*
 * Main JavaScript file!
 *
 */
import './styles/app.css';
import './styles/global.scss';

const $ = require('jquery');

require('@popperjs/core');
import 'bootstrap';

const imagesContext = require.context('./images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
imagesContext.keys().forEach(imagesContext);

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    $('#quantity').change(function() {
        let url;
        const cartAddProductButton = $('#cart-add-product');
        url = cartAddProductButton.attr('href');
        console.log(this.value);
        if(url.indexOf('?quantity=') !== -1) {
            url = `${url.substr(0, url.indexOf("?"))}?quantity=${this.value}`;
        } else {
            url = `${url}?quantity=${this.value}`;
        }
        cartAddProductButton.attr('href', url);
        console.log(url)
    })

    $('[id^=quantity-product-]').each(function() {
        $(this).change(function(event) {
            event.preventDefault();

            $('div.alert').remove();

            const productSlug = $(this).attr('data-product-slug');
            const url = $(this).attr('data-url');
            const quantity = $(this).val();

            const displayMessage = (type, message) => {
                $('.card-body').before(`<div class="alert alert-${type} mx-5 mt-5">${message}</div>`);
            }

            $.ajax({
                type: "GET",
                url: url,
                data: {productSlug, quantity},
                success: function(response){
                    if (response.error === true){
                        displayMessage('error', response.message);
                    }
                    else {
                        displayMessage('success', response.message);
                        const subTotal = $('#subtotal-' + productSlug);

                        const productPrice = subTotal.attr('data-price');
                        subTotal.text(`${(productPrice * quantity).toFixed(2)} €`);

                        $('#cart-total').text((response.total).toFixed(2));
                    }
                },
                error: function(error) {
                    displayMessage('error', response.message);
                },
            });
        })
    });
});
