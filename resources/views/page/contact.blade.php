@extends ('layout')

@section ('title', __('title.contact'))

@section ('content')
    
    {{--
    <div class="page-header">
        <h1 class="page-header__title">{{ __('title.contact') }}</h1>
        <p class="page-header__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lobortis massa lobortis, molestie nisl ut, mattis sapien.</p>
    </div>
    --}}
    
    <div class="contact">
        
        <div class="contact__left-column">
            <div class="contact__left-column-block">
                <h3 class="contact__left-column-header">Contact information</h3>
                <a class="contact__link" href="tel:312-877-7788">
                    <i class="material-icons">phone</i>
                    <span class="contact__left-column-link">(312) 877-7788</span>
                </a>
                <a class="contact__link" href="mailto:info@ialamaart.com">
                    <i class="material-icons">mail</i>
                    <span class="contact__left-column-link">info@ialamaart.com</span>
                </a>
            </div>
            <div class="contact__left-column-block">
                <h3 class="contact__left-column-header">Gallery managers</h3>
                <span class="contact__left-column-text">Galina Ialama</span>
                <span class="contact__left-column-text">Akram Yakhyaev</span>
            </div>
            <div class="contact__left-column-block">
                <h3 class="contact__left-column-header">We are in social networks</h3>
                <a class="contact__link" href="https://www.facebook.com/profile.php?id=100017153887353">
                    <span class="social-icon social-icon--facebook"></span>
                    <span class="contact__left-column-link contact__left-column-link--facebook">Like us on Facebook</span>
                </a>
                <a class="contact__link" href="https://www.instagram.com/galino4ka/?hl=en">
                    <span class="social-icon social-icon--instagram"></span>
                    <span class="contact__left-column-link contact__left-column-link--instagram">Follow us on Instagram</span>
                </a>
                <a class="contact__link" href="https://www.pinterest.com/ialamaartgalleryandshop/">
                    <span class="social-icon social-icon--pinterest"></span>
                    <span class="contact__left-column-link contact__left-column-link--pinterest">Pin us on Pinterest</span>
                </a>
            </div>
        </div>

        <div class="contact__right-column">
            <div class="contact__right-column-block">
                <form class="contact__form">
                    <input class="contact__form-input contact__form-input--name" type="text" placeholder="Name">
                    <input class="contact__form-input contact__form-input--email" type="text" placeholder="Email address">
                    <input class="contact__form-input contact__form-input--phone" type="text" placeholder="Phone number">
                    <textarea class="contact__form-textarea contact__form-textarea--message" placeholder="Message"></textarea>
                    <button class="contact__form-submit">Send</button>
                </form>
            </div>
        </div>

    </div>

@endsection