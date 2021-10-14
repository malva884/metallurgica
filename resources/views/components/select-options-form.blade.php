    <label>{{__('locale.'.ucfirst($title))}} {{($required ? '*':'')}}</label>
    <select class="select2 form-control form-control-lg" id="{{$title}}" name="{{$title}}" {{($required ? 'required':'')}}>
        <option value = '' {{(!$defoult ? 'selected':'')}}>-- Seleziona {{__('locale.'.ucfirst($title))}} --</option>
        @if($type == null)
            @foreach ((array)$values as $key => $value)
                @if($key == $defoult)
                    <option value = {{$key}} selected>{{$value}}</option>
                @else
                    <option value = {{$key}} >{{$value}}</option>
                @endif
            @endforeach
        @else
            @foreach ($get_default_values($type) as $key => $value)
                @if($key === $defoult)
                    <option value = {{$key}} selected>{{$value}}</option>
                @else
                    <option value = {{$key}} >{{$value}}</option>
                @endif
            @endforeach
        @endif

    </select>
