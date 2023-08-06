<option value="{{ $value }}">{{ $name }}</option>
@if (!empty($children))
    <optgroup>
        @foreach ($children as $child)
            <x-option-tree :value="$child['id']" :name="$child['name']" :children="$child['children'] ?? []" />
        @endforeach
    </optgroup>
@endif