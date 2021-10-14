@extends('layouts/contentLayoutMaster')

@section('title', 'Checkout')

@section('vendor-style')
    <!-- Vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection

@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-ecommerce.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-number-input.css')) }}">
@endsection
@section('content')


    <div class="bs-stepper checkout-tab-steps">
        @if($varied)
            @foreach($varied as $key => $message)
                <x-alert
                        :message="$message"
                        color="warning"
                />
            @endforeach
        @endif

        <!-- Wizard starts -->
        <div class="bs-stepper-header">
            <div class="step" data-target="#step-cart">
                <button type="button" class="step-trigger">
        <span class="bs-stepper-box">
          <i data-feather="shopping-cart" class="font-medium-3"></i>
        </span>
                    <span class="bs-stepper-label">
          <span class="bs-stepper-title">Cart</span>
          <span class="bs-stepper-subtitle">Your Cart Items</span>
        </span>
                </button>
            </div>
            <div class="line">
                <i data-feather="chevron-right" class="font-medium-2"></i>
            </div>
            <div class="step" data-target="#step-address">
                <button type="button" class="step-trigger">
        <span class="bs-stepper-box">
          <i data-feather="home" class="font-medium-3"></i>
        </span>
                    <span class="bs-stepper-label">
          <span class="bs-stepper-title">Address</span>
          <span class="bs-stepper-subtitle">Enter Your Address</span>
        </span>
                </button>
            </div>
            <div class="line">
                <i data-feather="chevron-right" class="font-medium-2"></i>
            </div>
            <div class="step" data-target="#step-payment">
                <button type="button" class="step-trigger">
        <span class="bs-stepper-box">
          <i data-feather="credit-card" class="font-medium-3"></i>
        </span>
                    <span class="bs-stepper-label">
          <span class="bs-stepper-title">Payment</span>
          <span class="bs-stepper-subtitle">Select Payment Method</span>
        </span>
                </button>
            </div>
        </div>
        <!-- Wizard ends -->

        <div class="bs-stepper-content">
            <!-- Checkout Place order starts -->
            <div id="step-cart" class="content">
                <div id="place-order" class="list-view product-checkout">
                    <!-- Checkout Place Order Left starts -->
                    <div class="checkout-items">
                        @foreach(Cart::content() as $item)
                        <div class="card ecommerce-card">
                            <div class="item-img">
                                <a href="{{url('app/ecommerce/details')}}">
                                    <img src="{{ asset('images/product/'.$item->model->image_defoult()) }}" alt="img-placeholder" />
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="item-name">
                                    <h6 class="mb-0"><a href="{{route('product.show',$item->id)}}" class="text-body">{{$item->model->name}}</a></h6>
                                    <span class="item-company">By <a href="javascript:void(0)" class="company-name">{{$item->options->linea}}</a></span>

                                </div>

                                <div class="item-quantity">
                                    <span class="quantity-title">Qty:</span>
                                    <div class="input-group quantity-counter-wrapper">
                                        <input type="text" id="{{$item->id}}" class="quantity-counter" onchange="updateQty({{$item->id}})" value="{{$item->qty}}" />
                                    </div>
                                </div>

                            </div>
                            <div class="item-options text-center">
                                <div class="item-wrapper">
                                    <div class="item-cost">
                                        <h4 class="item-price">€{{$item->price}}</h4>
                                        @if($item->options->price > $item->price)
                                        <p class="card-text shipping">
                                            <span class="badge badge-pill badge-warning"><del>€{{$item->options->price}}</del></span>
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{route('cart.del',[$item->id])}}" class="btn btn-light mt-1 remove-wishlist">
                                    <i data-feather="x" class="align-middle mr-25"></i>
                                    <span>Remove</span>
                                </a>

                            </div>
                        </div>

                        @endforeach
                    </div>
                    <!-- Checkout Place Order Left ends -->

                    <!-- Checkout Place Order Right starts -->
                    <div id="details" class="checkout-options">
                        <div class="card">
                            <div class="card-body">
                                <div class="price-details">
                                    <h6 class="price-title">Order Details</h6>
                                    <ul class="list-unstyled">
                                        <li class="price-detail">
                                            <div class="detail-title">Total Product</div>
                                            <div class="detail-amt">{{Cart::count()}}</div>
                                        </li>
                                        <li class="price-detail">
                                            <div class="detail-title">Linea Order</div>
                                            <div class="detail-amt"><span class="item-company">By <a href="javascript:void(0)" class="company-name">{{$linea}}</a></span></div>
                                        </li>
                                    </ul>
                                </div>
                                <hr />
                                <div  class="price-details">
                                    <h6 class="price-title">Price Details</h6>
                                    <ul class="list-unstyled">
                                        <li class="price-detail">
                                            <div class="detail-title">Total Imponible</div>
                                            <div class="detail-amt">€{{Cart::subtotal()}}</div>
                                        </li>
                                        <li class="price-detail">
                                            <div class="detail-title">Bag Discount</div>
                                            <div class="detail-amt discount-amt text-success">-25$</div>
                                        </li>
                                        <li class="price-detail">
                                            <div class="detail-title">Total Tax</div>
                                            <div class="detail-amt">€{{Cart::instance('shopping')->subtotal() / 100 * 22 }}</div>
                                        </li>

                                    </ul>
                                    <hr />
                                    <ul class="list-unstyled">
                                        <li class="price-detail">
                                            <div class="detail-title detail-total">Total</div>
                                            <div class="detail-amt font-weight-bolder">€{{Cart::instance('shopping')->total() + Cart::instance('shopping')->subtotal() / 100 * 22}}</div>
                                        </li>
                                    </ul>
                                    <button type="button" class="btn btn-primary btn-block btn-next place-order">Place Order</button>
                                </div>
                            </div>
                        </div>
                        <!-- Checkout Place Order Right ends -->
                    </div>
                </div>
                <!-- Checkout Place order Ends -->
            </div>
            <!-- Checkout Customer Address Starts -->
            <div id="step-address" class="content">
                <form id="checkout-address" class="list-view product-checkout">
                    <!-- Checkout Customer Address Left starts -->
                    <div class="card">
                        <div class="card-header flex-column align-items-start">
                            <h4 class="card-title">Add New Address</h4>
                            <p class="card-text text-muted mt-25">Be sure to check "Deliver to this address" when you have finished</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="checkout-name">Full Name:</label>
                                        <input type="text" id="checkout-name" class="form-control" name="fname" placeholder="John Doe" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="checkout-number">Mobile Number:</label>
                                        <input
                                                type="number"
                                                id="checkout-number"
                                                class="form-control"
                                                name="mnumber"
                                                placeholder="0123456789"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="checkout-apt-number">Flat, House No:</label>
                                        <input
                                                type="number"
                                                id="checkout-apt-number"
                                                class="form-control"
                                                name="apt-number"
                                                placeholder="9447 Glen Eagles Drive"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="checkout-landmark">Landmark e.g. near apollo hospital:</label>
                                        <input
                                                type="text"
                                                id="checkout-landmark"
                                                class="form-control"
                                                name="landmark"
                                                placeholder="Near Apollo Hospital"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="checkout-city">Town/City:</label>
                                        <input type="text" id="checkout-city" class="form-control" name="city" placeholder="Tokyo" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="checkout-pincode">Pincode:</label>
                                        <input type="number" id="checkout-pincode" class="form-control" name="pincode" placeholder="201301" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="checkout-state">State:</label>
                                        <input type="text" id="checkout-state" class="form-control" name="state" placeholder="California" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="add-type">Address Type:</label>
                                        <select class="form-control" id="add-type">
                                            <option>Home</option>
                                            <option>Work</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary btn-next delivery-address">Save And Deliver Here</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Checkout Customer Address Left ends -->

                    <!-- Checkout Customer Address Right starts -->
                    <div class="customer-card">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">John Doe</h4>
                            </div>
                            <div class="card-body actions">
                                <p class="card-text mb-0">9447 Glen Eagles Drive</p>
                                <p class="card-text">Lewis Center, OH 43035</p>
                                <p class="card-text">UTC-5: Eastern Standard Time (EST)</p>
                                <p class="card-text">202-555-0140</p>
                                <button type="button" class="btn btn-primary btn-block btn-next delivery-address mt-2">
                                    Deliver To This Address
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Checkout Customer Address Right ends -->
                </form>
            </div>
            <!-- Checkout Customer Address Ends -->

            <!-- Checkout Payment Starts -->
            <div id="step-payment" class="content">
                <form id="checkout-payment" class="list-view product-checkout" onsubmit="return false;">
                    <div class="payment-type">
                        <div class="card">
                            <div class="card-header flex-column align-items-start">
                                <h4 class="card-title">Payment options</h4>
                                <p class="card-text text-muted mt-25">Be sure to click on correct payment option</p>
                            </div>
                            <div class="card-body">
                                <h6 class="card-holder-name my-75">John Doe</h6>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="customColorRadio1" name="paymentOptions" class="custom-control-input" checked />
                                    <label class="custom-control-label" for="customColorRadio1">
                                        US Unlocked Debit Card 12XX XXXX XXXX 0000
                                    </label>
                                </div>
                                <div class="customer-cvv mt-1">
                                    <div class="form-inline">
                                        <label class="mb-50" for="card-holder-cvv">Enter CVV:</label>
                                        <input
                                                type="password"
                                                class="form-control ml-sm-75 ml-0 mb-50 input-cvv"
                                                name="input-cvv"
                                                id="card-holder-cvv"
                                        />
                                        <button type="button" class="btn btn-primary btn-cvv ml-0 ml-sm-1 mb-50">Continue</button>
                                    </div>
                                </div>
                                <hr class="my-2" />
                                <ul class="other-payment-options list-unstyled">
                                    <li class="py-50">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customColorRadio2" name="paymentOptions" class="custom-control-input" />
                                            <label class="custom-control-label" for="customColorRadio2"> Credit / Debit / ATM Card </label>
                                        </div>
                                    </li>
                                    <li class="py-50">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customColorRadio3" name="paymentOptions" class="custom-control-input" />
                                            <label class="custom-control-label" for="customColorRadio3"> Net Banking </label>
                                        </div>
                                    </li>
                                    <li class="py-50">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customColorRadio4" name="paymentOptions" class="custom-control-input" />
                                            <label class="custom-control-label" for="customColorRadio4"> EMI (Easy Installment) </label>
                                        </div>
                                    </li>
                                    <li class="py-50">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customColorRadio5" name="paymentOptions" class="custom-control-input" />
                                            <label class="custom-control-label" for="customColorRadio5"> Cash On Delivery </label>
                                        </div>
                                    </li>
                                </ul>
                                <hr class="my-2" />
                                <div class="gift-card mb-25">
                                    <p class="card-text">
                                        <i data-feather="plus-circle" class="mr-50 font-medium-5"></i>
                                        <span class="align-middle">Add Gift Card</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="amount-payable checkout-options">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Price Details</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled price-details">
                                    <li class="price-detail">
                                        <div class="details-title">Price of 3 items</div>
                                        <div class="detail-amt">
                                            <strong>$699.30</strong>
                                        </div>
                                    </li>
                                    <li class="price-detail">
                                        <div class="details-title">Delivery Charges</div>
                                        <div class="detail-amt discount-amt text-success">Free</div>
                                    </li>
                                </ul>
                                <hr />
                                <ul class="list-unstyled price-details">
                                    <li class="price-detail">
                                        <div class="details-title">Amount Payable</div>
                                        <div class="detail-amt font-weight-bolder">$699.30</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Checkout Payment Ends -->
            <!-- </div> -->
        </div>
    </div>

@endsection

@section('vendor-script')
    <!-- Vendor js files -->
    <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/pages/app-ecommerce-checkout.js')) }}"></script>
    <script>
        function updateQty(id){

            $.ajax({
                url: '{{ route('cart.qty') }}',
                method: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'qty': $('#'+id).val(),
                    'id': id,
                },
                success: function (response) {
                    $('#details').load(document.URL +  ' #details');
                    $('span#totalshop').html(response);
                    toastr['success']('', '{{__('locale.UpdateQtyItemCart')}}', {
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
        }
    </script>
@endsection
