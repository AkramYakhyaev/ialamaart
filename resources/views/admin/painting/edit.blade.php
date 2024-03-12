@extends ('admin.layout')

@section ('title', $painting->name)

@section ('panel')
    
    @if ($painting->availability === 0)
        <a class="dashboard-header__button" id="painting__set-available-button">Set available</a>
    @endif
    <a class="dashboard-header__button dashboard-header__button--red" id="painting__delete-button">✖ Delete this painting</a>

@endsection

@section ('content')
    
    <div class="painting-form painting-form--edit">
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
                            @if (file_exists('storage/paintings/'.$painting->id.'/'.$i.'.jpg'))
                                <div class="painting-form__upload-button painting-form__upload-button--hidden">
                                    <div>
                                        <i class="material-icons">file_upload</i>
                                        <span>Upload main photo</span>
                                    </div>
                                </div>
                                <div class="painting-form__wrap-preview">
                                    <div class="painting-form__preview" style="background-image: url({{ asset('/storage/paintings/'.$painting->id.'/'.$i.'.jpg') }});"></div>
                                    <div class="painting-form__preview-overlay">
                                        <span class="painting-form__delete-preview" title="Delete this image"><i class="material-icons">close</i></span>
                                    </div>
                                </div>
                            @else
                                <div class="painting-form__upload-button">
                                    <div>
                                        <i class="material-icons">file_upload</i>
                                        <span>Upload main photo</span>
                                    </div>
                                </div>
                                <div class="painting-form__wrap-preview painting-form__wrap-preview--hidden">
                                    <div class="painting-form__preview"></div>
                                    <div class="painting-form__preview-overlay">
                                        <span class="painting-form__delete-preview" title="Delete this image"><i class="material-icons">close</i></span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
                <div class="painting-form__images-notice"></div>

            </div>

            {{-- fields --}}
            <div class="painting-form__column">

                {{-- id --}}
                <input class="painting-form__id" type="text" value="{{ $painting->id }}" hidden>

                {{-- name --}}
                <label class="painting-form__label">Name</label>
                <input class="painting-form__input painting-form__input--name" type="text" value="{{ $painting->name }}" placeholder="Painting name">

                {{-- link --}}
                <label class="painting-form__label">Link</label>
                <input class="painting-form__input painting-form__input--link" type="text" value="{{ $painting->link }}" placeholder="painting-name">

                {{-- price --}}
                <label class="painting-form__label">Price</label>
                <input class="painting-form__input painting-form__input--price" type="text" value="{{ $painting->price }}" placeholder="100">

                {{-- description --}}
                <label class="painting-form__label">Description</label>
                <textarea class="painting-form__textarea painting-form__textarea--description" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lobortis massa lobortis, molestie nisl ut, mattis sapien.">{{ $painting->description }}</textarea>

                {{-- category --}}
                <label class="painting-form__label">Category</label>
                <select class="painting-form__select painting-form__select--category">
                    @foreach ($categoriesList as $category)
                        @if ($category->name == $painting->category()->first()->name)
                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                        @else
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endif
                    @endforeach
                </select>

                {{-- size --}}
                <label class="painting-form__label">Size</label>
                <select class="painting-form__select painting-form__select--size">
                    @foreach ($sizes as $size)
                        @if ($size == $painting->size)
                            <option value="{{ $size }}" selected>{{ strtoupper($size) }}</option>
                        @else
                            <option value="{{ $size }}">{{ strtoupper($size) }}</option>
                        @endif
                    @endforeach
                </select>

                {{-- orientation --}}
                <label class="painting-form__label">Orientation</label>
                <select class="painting-form__select painting-form__select--orientation">
                    @foreach ($orientations as $orientation)
                        @if ($orientation == $painting->orientation)
                            <option value="{{ $orientation }}" selected>{{ ucfirst($orientation) }}</option>
                        @else
                            <option value="{{ $orientation }}">{{ ucfirst($orientation) }}</option>
                        @endif
                    @endforeach
                </select>

                {{-- submit --}}
                <button class="painting-form__submit">Update painting</button>

            </div>

        </div>
    </div>

@endsection