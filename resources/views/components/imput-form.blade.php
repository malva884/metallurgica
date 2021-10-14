<div class="form-group">
    <label for="{{$title}}">
        {{__('locale.'.ucfirst($title))}}
        {{($required ? ' *':'')}}
    </label>
    @if(!is_null($length) )
        <div class="col-md-{{$length}}">
    @endif
    <input
            type="{{$type}}"
            id="{{$title}}"
            name="{{$title}}"
            class="{{$class}}"
            placeholder="{{__('locale.'.ucfirst($title))}}"
            aria-label="{{ucfirst($title)}}"
            aria-describedby="{{$title}}"
            value="{{$value}}"
            {{($required ? 'required':'')}}
            {{( !empty($readonly) ? 'readonly':'')}}
            {{$exstra}}   />
    @if($length)
        </div>
    @endif
    <!-- div class="valid-feedback">Looks good!</div -->
    <div class="invalid-feedback">{{__('locale.Form error value')}}.</div>

</div>