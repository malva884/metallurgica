<div class="form-group">
    <div class="col-lg-2 col-md-4">
        <div class="custom-control custom-checkbox custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="{{$title}}" id="{{$value['name']}}"
                   @if($defaults)
                       @if(is_array($defaults))
                           {{($value['id'] == $defaults ? 'checked':'')}}
                       @elseif(is_object($defaults))
                           {{($value['id'] == $defaults->$label ? 'checked':'')}}
                        @elseif(is_bool($defaults))
                           {{($defaults === true ? 'checked':'')}}
                       @else
                            {{($value['id'] == $defaults ? 'checked':'')}}

                       @endif
                   @endif
            />
            <label class="custom-control-label" for="{{$value['name']}}">
                @if($translation)
                    {{__('locale.'.$value['name'])}}
                @else
                    {{$value['name']}}
                @endif
            </label>
        </div>
    </div>
</div>


