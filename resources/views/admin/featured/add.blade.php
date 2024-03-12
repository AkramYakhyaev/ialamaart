@extends ('admin.layout')

@section ('title', 'Add featured artist')

@section ('content')
    
    <div class="featured-form">
        <div class="featured-form__column">
            <div class="featured-form__images">
                @for ($i = 0; $i < 4; $i++)
                    <div class="featured-form__image">
                        <input class="featured-form__image-number" type="text" value="{{ $i }}" hidden>
                        <input class="featured-form__image-input" type="file" hidden>
                        <div class="featured-form__upload-button">
                            <div>
                                <i class="material-icons">file_upload</i>
                                <span>Upload photo</span>
                            </div>
                        </div>
                        <div class="featured-form__wrap-preview featured-form__wrap-preview--hidden">
                            <div class="featured-form__preview"></div>
                            <div class="featured-form__preview-overlay">
                                <span class="featured-form__delete-preview" title="Delete this image"><i class="material-icons">close</i></span>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="featured-form__images-notice"></div>
        </div>
        <div class="featured-form__column">
            <label class="featured-form__label">Artist</label>
            <input class="featured-form__artist" type="text" placeholder="Artist name">
            <input class="featured-form__submit" type="submit" value="Create new featured artist">
        </div>
    </div>

@endsection