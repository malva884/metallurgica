<div class="form-group row">
    <div class="col-sm-3 col-form-label">
        <label for="first-name">{{__('locale.'.ucfirst($title))}}
            {{($required ? '*':'')}}
        </label>
    </div>
    <div class="col-sm-9">
        <textarea
                class="form-control"
                id="exampleFormControlTextarea1"
                rows="3"
                placeholder="{{__('locale.'.ucfirst($title))}}"
                name="{{$title}}"
                {{($required ? 'required':'')}}
        >{{$value}}</textarea>

    </div>
    <div class="invalid-feedback">{{__('locale.Form error value')}}.</div>
</div>





