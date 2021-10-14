@section('content-sidebar')
    <!-- Ecommerce Sidebar Starts -->
    <div class="sidebar-shop">
        <div class="row">
            <div class="col-sm-12">
                <h6 class="filter-heading d-none d-lg-block">Filters</h6>
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <!-- Price Filter starts -->
                <div class="multi-range-price">
                    <h6 class="filter-title mt-0">Multi Range</h6>
                    <ul class="list-unstyled price-range" id="price-range">
                        <li>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="priceAll" name="price-range" class="custom-control-input"
                                       checked/>
                                <label class="custom-control-label" for="priceAll">All</label>
                            </div>
                        </li>
                        <li>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="priceRange1" name="price-range" class="custom-control-input"/>
                                <label class="custom-control-label" for="priceRange1">&lt;=$10</label>
                            </div>
                        </li>
                        <li>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="priceRange2" name="price-range" class="custom-control-input"/>
                                <label class="custom-control-label" for="priceRange2">$10 - $100</label>
                            </div>
                        </li>
                        <li>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="priceARange3" name="price-range" class="custom-control-input"/>
                                <label class="custom-control-label" for="priceARange3">$100 - $500</label>
                            </div>
                        </li>
                        <li>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="priceRange4" name="price-range" class="custom-control-input"/>
                                <label class="custom-control-label" for="priceRange4">&gt;= $500</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Price Filter ends -->


                <!-- Categories Starts -->
                @if(!empty($categories))
                    <h6 class="filter-title">{{__('locale.Categories')}}</h6>
                    <select class="select2 form-control" id="category" onchange="redirect()">
                        <option value="" >-- Categoria --</option>
                        @foreach($categories as $category)
                            @if(is_null($category->parent_id))
                                <optgroup label="{{$category->name}}">
                                    @else
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endif

                                    @if(is_null($category->parent_id))
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                @endif

            <!-- Categories Ends -->


            </div>
        </div>
    </div>
    <!-- Ecommerce Sidebar Ends -->
@endsection


