<div class="input-group align-items-center">
    @php
        $elements = \App\Models\Element::all();
    @endphp

    <select @if(isset($dropdownId)) id="{{ $dropdownId }}" @endif class="form-select bs-gray-100 choose_period noselect" name="element_id">
        @foreach($elements as $element)
            <option value="{{$element->id}}">{{$element->name}}</option>
        @endforeach
    </select>
</div>
