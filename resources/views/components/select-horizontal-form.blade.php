<div class="form-group row">
    <div class="col-sm-3 col-form-label">
        <label for="first-name">{{__('locale.'.ucfirst($title))}}
            {{($required ? '*':'')}}
        </label>
    </div>
    <div class="col-sm-9">
        <select class="form-control" id="{{$title}}" name="{{$title}}" {{($required ? 'required':'')}}>
            <option value = '' {{(!$defoult ? 'selected':'')}}>-- Seleziona {{__('locale.'.ucfirst($title))}} --</option>
            @if($type === null)

                @foreach ($values as $value)
                    @if(!is_null($defoult) && $value->id == $defoult)
                        <option value = {{$value->id}} selected>{{$value->name}}</option>
                    @else
                        <option value = {{$value->id}} >{{$value->name}}</option>
                    @endif
                @endforeach
            @else
                @foreach ($get_values($type) as $key => $value)
                    @if($key === $defoult)

                        <option value = {{$key}} selected>{{$value}}</option>
                    @else

                        <option value = {{$key}} >{{$value}}</option>
                    @endif
                @endforeach
            @endif
        </select>
    </div>
    <div class="invalid-feedback">{{__('locale.Form error value')}}.</div>
</div>