@extends('layouts/contentLayoutMaster')

@section('title', 'Product Details')

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/swiper.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-ecommerce-details.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-number-input.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">

@endsection

@section('content')
    <!-- app e-commerce details start -->


    <section class="app-ecommerce-details">
        <div class="card">
            <!-- Product Details starts -->
            <div class="card-body">
                <div class="row my-2">
                    <x-image-box
                            :name="$images"
                            folder="product/{{$product->id}}"
                            class=""
                            alt=""
                    />
                    <div class="col-12 col-md-7">

                        <h4>{{$product->name}} </h4>
                        <span class="card-text item-company">By <a href="javascript:void(0)"
                                                                   class="company-name">{{$product->linea_name}}</a></span>
                        <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                            <h4 class="item-price mr-1">€{{$product->price}}</h4>

                        </div>
                        <p class="card-text">Available - <span class="text-success">In stock</span></p>
                        <p class="card-text">
                            {{$product->description}}
                        </p>

                        <hr/>
                        <div class="product-color-options">
                            <h6>Collection</h6>
                            <ul class="list-unstyled mb-0">
                                @foreach($products_collection as $product_collection)
                                    <a href="{{route('product.show',$product_collection->id)}}/">
                                        <li class="d-inline-block">
                                            <div class="color-option">
                                                <div class="filloption">
                                                    <img src="{{ URL::asset('/images/product/').'/'.$product_collection->image_defoult()}}"
                                                         alt="" height="65" width="100">
                                                </div>
                                            </div>
                                        </li>
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                        <hr/>
                        <div class="d-flex flex-column flex-sm-row pt-1">
                            <!-- <button type="button" class="btn btn-primary btn-cart mr-0 mr-sm-1 mb-1 mb-sm-0">
                              <i data-feather="shopping-cart" class="mr-50"></i>
                              <span class="add-to-cart">Add to cart</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-wishlist mr-0 mr-sm-1 mb-1 mb-sm-0">
                              <i data-feather="heart" class="mr-50"></i>
                              <span>Wishlist</span>
                            </button> -->
                            <div class="demo-inline-spacing">
                                <div class="input-group input-group-lg">
                                    <input type="number" id="qty" class="touchspin" value="1"/>
                                </div>
                                <a href="javascript:void(0)" id="add" data-id="{{ $product->id }}"
                                   class="btn btn-primary btn-cart mr-0 mr-sm-1 mb-1 mb-sm-0">
                                    <i data-feather="shopping-cart" class="mr-50"></i>
                                    <span class="add-to-cart">Add to cart</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Product Details ends -->

            <!-- Item features starts -->
            <div class="item-features">
                <div class="row text-center">
                    <div class="col-12 col-md-4 mb-4 mb-md-0">
                        <div class="w-75 mx-auto">
                            <i data-feather="award"></i>
                            <h4 class="mt-2 mb-1">100% Original</h4>
                            <p class="card-text">Chocolate bar candy canes ice cream toffee. Croissant pie cookie
                                halvah.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-4 mb-md-0">
                        <div class="w-75 mx-auto">
                            <i data-feather="clock"></i>
                            <h4 class="mt-2 mb-1">10 Day Replacement</h4>
                            <p class="card-text">Marshmallow biscuit donut dragée fruitcake. Jujubes wafer cupcake.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-4 mb-md-0">
                        <div class="w-75 mx-auto">
                            <i data-feather="shield"></i>
                            <h4 class="mt-2 mb-1">1 Year Warranty</h4>
                            <p class="card-text">Cotton candy gingerbread cake I love sugar plum I love sweet
                                croissant.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Item features ends -->

            <!-- Related Products starts -->
            <div class="card-body">
                <div class="mt-4 mb-2 text-center">
                    <h4>Related Products</h4>
                    <p class="card-text">People also search for this items</p>
                </div>
                <div class="swiper-responsive-breakpoints swiper-container px-4 py-2">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <a href="javascript:void(0)">
                                <div class="item-heading">
                                    <h5 class="text-truncate mb-0">Apple Watch Series 6</h5>
                                    <small class="text-body">by Apple</small>
                                </div>
                                <div class="img-container w-50 mx-auto py-75">
                                    <img src="{{ asset('images/elements/apple-watch.png') }}" class="img-fluid"
                                         alt="image"/>
                                </div>
                                <div class="item-meta">
                                    <ul class="unstyled-list list-inline mb-25">
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i>
                                        </li>
                                    </ul>
                                    <p class="card-text text-primary mb-0">$399.98</p>
                                </div>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="javascript:void(0)">
                                <div class="item-heading">
                                    <h5 class="text-truncate mb-0">Apple MacBook Pro - Silver</h5>
                                    <small class="text-body">by Apple</small>
                                </div>
                                <div class="img-container w-50 mx-auto py-50">
                                    <img src="{{ asset('images/elements/macbook-pro.png') }}" class="img-fluid"
                                         alt="image"/>
                                </div>
                                <div class="item-meta">
                                    <ul class="unstyled-list list-inline mb-25">
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i>
                                        </li>
                                    </ul>
                                    <p class="card-text text-primary mb-0">$2449.49</p>
                                </div>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="javascript:void(0)">
                                <div class="item-heading">
                                    <h5 class="text-truncate mb-0">Apple HomePod (Space Grey)</h5>
                                    <small class="text-body">by Apple</small>
                                </div>
                                <div class="img-container w-50 mx-auto py-75">
                                    <img src="{{ asset('images/elements/homepod.png') }}" class="img-fluid"
                                         alt="image"/>
                                </div>
                                <div class="item-meta">
                                    <ul class="unstyled-list list-inline mb-25">
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i>
                                        </li>
                                    </ul>
                                    <p class="card-text text-primary mb-0">$229.29</p>
                                </div>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="javascript:void(0)">
                                <div class="item-heading">
                                    <h5 class="text-truncate mb-0">Magic Mouse 2 - Black</h5>
                                    <small class="text-body">by Apple</small>
                                </div>
                                <div class="img-container w-50 mx-auto py-75">
                                    <img src="{{ asset('images/elements/magic-mouse.png') }}" class="img-fluid"
                                         alt="image"/>
                                </div>
                                <div class="item-meta">
                                    <ul class="unstyled-list list-inline mb-25">
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                    </ul>
                                    <p class="card-text text-primary mb-0">$90.98</p>
                                </div>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="javascript:void(0)">
                                <div class="item-heading">
                                    <h5 class="text-truncate mb-0">iPhone 12 Pro</h5>
                                    <small class="text-body">by Apple</small>
                                </div>
                                <div class="img-container w-50 mx-auto py-75">
                                    <img src="{{ asset('images/elements/iphone-x.png') }}" class="img-fluid"
                                         alt="image"/>
                                </div>
                                <div class="item-meta">
                                    <ul class="unstyled-list list-inline mb-25">
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i>
                                        </li>
                                        <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i>
                                        </li>
                                    </ul>
                                    <p class="card-text text-primary mb-0">$1559.99</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- Add Arrows -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
            <!-- Related Products ends -->
        </div>
    </section>

    <!-- app e-commerce details end -->
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/swiper.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}

    <script src="{{ asset(mix('js/scripts/forms/form-number-input.js')) }}"></script>
    <script>
        $(function () {
            'use strict';

            var productsSwiper = $('.swiper-responsive-breakpoints'),
                productOption = $('.product-color-options li'),
                btnCart = $('.btn-cart'),
                checkout = 'app-ecommerce-checkout.html';

            // Init Swiper
            if (productsSwiper.length) {
                new Swiper('.swiper-responsive-breakpoints', {
                    slidesPerView: 5,
                    spaceBetween: 55,
                    // init: false,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev'
                    },
                    breakpoints: {
                        1600: {
                            slidesPerView: 4,
                            spaceBetween: 55
                        },
                        1300: {
                            slidesPerView: 3,
                            spaceBetween: 55
                        },
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 55
                        },
                        320: {
                            slidesPerView: 1,
                            spaceBetween: 55
                        }
                    }
                });
            }
            // On cart & view cart btn click to v
            if (btnCart.length) {
                btnCart.on('click', function (e) {
                    var $this = $(this),
                        addToCart = $this.find('.add-to-cart');
                    if (addToCart.length > 0) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: '{{ route('cart.add') }}',
                            method: "GET",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                'qty': $('#qty').val(),
                                'id': $(this).data("id"),
                            },
                            success: function (response) {
                                e.preventDefault();
                                addToCart.text('{{__('locale.InTheCart')}}').removeClass('add-to-cart').addClass('view-in-cart');
                                $('span#totalshop').html(response);
                                toastr['success']('', '{{__('locale.AddedItemCart')}}', {
                                    closeButton: true,
                                    tapToDismiss: false
                                });

                            },
                            error: function (xhr, status, error) {
                                var err = eval("(" + xhr.responseText + ")");
                                toastr['error']('', '{{__('locale.DaGestire')}}', {
                                    closeButton: true,
                                    tapToDismiss: false
                                });
                            }
                        });

                        // $this.attr('href', checkout);

                    }
                });
            }

            // Product color options
            if (productOption.length) {
                productOption.on('click', function () {
                    $(this).addClass('selected').siblings().removeClass('selected');
                });
            }
        });

    </script>
@endsection
