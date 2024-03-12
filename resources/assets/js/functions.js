'use strict';

function getMetaContent(metaName) {
    var metas = document.getElementsByTagName('meta');
    var re = new RegExp('\\b' + metaName + '\\b', 'i');
    var i = 0;
    var mLength = metas.length;
 
    for (i; i < mLength; i++) {
        if (re.test(metas[i].getAttribute('name'))) {
            return metas[i].getAttribute('content');
        }
    }
 
    return '';
}

function ajax(route, data, callback) {
    var ajax = new XMLHttpRequest();
    var data = JSON.stringify(data);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            if (ajax.status == 200) {
                callback.call(JSON.parse(ajax.responseText));
            }
        }
    }
    ajax.open('POST', route);
    ajax.setRequestHeader('Content-type', 'application/json');
    ajax.setRequestHeader('X-CSRF-TOKEN', getMetaContent('csrf-token'));
    ajax.send(data);
}

function insertAfter(node, referenceNode) {
    if (!node || !referenceNode) return;
    var parent = referenceNode.parentNode, nextSibling = referenceNode.nextSibling;
    if (nextSibling && parent) {
        parent.insertBefore(node, referenceNode.nextSibling);
    } else if (parent) {
        parent.appendChild(node);
    }
}

function submitPreventDefault(element) {
    element.addEventListener('submit', function(e) {
        e.preventDefault();
    });
}

function createNotice(target, targetClass, message, margin) {
    if (document.getElementsByClassName(targetClass + '--notice')[0]) {
        removeNotice(targetClass);
    }
    var notice = document.createElement('span');
    notice.innerHTML = message;
    if (margin) {
        var noticeClass = 'notice notice--margin ' + targetClass + '--notice';
    } else {
        var noticeClass = 'notice ' + targetClass + '--notice';
    }
    notice.setAttribute('class', noticeClass);
    insertAfter(notice, target);
    target.style.border = '2px solid #cc181e';
}

function removeNotice(targetClass) {
    if (document.getElementsByClassName(targetClass + '--notice')[0]) {
        document.getElementsByClassName(targetClass + '--notice')[0].remove();
        document.getElementsByClassName(targetClass)[0].removeAttribute('style');
    }
}

function removeAllNotices() {
    for (var i = 0; i < arguments.length; i++) {
        removeNotice(arguments[i]);
    }
}

function clearForm() {
    for (var i = 0; i < arguments.length; i++) {
        document.getElementsByClassName(arguments[i])[0].value = '';
        document.getElementsByClassName(arguments[i])[0].removeAttribute('style');
        if (document.getElementsByClassName(arguments[i] + '--notice')[0]) {
            document.getElementsByClassName(arguments[i] + '--notice')[0].remove();
        }
    }
}

function removeNoticeOnFocus() {
    for (var i = 0; i < arguments.length; i++) {
        document.getElementsByClassName(arguments[i])[0].addEventListener('focus', function(e) {
            var currentClass = e.target.className.split(' ')[1];
            removeNotice(currentClass);
        });
    }
}

function b64toBlob(b64Data, contentType, sliceSize) {
    contentType = contentType || '';
    sliceSize = sliceSize || 512;

    var byteCharacters = atob(b64Data);
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
    var slice = byteCharacters.slice(offset, offset + sliceSize);

    var byteNumbers = new Array(slice.length);
    for (var i = 0; i < slice.length; i++) {
        byteNumbers[i] = slice.charCodeAt(i);
    }

    var byteArray = new Uint8Array(byteNumbers);

    byteArrays.push(byteArray);
    }

    var blob = new Blob(byteArrays, {type: contentType});

    return blob;
}

function createModal(text, button, reload) {

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
            if (reload) setTimeout(reloadPage, 100);
        }

    });

    modalButton.addEventListener('click', function(e) {

        wrapModal.classList.add('wrap-modal--hidden');
        setTimeout(removeModal, 100);
        if (reload) setTimeout(reloadPage, 100);

    });

    function removeModal() {
        modalNode.remove();
    }

    function reloadPage() {
        location.reload();
    }
    
}

function getGetParameters() {
    return location.search.substr(1);
}

function getGetParameter(key) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
            tmp = item.split("=");
            if (tmp[0] === key) result = decodeURIComponent(tmp[1]);
        });
    return result;
}