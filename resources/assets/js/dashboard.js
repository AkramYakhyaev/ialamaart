'use strict';

function ready() {

    function createDashboardModal(text, button, redirect, redirectPath) {

        var wrapModal = document.getElementsByClassName('wrap-modal')[0];

        var modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = '<span class="modal__text">' + text + '</span>';
        modal.innerHTML += '<button class="modal__button">' + button + '</button>';

        wrapModal.appendChild(modal);
        wrapModal.classList.remove('wrap-modal--hidden');

        var modalNode = wrapModal.getElementsByClassName('modal')[0];
        var modalButton = modalNode.getElementsByClassName('modal__button')[0];

        wrapModal.addEventListener('click', function(e) {

            if (e.target === wrapModal) {
                wrapModal.classList.add('wrap-modal--hidden');
                setTimeout(removeModal, 100);
                if (redirect) setTimeout(redirectToPaintings, 100);
            }

        });

        modalButton.addEventListener('click', function(e) {

            wrapModal.classList.add('wrap-modal--hidden');
            setTimeout(removeModal, 100);
            if (redirect) setTimeout(redirectToPaintings, 100);

        });

        function removeModal() {
            modalNode.remove();
        }

        function redirectToPaintings() {
            window.location = redirectPath;
        }
        
    }

    // добавление/редактирование картин в админке
    if (document.getElementsByClassName('painting-form')[0]) {

        var paintingForm = document.getElementsByClassName('painting-form')[0];
        var paintingName = paintingForm.getElementsByClassName('painting-form__input--name')[0];
        var paintingLink = paintingForm.getElementsByClassName('painting-form__input--link')[0];
        var paintingPrice = paintingForm.getElementsByClassName('painting-form__input--price')[0];
        var paintingDescription = paintingForm.getElementsByClassName('painting-form__textarea--description')[0];
        var paintingCategory = paintingForm.getElementsByClassName('painting-form__select--category')[0];
        var paintingSize = paintingForm.getElementsByClassName('painting-form__select--size')[0];
        var paintingOrientation = paintingForm.getElementsByClassName('painting-form__select--orientation')[0];
        var paintingPreviews = paintingForm.getElementsByClassName('painting-form__preview');
        var paintingPhotoInputs = paintingForm.getElementsByClassName('painting-form__image-input');
        var paintingUploadButtons = paintingForm.getElementsByClassName('painting-form__upload-button');
        var paintingDeleteButtons = paintingForm.getElementsByClassName('painting-form__delete-preview');
        var paintingImagesNotice = paintingForm.getElementsByClassName('painting-form__images-notice')[0];
        var paintingSubmit = paintingForm.getElementsByClassName('painting-form__submit')[0];

        if (paintingForm.classList.contains('painting-form--add')) {
            var action = 'add';
        } else if (paintingForm.classList.contains('painting-form--edit')) {
            var action = 'edit';
        }

        function paintingToggleUploadToPreview(previewBlock, uploadButton) {

            if (uploadButton.classList.contains('painting-form__upload-button--hidden')) {
                uploadButton.classList.remove('painting-form__upload-button--hidden');
                previewBlock.parentNode.classList.add('painting-form__wrap-preview--hidden');
            } else if (previewBlock.parentNode.classList.contains('painting-form__wrap-preview--hidden')) {
                previewBlock.parentNode.classList.remove('painting-form__wrap-preview--hidden');
                uploadButton.classList.add('painting-form__upload-button--hidden');
            }

        }

        function createDashboardPaintingNotice(target, targetClass, message, margin) {

            if (document.getElementsByClassName(targetClass + '--notice')[0]) {
                removeNotice(targetClass);
            }
            var notice = document.createElement('span');
            notice.innerHTML = message;
            if (margin) {
                var noticeClass = 'painting-form-notice notice--margin ' + targetClass + '--notice';
            } else {
                var noticeClass = 'painting-form-notice ' + targetClass + '--notice';
            }
            notice.setAttribute('class', noticeClass);
            insertAfter(notice, target);
            target.style.border = '2px solid #cc181e';

        }

        removeNoticeOnFocus('painting-form__input--name', 'painting-form__input--link', 'painting-form__input--price', 'painting-form__textarea--description');

        for (var i = 0; i < paintingUploadButtons.length; i++) {

            paintingUploadButtons[i].addEventListener('click', function(e) {
                var currentNumber = e.currentTarget.parentElement.getElementsByClassName('painting-form__image-number')[0].value;
                paintingPhotoInputs[currentNumber].click();
            });

            paintingDeleteButtons[i].addEventListener('click', function(e) {
                var currentNumber = e.currentTarget.parentElement.parentElement.parentElement.getElementsByClassName('painting-form__image-number')[0].value;
                paintingPreviews[currentNumber].removeAttribute('style');
                paintingPhotoInputs[currentNumber].value = '';
                paintingToggleUploadToPreview(paintingPreviews[currentNumber], paintingUploadButtons[currentNumber]);
            });

            paintingPhotoInputs[i].addEventListener('change', function(e) {
                var currentNumber = e.currentTarget.parentElement.getElementsByClassName('painting-form__image-number')[0].value;
                var file = paintingPhotoInputs[currentNumber].files[0];
                if (!file || !file.type.match(/image.*/)) return;
                var fileReader = new FileReader();
                fileReader.onload = (function(theFile) {
                    return function(e) {
                        paintingToggleUploadToPreview(paintingPreviews[currentNumber], paintingUploadButtons[currentNumber]);
                        paintingPreviews[currentNumber].setAttribute('style', 'background-image: url(' + e.target.result + ')');
                    };
                })(file);
                fileReader.readAsDataURL(file);
            });

        }

        paintingSubmit.addEventListener('click', function() {

            paintingSubmit.disabled = true;

            var images = [];

            for (var j = 0; j < paintingPreviews.length; j++) {
                images[j] = paintingPreviews[j].style.backgroundImage.substring(5, paintingPreviews[j].style.backgroundImage.length - 2);
            }

            var data = {
                'images': images,
                'name': paintingName.value,
                'link': paintingLink.value,
                'price': paintingPrice.value,
                'description': paintingDescription.value,
                'category': paintingCategory.value,
                'size': paintingSize.value,
                'orientation': paintingOrientation.value
            };

            if (action === 'add') {
                var route = '/dashboard/painting/add';
                var successMessage = 'The painting was successfully added!';
                var needRedirect = true;
                var redirectUrl = '/dashboard/paintings';
            } else if (action === 'edit') {
                var route = '/dashboard/painting/edit';
                var successMessage = 'The painting was successfully updated!';
                var needRedirect = false;
                var redirectUrl = '';
                data['id'] = document.getElementsByClassName('painting-form__id')[0].value;
            }

            ajax(route, data, function() {

                removeAllNotices('painting-form__input--name', 'painting-form__input--link', 'painting-form__input--price', 'painting-form__textarea--description', 'painting-form__select--category', 'painting-form__select--size', 'painting-form__select--orientation');
                paintingImagesNotice.innerHTML = '';

                if (this.status) {
                    createDashboardModal(successMessage, 'Got it!', needRedirect, redirectUrl);
                } else {

                    if (this.data.images != '') paintingImagesNotice.innerHTML = this.data.images;
                    if (this.data.name != '') createDashboardPaintingNotice(paintingName, 'painting-form__input--name', this.data.name, false);
                    if (this.data.link != '') createDashboardPaintingNotice(paintingLink, 'painting-form__input--link', this.data.link, false);
                    if (this.data.price != '') createDashboardPaintingNotice(paintingPrice, 'painting-form__input--price', this.data.price, false);
                    if (this.data.description != '') createDashboardPaintingNotice(paintingDescription, 'painting-form__textarea--description', this.data.description, false);
                    if (this.data.category != '') createDashboardPaintingNotice(paintingCategory, 'painting-form__select--category', this.data.category, false);
                    if (this.data.size != '') createDashboardPaintingNotice(paintingSize, 'painting-form__select--category', this.data.size, false);
                    if (this.data.orientation != '') createDashboardPaintingNotice(paintingOrientation, 'painting-form__select--orientation', this.data.orientation, false);

                }

                paintingSubmit.disabled = false;

            });

        });

    }

    // удаление картины
    if (document.getElementById('painting__delete-button')) {

        var button = document.getElementById('painting__delete-button');
        var id = document.getElementsByClassName('painting-form__id')[0];

        button.addEventListener('click', function() {

            var result = confirm('Delete this painting?');

            if (result) {

                var data = {
                    'id': id.value
                };

                ajax('/dashboard/painting/delete', data, function() {
                    if (this.status) {
                        createDashboardModal('The painting was successfully deleted!', 'Got it!', true, '/dashboard/paintings');
                    } else {
                        console.log(this.data.error);
                    }
                });

            }

        });

    }

    // set available
    if (document.getElementById('painting__set-available-button')) {

        var button = document.getElementById('painting__set-available-button');
        var id = document.getElementsByClassName('painting-form__id')[0];

        button.addEventListener('click', function() {

            var result = confirm('Make this picture available again?');

            if (result) {

                var data = {
                    'id': id.value
                };

                ajax('/dashboard/painting/available', data, function() {
                    if (this.status) {
                        createDashboardModal('The painting was successfully updated!', 'Got it!', false, '/dashboard/paintings');
                    } else {
                        console.log(this.data.error);
                    }
                });

            }

        });

    }

    // удаление заказа
    if (document.getElementById('order__delete-button')) {

        var button = document.getElementById('order__delete-button');
        var id = document.getElementsByClassName('dashboard-order__id')[0];

        button.addEventListener('click', function() {

            var result = confirm('Delete this order?');

            if (result) {

                var data = {
                    'id': id.innerHTML
                };

                ajax('/dashboard/order/delete', data, function() {
                    if (this.status) {
                        createDashboardModal('The order was successfully deleted!', 'Got it!', true, '/dashboard/orders');
                    } else {
                        console.log(this.data.error);
                    }
                });

            }

        });

    }

    // добавление/редактирование новостей
    if (document.getElementsByClassName('event-form')[0]) {

        var eventForm = document.getElementsByClassName('event-form')[0];
        var eventDate = eventForm.getElementsByClassName('event-form__date')[0];
        var eventText = eventForm.getElementsByClassName('event-form__text')[0];
        var eventSubmit = eventForm.getElementsByClassName('event-form__submit')[0];

        eventDate.addEventListener('focus', function(e) {
            if (e.currentTarget.getAttribute('style')) {
                e.currentTarget.removeAttribute('style');
            }
            if (document.getElementsByClassName('event-form__date--notice')[0]) {
                document.getElementsByClassName('event-form__date--notice')[0].remove();
            }
        });

        eventText.addEventListener('focus', function(e) {
            if (e.currentTarget.getAttribute('style')) {
                e.currentTarget.removeAttribute('style');
            }
            if (document.getElementsByClassName('event-form__text--notice')[0]) {
                document.getElementsByClassName('event-form__text--notice')[0].remove();
            }
        });

        eventSubmit.addEventListener('click', function() {

            eventSubmit.disabled = true;

            if (eventForm.classList.contains('event-form--add')) {

                var data = {
                    'date': eventDate.value,
                    'text': eventText.value
                };

                ajax('/dashboard/event/add', data, function() {

                    removeAllNotices('event-form__date', 'event-form__text');

                    if (this.status) {
                        createDashboardModal('The event was successfully added!', 'Got it!', true, '/dashboard/events');
                    } else {
                        if (this.data.date != '') createNotice(eventDate, 'event-form__date', this.data.date, false);
                        if (this.data.text != '') createNotice(eventText, 'event-form__text', this.data.text, false);
                    }

                    eventSubmit.disabled = false;
                });

            } else if (eventForm.classList.contains('event-form--edit')) {

                var id = eventForm.getElementsByClassName('event-form__id')[0];
                var data = {
                    'id': id.value,
                    'date': eventDate.value,
                    'text': eventText.value
                };

                ajax('/dashboard/event/edit', data, function() {

                    removeAllNotices('event-form__date', 'event-form__text');

                    if (this.status) {
                        createDashboardModal('The event was successfully updated!', 'Got it!', false);
                    } else {
                        if (this.data.date != '') createNotice(eventDate, 'event-form__date', this.data.date, false);
                        if (this.data.text != '') createNotice(eventText, 'event-form__text', this.data.text, false);
                    }

                    eventSubmit.disabled = false;
                });

            }
        });

    }

    // удаление новости
    if (document.getElementById('event__delete-button')) {

        var button = document.getElementById('event__delete-button');
        var id = document.getElementsByClassName('event-form__id')[0];

        button.addEventListener('click', function() {

            var result = confirm('Delete this event?');

            if (result) {

                var data = {
                    'id': id.value
                };

                ajax('/dashboard/event/delete', data, function() {
                    if (this.status) {
                        createDashboardModal('The event was successfully deleted!', 'Got it!', true, '/dashboard/events');
                    } else {
                        console.log(this.data.error);
                    }
                });

            }

        });

    }

    // обновление текущего featured artist
    if (document.getElementsByClassName('dashboard-header__button--update-featured')[0]) {

        var updateFeaturedButton = document.getElementsByClassName('dashboard-header__button--update-featured')[0];
        var updateFeaturedRadios = document.getElementsByClassName('dashboard-featured__radio');
        var currentId;

        updateFeaturedButton.addEventListener('click', function() {

            for (var i = 0; i < updateFeaturedRadios.length; i++) {
                if (updateFeaturedRadios[i].checked) {
                    currentId = updateFeaturedRadios[i].id;
                    break;
                }
            }

            var data = {'id': currentId};
            updateFeaturedButton.innerHTML = 'Saving...';

            ajax('/dashboard/featured/update', data, function() {
                updateFeaturedButton.innerHTML = 'Save changes';
                createDashboardModal('The featured artist was successfully updated!', 'Got it!', false);
            });

        });

    }

    // добавление featured artist
    if (document.getElementsByClassName('featured-form')[0]) {

        var featuredForm = document.getElementsByClassName('featured-form')[0];
        var featuredArtist = featuredForm.getElementsByClassName('featured-form__artist')[0];
        var featuredImagesNotice = featuredForm.getElementsByClassName('featured-form__images-notice')[0];
        var photoPreviews = featuredForm.getElementsByClassName('featured-form__preview');
        var photoInputs = featuredForm.getElementsByClassName('featured-form__image-input');
        var uploadButtons = featuredForm.getElementsByClassName('featured-form__upload-button');
        var deleteButtons = featuredForm.getElementsByClassName('featured-form__delete-preview');
        var featuredSubmit = featuredForm.getElementsByClassName('featured-form__submit')[0];
        
        function toggleUploadToPreview(previewBlock, uploadButton) {

            if (uploadButton.classList.contains('featured-form__upload-button--hidden')) {
                uploadButton.classList.remove('featured-form__upload-button--hidden');
                previewBlock.parentNode.classList.add('featured-form__wrap-preview--hidden');
            } else if (previewBlock.parentNode.classList.contains('featured-form__wrap-preview--hidden')) {
                previewBlock.parentNode.classList.remove('featured-form__wrap-preview--hidden');
                uploadButton.classList.add('featured-form__upload-button--hidden');
            }

        }

        featuredArtist.addEventListener('focus', function(e) {

            if (e.currentTarget.getAttribute('style')) {
                e.currentTarget.removeAttribute('style');
            }

            if (document.getElementsByClassName('featured-form__artist--notice')[0]) {
                document.getElementsByClassName('featured-form__artist--notice')[0].remove();
            }

        });

        for (var i = 0; i < uploadButtons.length; i++) {

            uploadButtons[i].addEventListener('click', function(e) {
                var currentNumber = e.currentTarget.parentElement.getElementsByClassName('featured-form__image-number')[0].value;
                photoInputs[currentNumber].click();
            });

            deleteButtons[i].addEventListener('click', function(e) {
                var currentNumber = e.currentTarget.parentElement.parentElement.parentElement.getElementsByClassName('featured-form__image-number')[0].value;
                photoPreviews[currentNumber].removeAttribute('style');
                photoInputs[currentNumber].value = '';
                toggleUploadToPreview(photoPreviews[currentNumber], uploadButtons[currentNumber]);
            });

            photoInputs[i].addEventListener('change', function(e) {
                var currentNumber = e.currentTarget.parentElement.getElementsByClassName('featured-form__image-number')[0].value;
                var file = photoInputs[currentNumber].files[0];
                if (!file || !file.type.match(/image.*/)) return;
                var fileReader = new FileReader();
                fileReader.onload = (function(theFile) {
                    return function(e) {
                        toggleUploadToPreview(photoPreviews[currentNumber], uploadButtons[currentNumber]);
                        photoPreviews[currentNumber].setAttribute('style', 'background-image: url(' + e.target.result + ')');
                    };
                })(file);
                fileReader.readAsDataURL(file);
            });

        }

        featuredSubmit.addEventListener('click', function() {

            featuredSubmit.disabled = true;

            var images = [];

            for (var j = 0; j < photoPreviews.length; j++) {
                images[j] = photoPreviews[j].style.backgroundImage.substring(5, photoPreviews[j].style.backgroundImage.length - 2);
            }

            var data = {
                'artist': featuredArtist.value,
                'images': images
            };

            ajax('/dashboard/featured/add', data, function() {

                removeAllNotices('featured-form__artist');
                featuredImagesNotice.innerHTML = '';

                if (this.status) {
                    createDashboardModal('The featured artist was successfully added!', 'Got it!', true, '/dashboard/featured');
                } else {
                    if (this.data.images != '') featuredImagesNotice.innerHTML = this.data.images;
                    if (this.data.artist != '') createNotice(featuredArtist, 'featured-form__artist', this.data.artist, false);
                }

                featuredSubmit.disabled = false;

            });

        });

    }

    // удаление featured artist
    if (document.getElementsByClassName('dashboard-featured__remove-button')) {

        var removeFeaturedButtons = document.getElementsByClassName('dashboard-featured__remove-button');

        for (var i = 0; i < removeFeaturedButtons.length; i++) {

            removeFeaturedButtons[i].addEventListener('click', function(e) {

                var id = e.currentTarget.parentElement.parentElement.getElementsByClassName('dashboard-featured__radio')[0].id;
                var result = confirm('Delete this featured artist?');

                if (result) {

                    var data = {
                        'id': id
                    };

                    ajax('/dashboard/featured/delete', data, function() {
                        if (this.status) {
                            createDashboardModal('The featured artist was successfully deleted!', 'Got it!', true, '/dashboard/featured');
                        } else {
                            console.log(this.data.error);
                        }
                    });

                }

            });

        }
        
    }

}

document.addEventListener("DOMContentLoaded", ready);