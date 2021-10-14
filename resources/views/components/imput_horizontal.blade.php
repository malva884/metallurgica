<div class="form-group row">
    <div class="col-sm-3 col-form-label">
        <label for="first-name">{{__('locale.'.ucfirst($title))}}
            {{($required ? '*':'')}}
        </label>
    </div>
    <div class="col-sm-9">
        <input type="{{$type}}" id="first-name" class="form-control"
               name="{{$title}}"
               placeholder="{{__('locale.'.ucfirst($title))}}"
               value="{{$value}}"
               step=0.01
                {{($required ? 'required':'')}}
               />
    </div>
    <div class="invalid-feedback">{{__('locale.Form error value')}}.</div>
</div>