@extends ('admin.layout')

@section ('title', 'Add event')

@section ('content')
    
    <div class="event-form event-form--add">
        <div class="event-form__column"></div>
        <div class="event-form__column">
            <label class="event-form__label">Date</label>
            <input class="event-form__date" type="date">
            <label class="event-form__label">Text</label>
            <textarea class="event-form__text" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lobortis massa lobortis, molestie nisl ut, mattis sapien."></textarea>
            <input class="event-form__submit" type="submit" value="Create new event">
        </div>
    </div>

@endsection