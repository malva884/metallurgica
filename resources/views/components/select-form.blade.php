<div class="form-group">
    <label for="status">{{__('locale.'.ucfirst($title))}} {{($required ? '*':'')}}</label>
    <select class="form-control" id="{{$title}}" name="{{$title}}" {{($required ? 'required':'')}}>
        <option value = '' {{(!$defoult ? 'selected':'')}}>-- Seleziona {{__('locale.'.ucfirst($title))}} --</option>
        @if($type === null)
            @foreach ($values as $key => $value)
                @if(!empty($value->id))
                    @if(!is_null($defoult) && $value->id == $defoult)
                        <option value = {{$value->id}} selected>{{$value->name}}</option>
                    @else

                        <option value = {{$value->id}} >{{$value->name}}</option>
                    @endif
                @else
                    @if(!is_null($defoult) && $key == $defoult)
                        <option value = {{$key}} selected>{{$value}}</option>
                    @else

                        <option value = {{$key}} >{{$value}}</option>
                    @endif
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
</div>