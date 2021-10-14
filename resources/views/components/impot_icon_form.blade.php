<label for="{{$title}}">
    {{__('locale.'.ucfirst($title))}}
    {{($required ? ' *':'')}}
</label>
<div class="input-group input-group-merge mb-2">
    <div class="input-group-prepend">
        <span class="input-group-text">{{$icon}}</span>
    </div>
    <input
            type="{{$type}}"
            id="{{$title}}"
            name="{{$title}}"
            class="form-control"
            placeholder="{{__('locale.'.ucfirst($title))}}"
            aria-label="{{ucfirst($title)}}"
            aria-describedby="{{$title}}"
            value="{{$value}}"
            {{($required ? 'required':'')}}
             />
    <!--div class="input-group-append">
        <span class="input-group-text">.00</span>
    </div -->
</div>