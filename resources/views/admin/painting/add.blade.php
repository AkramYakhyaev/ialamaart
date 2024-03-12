@extends ('admin.layout')

@section ('title', 'Add painting')

@section ('content')
    
    <div class="painting-form painting-form--add">
        <div class="painting-form__content">

            {{-- photos --}}
            <div class="painting-form__column">
                <div class="painting-form__images">
                    @for ($i = 0; $i < $photoCount; $i++)
                        @if ($i === 0)
                            <div class="painting-form__image painting-form__image--main">
                        @else
                            <div class="painting-form__image">
                        @endif
                            <input class="painting-form__image-number" type="text" value="{{ $i }}" hidden>
                            <input class="painting-form__image-input" type="file" hidden>
                            <div class="painting-form__upload-button">
                                <div>
                                    <i class="material-icons">file_upload</i>
                                    <span>Upload photo</span>
                                </div>
                            </div>
                            <div class="painting-form__wrap-preview painting-form__wrap-preview--hidden">
                                <div class="painting-form__preview"></div>
                                <div class="painting-form__preview-overlay">
                                    <span class="painting-form__delete-preview" title="Delete this image"><i class="material-icons">close</i></span>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="painting-form__images-notice"></div>
                
            </div>

            {{-- fields --}}
            <div class="painting-form__column">

                {{-- name --}}
                <label class="painting-form__label">Name</label>
                <input class="painting-form__input painting-form__input--name" type="text" value="" placeholder="Painting name">

                {{-- link --}}
                <label class="painting-form__label">Link</label>
                <input class="painting-form__input painting-form__input--link" type="text" value="" placeholder="painting-name">

                {{-- price --}}
                <label class="painting-form__label">Price</label>
                <input class="painting-form__input painting-form__input--price" type="text" value="" placeholder="100">

                {{-- description --}}
                <label class="painting-form__label">Description</label>
                <textarea class="painting-form__textarea painting-form__textarea--description" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lobortis massa lobortis, molestie nisl ut, mattis sapien."></textarea>

                {{-- category --}}
                <label class="painting-form__label">Category</label>
                <select class="painting-form__select painting-form__select--category">
                    @foreach ($categoriesList as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                {{-- size --}}
                <label class="painting-form__label">Size</label>
                <select class="painting-form__select painting-form__select--size">
                    @foreach ($sizes as $size)
                        <option value="{{ $size }}">{{ strtoupper($size) }}</option>
                    @endforeach
                </select>

                {{-- orientation --}}
                <label class="painting-form__label">Orientation</label>
                <select class="painting-form__select painting-form__select--orientation">
                    @foreach ($orientations as $orientation)
                        <option value="{{ $orientation }}">{{ ucfirst($orientation) }}</option>
                    @endforeach
                </select>

                {{-- submit --}}
                <button class="painting-form__submit">Create new painting</button>

            </div>

        </div>
    </div>

@endsection