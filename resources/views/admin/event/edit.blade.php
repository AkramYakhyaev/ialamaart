@extends ('admin.layout')

@section ('title', $event->text)

@section ('panel')
    
    <a class="dashboard-header__button dashboard-header__button--red" id="event__delete-button">✖ Delete this event</a>

@endsection

@section ('content')
    
    <div class="event-form event-form--edit">
        <div class="event-form__column"></div>
        <div class="event-form__column">
            <input class="event-form__id" type="text" value="{{ $event->id }}" hidden>
            <label class="event-form__label">Date</label>
            <input class="event-form__date" type="date" value="{{ Carbon\Carbon::parse($event->created_at)->format('Y-m-d') }}">
            <label class="event-form__label">Text</label>
            <textarea class="event-form__text" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lobortis massa lobortis, molestie nisl ut, mattis sapien.">{{ $event->text }}</textarea>
            <input class="event-form__submit" type="submit" value="Update event">
        </div>
    </div>

@endsection