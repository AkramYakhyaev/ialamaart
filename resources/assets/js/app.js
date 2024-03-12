'use strict';

function ready() {

    // уменьшает/увеличивает количество элементов в корзине (цифра в хедере)
    function updateCartCount(action) {

        var cartCountBlock = document.getElementsByClassName('header__cart-count')[0];
        var stickyCartBlock = document.getElementsByClassName('sticky-cart')[0].getElementsByTagName('span')[0];
        var mobileCartBlock = document.getElementsByClassName('mobile-header__cart')[0].getElementsByTagName('span')[0];
        var currentCount = parseInt(cartCountBlock.getElementsByTagName('span')[0].innerHTML, 10);

        if (action) {
            var newCount = currentCount + 1;
        } else {
            var newCount = currentCount - 1;
        }

        cartCountBlock.innerHTML = stickyCartBlock.innerHTML = mobileCartBlock.innerHTML = '<span>' + newCount + '</span>';

    }

    // выставляет количество элементов в корзине при загрузке страницы
    if (document.getElementsByClassName('header__cart-count')[0]) {

        var cartCount = document.getElementsByClassName('header__cart-count')[0];
        var stickyCartBlock = document.getElementsByClassName('sticky-cart')[0].getElementsByTagName('span')[0];
        var mobileCartBlock = document.getElementsByClassName('mobile-header__cart')[0].getElementsByTagName('span')[0];

        ajax('/cart/count', '', function() {
            cartCount.innerHTML = stickyCartBlock.innerHTML = mobileCartBlock.innerHTML = '<span>' + this + '</span>';
        });
        
    }

    // прилипающий хедер и прокрутка блока фильтров
    if (document.getElementsByClassName('wrap-header')[0]) {

        var wrap = document.getElementsByClassName('wrap-content')[0];
        var header = document.getElementsByClassName('wrap-header')[0];
        var r = header.getElementsByClassName('header__title-and-menu')[0];
        var cart = header.getElementsByClassName('header__cart-button')[0];
        var stickyCart = header.getElementsByClassName('sticky-cart')[0];
        var logo = document.getElementsByClassName('header__logo-image')[0];
        var headerTitleBlock = header.getElementsByClassName('header__title-block')[0];

        window.onscroll = function() {

            var scrollTop = window.pageYOffset ? window.pageYOffset : (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
            
            if (document.documentElement.clientWidth > 863) {
                if (scrollTop > 94) {
                    wrap.style.margin = '128px auto 0 auto';
                    header.style.boxShadow = '0 0 24px rgba(0,0,0,0.1)';
                    header.style.top = '-94px';
                    header.style.position = 'fixed';
                    header.style.borderBottom = '4px solid #cc181e';
                    r.style.backgroundColor = '#fff';
                    r.style.outline = 'none';
                    r.style.boxShadow = 'none';
                    cart.style.display = 'none';
                    stickyCart.removeAttribute('style');
                    logo.classList.add('header__logo-image--sticky');
                    headerTitleBlock.classList.add('header__title-block--sticky');
                } else {
                    wrap.removeAttribute('style');
                    header.removeAttribute('style');
                    r.removeAttribute('style');
                    cart.removeAttribute('style');
                    stickyCart.style.display = 'none';
                    logo.classList.remove('header__logo-image--sticky');
                    headerTitleBlock.classList.remove('header__title-block--sticky');
                }
            } else {
                wrap.removeAttribute('style');
            }

            // прокрутка блока фильтров
            if (document.getElementsByClassName('filters')[0]) {
                var filtersBlock = document.getElementsByClassName('filters')[0];
                var filtersBlockRightMargin = document.body.clientWidth - filtersBlock.getBoundingClientRect().right;
                if (document.documentElement.clientWidth > 1119) {
                    if (scrollTop > 118) {
                        console.log(filtersBlockRightMargin);
                        filtersBlock.style.position = 'fixed';
                        filtersBlock.style.top = '62px';
                        filtersBlock.style.right = filtersBlockRightMargin + 'px';
                        filtersBlock.style.boxShadow = '0 0 24px rgba(0,0,0,.1)';
                    } else {
                        filtersBlock.removeAttribute('style');
                    }
                } else {
                    filtersBlock.removeAttribute('style');
                }
            }

        }

    }

    // мобильное меню
    if (document.getElementsByClassName('mobile-header__menu-button')[0]) {

        var button = document.getElementsByClassName('mobile-header__menu-button')[0];
        var menu = document.getElementsByClassName('mobile-header__menu')[0];
        var close = document.getElementsByClassName('mobile-header__menu-close-button')[0];

        button.addEventListener('click', function() {
            if (menu.classList.contains('mobile-header__menu--hidden')) {
                menu.classList.remove('mobile-header__menu--hidden');
                // запрещаем скролл
                document.body.style.overflow = 'hidden';
            } else {
                menu.classList.add('mobile-header__menu--hidden');
                document.body.removeAttribute('style');
            }
        });

        close.addEventListener('click', function() {
            menu.classList.add('mobile-header__menu--hidden');
            document.body.removeAttribute('style');
        });

    }

    // мобильный блок фильтров
    if (document.getElementsByClassName('filters')[0]) {

        var filters = document.getElementsByClassName('filters')[0];
        var mobileFilterHeader = document.getElementsByClassName('filters-mobile-bar')[0];
        var mobileFilterHeaderArrow = mobileFilterHeader.getElementsByClassName('filters-mobile-bar__icon')[0];

        mobileFilterHeader.addEventListener('click', function() {
            if (filters.classList.contains('filters--mobile-opened')) {
                filters.classList.remove('filters--mobile-opened');
                mobileFilterHeader.removeAttribute('style');
                mobileFilterHeaderArrow.classList.remove('filters-mobile-bar__icon--opened');
            } else {
                filters.classList.add('filters--mobile-opened');
                mobileFilterHeader.style.margin = 0;
                mobileFilterHeader.style.borderRadius = '4px 4px 0 0';
                mobileFilterHeaderArrow.classList.add('filters-mobile-bar__icon--opened');
            }
        });

    }

    // админка > удаление категории
    if (document.getElementsByClassName('dashboard-header__button--delete-category')[0]) {

        var deleteCategoryButton = document.getElementsByClassName('dashboard-header__button--delete-category')[0];
        var categoryId = document.getElementById('category-id');

        deleteCategoryButton.addEventListener('click', function() {

            var confirmDelete = confirm('You are sure?');
            var redirectUri = '/dashboard/category/' + categoryId.value + '/delete';
            
            if (confirmDelete) document.location.href = redirectUri;

        });

    }

    // конструктор для услуги digital art (осторожно, тут жесть)
    if (document.getElementsByClassName('digital-art-constructor')[0]) {

        var categoryItems = document.getElementsByClassName('digital-art-constructor__category');
        var rateItems = document.getElementsByClassName('digital-art-constructor__rate');
        var category = 0;
        var rate = 0;
        var currentCombination = '00';
        var summary = 0;
        var summaryPriceBlock = document.getElementsByClassName('digital-art-constructor__price')[0];
        var currentImage = 1;

        var images = document.getElementsByClassName('digital-art-constructor__images')[0];
        var addImageButton = document.getElementsByClassName('digital-art-constructor__add-image-block')[0];

        var orderButton = document.getElementsByClassName('digital-art-constructor__order-button')[0];

        var test = '';

        function changePrice() {

            switch (currentCombination) {
                case '00': summary = 500; break;
                case '01': summary = 800; break;
                case '02': summary = 1000; break;
                case '10': summary = 800; break;
                case '11': summary = 1200; break;
                case '12': summary = 1500; break;
                case '20': summary = 1200; break;
                case '21': summary = 1600; break;
                case '22': summary = 2000; break;
            }

            summaryPriceBlock.innerHTML = '$' + summary;

        }

        function createImage() {

            var element = document.createElement('div');
            element.classList.add('digital-art-constructor__image-preview');
            element.style.display = 'none';
            element.innerHTML = '<input type="file" hidden><div></div><span title="Delete this image"><i class="material-icons">close</i></span>';
            images.insertBefore(element, addImageButton);

            var input = element.getElementsByTagName('input')[0];

            input.click();

            input.addEventListener('change', function() {

                var file = input.files[0];

                if (!file || !file.type.match(/image.*/)) {
                    element.remove();
                    return;
                };

                var fileReader = new FileReader();

                fileReader.onload = (function(theFile) {
                    return function(e) {
                        element.removeAttribute('style');
                        element.setAttribute('style', 'background-image: url(' + e.target.result + ')');
                    };
                })(file);

                fileReader.readAsDataURL(file);

            });

            if (images.children.length == 5) addImageButton.style.display = 'none';

            var close = element.getElementsByTagName('span')[0];

            close.addEventListener('click', function(e) {

                e.currentTarget.parentElement.remove();

                if (images.children.length == 4) {
                    addImageButton.removeAttribute('style');
                }

                if (document.getElementsByClassName('digital-art-constructor__notice--images')[0]) {
                    document.getElementsByClassName('digital-art-constructor__notice--images')[0].remove();
                }

                removeGlobalNotice();

            });
            
            return element;

        }

        function removeNotSelectedFiles() {

            var inputs = document.getElementsByClassName('digital-art-constructor__image-preview');
            
            for (var i = 0; i < inputs.length; ++i) {
                if (inputs[i].hasAttribute('style')) {
                    if (inputs[i].getAttribute('style') === 'display: none;') {
                        inputs[i].remove();
                    }
                }
            }

        }

        function createDigitalArtNotice(target, message) {

            switch (target) {

                case 'all':
                    var orderButton = document.getElementsByClassName('digital-art-constructor__order-button')[0];
                    var notice = document.createElement('span');
                    notice.classList.add('digital-art-constructor__notice', 'digital-art-constructor__notice--all');
                    notice.innerHTML = message;
                    document.getElementsByClassName('digital-art-constructor__summary-right-column')[0].insertBefore(notice, orderButton);
                    break;

                case 'category':
                    var categories = document.getElementsByClassName('digital-art-constructor__categories')[0];
                    var notice = document.createElement('span');
                    notice.innerHTML = message;
                    notice.classList.add('digital-art-constructor__notice', 'digital-art-constructor__notice--categories');
                    insertAfter(notice, categories);
                    break;

                case 'rate':
                    var rates = document.getElementsByClassName('digital-art-constructor__rates')[0];
                    var notice = document.createElement('span');
                    notice.innerHTML = message;
                    notice.classList.add('digital-art-constructor__notice', 'digital-art-constructor__notice--rates');
                    insertAfter(notice, rates);
                    break;

                case 'images':
                    var images = document.getElementsByClassName('digital-art-constructor__images')[0];
                    var notice = document.createElement('span');
                    notice.innerHTML = message;
                    notice.classList.add('digital-art-constructor__notice', 'digital-art-constructor__notice--images');
                    insertAfter(notice, images);
                    break;
                    
            }

        }

        function removeGlobalNotice() {
            if (document.getElementsByClassName('digital-art-constructor__notice--all')[0]) {
                document.getElementsByClassName('digital-art-constructor__notice--all')[0].remove();
            }
        }

        for (var i = 0; i < categoryItems.length; ++i) {

            categoryItems[i].addEventListener('click', function(e) {
                for (var j = 0; j < categoryItems.length; ++j) {
                    categoryItems[j].classList.remove('digital-art-constructor__category--selected');
                }
                e.currentTarget.classList.add('digital-art-constructor__category--selected');
                category = e.currentTarget.id;
                currentCombination = e.currentTarget.id + rate;
                removeGlobalNotice();
                changePrice();
            });

        }

        for (var i = 0; i < rateItems.length; ++i) {

            rateItems[i].addEventListener('click', function(e) {
                for (var j = 0; j < rateItems.length; ++j) {
                    rateItems[j].classList.remove('digital-art-constructor__rate--selected');
                }
                e.currentTarget.classList.add('digital-art-constructor__rate--selected');
                rate = e.currentTarget.id;
                currentCombination = category + e.currentTarget.id;
                removeGlobalNotice();
                changePrice();
            });

        }

        addImageButton.addEventListener('click', function() {

            removeNotSelectedFiles();

            if (document.getElementsByClassName('digital-art-constructor__notice--images')[0]) {
                document.getElementsByClassName('digital-art-constructor__notice--images')[0].remove();
            }
            removeGlobalNotice();
            createImage();
        });

        document.getElementsByClassName('digital-art-constructor__email')[0].addEventListener('focus', function() {
            removeNotice('digital-art-constructor__email');
            removeGlobalNotice();
        });

        document.getElementsByClassName('digital-art-constructor__wishes-textarea')[0].addEventListener('focus', function() {
            removeNotice('digital-art-constructor__wishes-textarea');
            removeGlobalNotice();
        });

        orderButton.addEventListener('click', function() {

            var notices = document.getElementsByClassName('digital-art-constructor__notice');

            // очищает notice'ы
            for (var k = 0; k < notices.length; ++k) {
                notices[k].remove();
            }

            removeGlobalNotice();

            removeNotSelectedFiles();

            orderButton.disabled = true;

            var category = document.getElementsByClassName('digital-art-constructor__category--selected')[0].id;
            var rate = document.getElementsByClassName('digital-art-constructor__rate--selected')[0].id;
            var imagesElements = document.getElementsByClassName('digital-art-constructor__image-preview');
            var images = [];
            var emailInput = document.getElementsByClassName('digital-art-constructor__email')[0];
            var wishesTextarea = document.getElementsByClassName('digital-art-constructor__wishes-textarea')[0];

            for (var j = 0; j < imagesElements.length; ++j) {
                
                var attr = imagesElements[j].style.backgroundImage;
                attr = attr.substring(5, attr.length - 2);
                images[j] = attr.substring(5, attr.length - 2);
            }

            var data = {
                'category': category,
                'rate': rate,
                'images': images,
                'email': emailInput.value,
                'wishes': wishesTextarea.value
            };

            ajax('/digital', data, function() {

                if (this.status) {

                    createModal('Excellent! Your order is accepted, one of our managers will contact you in the near future to clarify the details of the order.', 'Got it!', true);

                } else {

                    removeAllNotices('digital-art-constructor__email', 'digital-art-constructor__wishes-textarea');

                    createDigitalArtNotice('all', 'Something went wrong, check the entered data');

                    if (this.data.email != '') createNotice(emailInput, 'digital-art-constructor__email', this.data.email, true);

                    if (this.data.wishes != '') createNotice(wishesTextarea, 'digital-art-constructor__wishes-textarea', this.data.wishes, true);

                    if (this.data.category != '') {
                        createDigitalArtNotice('category', this.data.category);
                    }

                    if (this.data.rate != '') {
                        createDigitalArtNotice('rate', this.data.rate);
                    }

                    if (this.data.images != '') {
                        createDigitalArtNotice('images', this.data.images);
                    }

                }
                
                orderButton.disabled = false;

            });

        });

    }

    // добавление картины в корзину (страница painting)
    if (document.getElementsByClassName('painting__cart-button')[0]) {

        var paintingId = document.getElementById('painting-id');
        var addToCartButton = document.getElementsByClassName('painting__cart-button')[0];
        var addHtmlBlock = '<i class="material-icons">add_shopping_cart</i>Add to cart';
        var removeHtmlBlock = '<i class="material-icons">remove_shopping_cart</i>Remove from cart';
        var addedClass = 'painting__cart-button--added';

        function addToCart() {

            if (addToCartButton.classList.contains(addedClass)) {

                updateCartCount(false);
                addToCartButton.classList.remove(addedClass);
                addToCartButton.innerHTML = addHtmlBlock;
                return 'remove';

            } else {

                updateCartCount(true);
                addToCartButton.classList.add(addedClass);
                addToCartButton.innerHTML = removeHtmlBlock;
                return 'add';

            }

        }

        addToCartButton.addEventListener('click', function() {
            
            var action = addToCart();
            var data = {
                'id': paintingId.value,
                'action': action
            };

            ajax('/cart/painting', data, function() {
                // console.log(this);
            });

        });

    }

    // удаление товаров из корзины (страница cart)
    if (document.getElementsByClassName('cart__item-remove')[0]) {

        var removeFromCartButtons = document.getElementsByClassName('cart__item-remove');

        function removeItem(id, element) {

            var data = {
                'id': id,
                'action': 'remove'
            };

            ajax('/cart/painting', data, function() {

                var elementPrice = parseInt(element.parentElement.parentElement.getElementsByClassName('cart__item-block--price')[0].innerHTML.slice(1), 10);
                element.parentElement.parentElement.remove();

                // обновить количество элементов в хедере
                updateCartCount(false);

                // проверить, остались ли еще элементы
                var items = document.getElementsByClassName('cart__item');

                if (items.length > 0) {

                    // вычитает из общей цены цену удаленного товара и
                    // обновляет summary на странице
                    var priceBlock = document.getElementsByClassName('cart__summary-price')[0];
                    var currentPrice = parseInt(priceBlock.innerHTML.slice(1), 10);
                    var newPrice = currentPrice - elementPrice;
                    priceBlock.innerHTML = '$' + newPrice;
                    
                } else {

                    // делает корзину пустой
                    document.getElementsByClassName('cart')[0].remove();
                    document.getElementsByClassName('page-header__text')[0].innerHTML = 'Your cart is empty :(';

                }

            });
        }

        for (var i = 0; i < removeFromCartButtons.length; ++i) {
            removeFromCartButtons[i].addEventListener('click', function(e) {
                removeItem(e.currentTarget.getElementsByClassName('cart-item-id')[0].value, e.currentTarget);
            });
        }

    }

    // contact us
    if (document.getElementsByClassName('contact__form')[0]) {

        var contactForm = document.getElementsByClassName('contact__form')[0];
        var contactFormName = contactForm.getElementsByClassName('contact__form-input--name')[0];
        var contactFormEmail = contactForm.getElementsByClassName('contact__form-input--email')[0];
        var contactFormPhone = contactForm.getElementsByClassName('contact__form-input--phone')[0];
        var contactFormMessage = contactForm.getElementsByClassName('contact__form-textarea--message')[0];
        var contactFormSubmit = contactForm.getElementsByClassName('contact__form-submit')[0];

        submitPreventDefault(contactForm);
        removeNoticeOnFocus('contact__form-input--name', 'contact__form-input--email', 'contact__form-input--phone', 'contact__form-textarea--message');

        contactFormSubmit.addEventListener('click', function() {

            contactFormSubmit.disabled = true;
            contactFormSubmit.innerHTML = 'Sending...';
            
            var data = {
                'name': contactFormName.value,
                'email': contactFormEmail.value,
                'phone': contactFormPhone.value,
                'message': contactFormMessage.value
            };

            ajax('/contact', data, function() {

                if (this.status) {
                    clearForm('contact__form-input--name', 'contact__form-input--email', 'contact__form-input--phone', 'contact__form-textarea--message');
                    createModal('Your message has been sent! Soon one of our managers will contact you.', 'Got it!', false);
                } else {
                    removeAllNotices('contact__form-input--name', 'contact__form-input--email', 'contact__form-input--phone', 'contact__form-textarea--message');
                    if (this.data.name != '') createNotice(contactFormName, 'contact__form-input--name', this.data.name, true);
                    if (this.data.email != '') createNotice(contactFormEmail, 'contact__form-input--email', this.data.email, true);
                    if (this.data.phone != '') createNotice(contactFormPhone, 'contact__form-input--phone', this.data.phone, true);
                    if (this.data.message != '') createNotice(contactFormMessage, 'contact__form-textarea--message', this.data.message, true);
                }

                contactFormSubmit.disabled = false;
                contactFormSubmit.innerHTML = 'Send';

            });

        });

    }

    // галерея на главной
    if (document.getElementsByClassName('home__gallery')[0]) {

        var gallery = document.getElementsByClassName('home__gallery')[0];
        var currentImage = gallery.getElementsByClassName('home__gallery-current')[0];
        var items = gallery.getElementsByClassName('home__gallery-item');
        var currentImageNumber = 0;

        function nextImage() {
            
            currentImageNumber++;

            if (currentImageNumber === items.length) {
                currentImageNumber = 0;
                items[items.length - 1].classList.remove('home__gallery-item--selected');
            } else {
                items[currentImageNumber - 1].classList.remove('home__gallery-item--selected');
            }

            items[currentImageNumber].classList.add('home__gallery-item--selected');

            currentImage.style.backgroundImage = items[currentImageNumber].style.backgroundImage;

        }

        setInterval(nextImage, 3000);
        
    }

    // форма входа
    if (document.getElementsByClassName('login-form')[0]) {

        var loginForm = document.getElementsByClassName('login-form')[0];
        var emailInput = loginForm.getElementsByClassName('login-form__input--email')[0];
        var passwordInput = loginForm.getElementsByClassName('login-form__input--password')[0];
        var loginSubmit = loginForm.getElementsByClassName('login-form__submit')[0];

        submitPreventDefault(loginForm);
        removeNoticeOnFocus('login-form__input--email', 'login-form__input--password');

        loginSubmit.addEventListener('click', function() {

            loginSubmit.disabled = true;
            loginSubmit.innerHTML = 'Loading...';

            var data = {
                'email': emailInput.value,
                'password': passwordInput.value
            };

            ajax('/login', data, function() {
                
                if (this.status) {
                    clearForm('login-form__input--email', 'login-form__input--password');
                    window.location = this.data.redirect;
                } else {
                    removeAllNotices('login-form__input--email', 'login-form__input--email');
                    if (this.data.email != '') createNotice(emailInput, 'login-form__input--email', this.data.email, true);
                    if (this.data.password != '') createNotice(passwordInput, 'login-form__input--password', this.data.password, true);
                }

                loginSubmit.disabled = false;
                loginSubmit.innerHTML = 'Log in';

            });

        });

    }

    // платежные формы
    if (document.getElementsByClassName('payment-form')[0]) {

        var stripeToken = null;
        var stripeFormTemplate = '<form class="stripe-form"><input class="stripe-form__token" type="text" value="" hidden><div class="group"><label><div id="card-element" class="field"></div></label></div><button class="stripe-submit-button" type="submit"></button><div class="outcome"><div class="error" role="alert"></div></div></form>';

        var paymentForm = document.getElementsByClassName('payment-form')[0];
        var paymentHiddenForm = document.getElementsByClassName('payment-form__hidden-form')[0];
        var paymentMethods = paymentForm.getElementsByClassName('payment-form__method');
        var paymentMethod;
        var paymentSubmit = paymentForm.getElementsByClassName('payment-form__submit')[0];
        var paymentErrorBlock = paymentForm.getElementsByClassName('payment-form__error')[0];

        var paymentName = paymentForm.getElementsByClassName('payment-form__input--name')[0];
        var paymentEmail = paymentForm.getElementsByClassName('payment-form__input--email')[0];
        var paymentAddress = paymentForm.getElementsByClassName('payment-form__input--address')[0];
        var paymentCity = paymentForm.getElementsByClassName('payment-form__input--city')[0];
        var paymentState = paymentForm.getElementsByClassName('payment-form__input--state')[0];
        var paymentZIPCode = paymentForm.getElementsByClassName('payment-form__input--zip')[0];
        var stripePublishableKey = paymentForm.getElementsByClassName('payment-form__stripe-publishable-key')[0];

        paymentHiddenForm.innerHTML = '';

        function stripeFormProcessing(destroy) {

            var stripe = Stripe(stripePublishableKey.value);
            var elements = stripe.elements();

            var card = elements.create('card', {
                hidePostalCode: true,
                style: {
                    base: {
                        iconColor: '#6772e5',
                        color: '#000',
                        lineHeight: '40px',
                        fontWeight: 400,
                        fontFamily: '"Fira Sans", Helvetica, sans-serif',
                        fontSize: '16px',

                        '::placeholder': {
                            color: '#AAA',
                        },
                    },
                    classes: {
                        focus: 'is-focused',
                        empty: 'is-empty',
                    },
                }
            });

            if (destroy) {
                card.unmount();
                return;
            } else {
                card.mount('#card-element');
            }

            function setOutcome(result) {

                var errorElement = document.querySelector('.error');
                errorElement.classList.remove('visible');

                if (result.token) {

                    paymentMethod = paymentForm.getElementsByClassName('payment-form__method--active')[0].getAttribute('data');
                    paymentSubmit.disabled = true;
                    paymentSubmit.innerHTML = 'Processing...';

                    var data = {
                        'name': paymentName.value,
                        'email': paymentEmail.value,
                        'address': paymentAddress.value,
                        'city': paymentCity.value,
                        'state': paymentState.value,
                        'zip': paymentZIPCode.value,
                        'method': paymentMethod,
                        'token': result.token.id
                    };

                    ajax('/payment/form', data, function() {

                        if (!this.status) {
                        
                            removeAllNotices('payment-form__input--name', 'payment-form__input--email', 'payment-form__input--address', 'payment-form__input--city', 'payment-form__input--state', 'payment-form__input--zip');
                            paymentSubmit.disabled = false;
                            paymentSubmit.innerHTML = 'Checkout';
                            if (this.data.name != '') createNotice(paymentName, 'payment-form__input--name', this.data.name, true);
                            if (this.data.email != '') createNotice(paymentEmail, 'payment-form__input--email', this.data.email, true);
                            if (this.data.address != '') createNotice(paymentAddress, 'payment-form__input--address', this.data.address, true);
                            if (this.data.city != '') createNotice(paymentCity, 'payment-form__input--city', this.data.city, true);
                            if (this.data.state != '') createNotice(paymentState, 'payment-form__input--state', this.data.state, true);
                            if (this.data.zip != '') createNotice(paymentZIPCode, 'payment-form__input--zip', this.data.zip, true);

                        } else if (this.token) {

                            paymentSubmit.disabled = false;
                            paymentSubmit.innerHTML = 'Checkout';

                            if (this.stripeSuccess) {
                                window.location = this.data.redirect;
                            } else {
                                var error = document.createElement('span');
                                error.className = 'payment-error';
                                error.innerHTML = this.data.errorMessage;
                                paymentErrorBlock.appendChild(error);
                            }

                        } else {

                            paymentSubmit.disabled = false;
                            paymentSubmit.innerHTML = 'Checkout';

                        }

                    });

                } else if (result.error) {

                    var data = {
                        'name': paymentName.value,
                        'email': paymentEmail.value,
                        'address': paymentAddress.value,
                        'city': paymentCity.value,
                        'state': paymentState.value,
                        'zip': paymentZIPCode.value,
                        'method': paymentMethod
                    };

                    ajax('/payment/form', data, function() {

                        if (!this.status) {
                        
                            removeAllNotices('payment-form__input--name', 'payment-form__input--email', 'payment-form__input--address', 'payment-form__input--city', 'payment-form__input--state', 'payment-form__input--zip');
                            if (this.data.name != '') createNotice(paymentName, 'payment-form__input--name', this.data.name, true);
                            if (this.data.email != '') createNotice(paymentEmail, 'payment-form__input--email', this.data.email, true);
                            if (this.data.address != '') createNotice(paymentAddress, 'payment-form__input--address', this.data.address, true);
                            if (this.data.city != '') createNotice(paymentCity, 'payment-form__input--city', this.data.city, true);
                            if (this.data.state != '') createNotice(paymentState, 'payment-form__input--state', this.data.state, true);
                            if (this.data.zip != '') createNotice(paymentZIPCode, 'payment-form__input--zip', this.data.zip, true);

                        }

                    });

                    paymentSubmit.disabled = false;
                    paymentSubmit.innerHTML = 'Checkout';

                    stripeToken = null;
                    errorElement.textContent = result.error.message;
                    errorElement.classList.add('visible');

                }

            }

            card.on('change', function(event) {
                setOutcome(event);
            });
            
            document.getElementsByClassName('payment-form__submit')[0].addEventListener('click', function() {
                var extraDetails = {};
                stripe.createToken(card, extraDetails).then(setOutcome);
            });

        }

        for (var i = 0; i < paymentMethods.length; ++i) {

            var wrapStripeForm = document.getElementsByClassName('wrap-stripe-form')[0];

            paymentMethods[i].addEventListener('click', function(e) {

                for (var j = 0; j < paymentMethods.length; ++j) {

                    if (paymentMethods[j].classList.contains('payment-form__method--active')) {
                        paymentMethods[j].classList.remove('payment-form__method--active');
                    }

                }

                e.currentTarget.classList.add('payment-form__method--active');

                if (e.currentTarget.getAttribute('data') == 'stripe') {
                    if (!document.getElementsByClassName('stripe-form')[0]) {
                        wrapStripeForm.innerHTML = stripeFormTemplate;
                        stripeFormProcessing(false);
                    }
                } else if (e.currentTarget.getAttribute('data') == 'paypal') {
                    if (document.getElementsByClassName('stripe-form')[0]) {
                        wrapStripeForm.innerHTML = '';
                        stripeFormProcessing(true);
                    }
                }

                paymentMethod = e.currentTarget.getAttribute('data');

            });

        }

        submitPreventDefault(paymentForm);
        removeNoticeOnFocus('payment-form__input--name', 'payment-form__input--email', 'payment-form__input--address', 'payment-form__input--city', 'payment-form__input--state', 'payment-form__input--zip');

        paymentSubmit.addEventListener('click', function() {

            if (document.getElementsByClassName('payment-error')[0]) {
                document.getElementsByClassName('payment-error')[0].remove();
            }

            paymentMethod = paymentForm.getElementsByClassName('payment-form__method--active')[0].getAttribute('data');
            paymentSubmit.disabled = true;
            paymentSubmit.innerHTML = 'Processing...';

            if (paymentMethod === 'paypal') {

                var data = {
                    'name': paymentName.value,
                    'email': paymentEmail.value,
                    'address': paymentAddress.value,
                    'city': paymentCity.value,
                    'state': paymentState.value,
                    'zip': paymentZIPCode.value,
                    'method': paymentMethod
                };

            }
            
            ajax('/payment/form', data, function() {

                if (paymentMethod === 'paypal') {

                    if (this.status) {
                    
                        paymentHiddenForm.innerHTML = this.data.form;
                        document.getElementById('pay').click();
                        
                    } else {
                        removeAllNotices('payment-form__input--name', 'payment-form__input--email', 'payment-form__input--address', 'payment-form__input--city', 'payment-form__input--state', 'payment-form__input--zip');
                        paymentSubmit.disabled = false;
                        paymentSubmit.innerHTML = 'Checkout';
                        if (this.data.name != '') createNotice(paymentName, 'payment-form__input--name', this.data.name, true);
                        if (this.data.email != '') createNotice(paymentEmail, 'payment-form__input--email', this.data.email, true);
                        if (this.data.address != '') createNotice(paymentAddress, 'payment-form__input--address', this.data.address, true);
                        if (this.data.city != '') createNotice(paymentCity, 'payment-form__input--city', this.data.city, true);
                        if (this.data.state != '') createNotice(paymentState, 'payment-form__input--state', this.data.state, true);
                        if (this.data.zip != '') createNotice(paymentZIPCode, 'payment-form__input--zip', this.data.zip, true);
                    }

                }

            });

        });

    }

    // галерея на странице картины
    if (document.getElementsByClassName('painting__images')[0]) {

        var currentImage = document.getElementsByClassName('painting__image')[0];
        var items = document.getElementsByClassName('painting__images-item');

        for (var i = 0; i < items.length; ++i) {

            items[i].addEventListener('click', function(e) {

                for (var j = 0; j < items.length; ++j) {
                    items[j].classList.remove('painting__images-item--active');
                }

                e.currentTarget.classList.add('painting__images-item--active');
                var url = e.currentTarget.style.backgroundImage.substr(5);
                url = url.substr(0, url.length - 2);
                currentImage.setAttribute('src', url);

            });

        }
        
    }

    // блок с фильтрами
    if (document.getElementsByClassName('filters')[0]) {

        var filtersBlock = document.getElementsByClassName('filters')[0];
        var categories = filtersBlock.getElementsByClassName('filters__categories')[0];
        var prices = filtersBlock.getElementsByClassName('filters__prices')[0];
        var sizes = filtersBlock.getElementsByClassName('filters__size-item');
        var orientations = filtersBlock.getElementsByClassName('filters__orientation-item');
        var searchButton = filtersBlock.getElementsByClassName('filters__search-button')[0];
        var elements = {
            'category': getGetParameter('category'),
            'price': getGetParameter('price'),
            'size': getGetParameter('size'),
            'orientation': getGetParameter('orientation')
        };

        function changeElements() {
            var queryString = '';
            for (var key in elements) {
                if (null !== elements[key]) {
                    queryString += '&' + key + '=' + elements[key];
                }
            }
            console.log(queryString);
            searchButton.setAttribute('href', '?' + queryString.substr(1));
        }

        function toggle(items, activeClass, stateKey) {
            for (var i = 0; i < items.length; i++) {
                items[i].addEventListener('click', function(e) {
                    if (e.currentTarget.classList.contains(activeClass)) {
                        e.currentTarget.classList.remove(activeClass);
                        elements[stateKey] = null;
                        changeElements();
                    } else {
                        for (var j = 0; j < items.length; j++) {
                            if (items[j].classList.contains(activeClass)) {
                                items[j].classList.remove(activeClass);
                            }
                        }
                        e.currentTarget.classList.add(activeClass);
                        elements[stateKey] = e.currentTarget.getAttribute('value');
                        changeElements();
                    }
                });
            }
        }

        categories.addEventListener('change', function(e) {
            var selectedValue = e.target.options[e.target.selectedIndex].value;
            if (selectedValue === 'all') {
                elements.category = null;
            } else {
                elements.category = selectedValue;
            }
            changeElements();
        });

        prices.addEventListener('change', function(e) {
            var selectedValue = e.target.options[e.target.selectedIndex].value;
            if (selectedValue === 'all') {
                elements.price = null;
            } else {
                elements.price = selectedValue;
            }
            changeElements();
        });

        toggle(sizes, 'filters__size-item--active', 'size');
        toggle(orientations, 'filters__orientation-item--active', 'orientation');
    }

}

document.addEventListener("DOMContentLoaded", ready);