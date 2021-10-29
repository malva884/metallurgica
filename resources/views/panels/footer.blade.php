<!-- BEGIN: Footer-->
<footer class="footer {{($configData['footerType']=== 'footer-hidden') ? 'd-none':''}} footer-light">
  <p class="clearfix mb-0">
    <!--span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2020<a class="ml-25" href="https://1.envato.market/pixinvent_portfolio" target="_blank">Pixinvent</a>
      <span class="d-none d-sm-inline-block">, All rights Reserved</span>
    </span -->
      <span class="float-md-left d-block d-md-inline-block mt-25">
      <span class="d-none d-sm-inline-block">V.: {{config('app.app_version')}}</span>
    </span>
  </p>
</footer>
<!-- END: Footer-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


<script>
        var getNewNotifications = function () {

            $.getJSON('{{route('notification.refresh')}}', function (response) {
                $('span#notify').html(response.length);
              $('.media-list').empty();
                if(response.length > 0){
                  for (var i = 0; i < response.length; i++) {
                    let checked = '';
                    if (response[i]['fixsed'])
                      checked = 'checked';
                    $('#message').append(
                            '<a class="d-flex" href="/' + response[i]['data']['route'] + '">' +
                            ' <div class="media d-flex align-items-start">' +
                            '             <div class="media-left">' +
                            '               <div class="avatar bg-light-danger">' +
                            '                 <div class="avatar-content">'+response[i]['data']['op']+ '</div>' +
                            '               </div>' +
                            '             </div>' +
                            '             <div class="media-body">' +
                            '               <p class="media-heading"><span' +
                            '                       class="font-weight-bolder">' + response[i]['data']['title'] + '</span>&nbsp;' +
                            '               </p><small' +
                            '                     class="notification-text">' + response[i]['data']['message'] + '</small>' +
                            '             </div>' +
                            '           </div>' +
                            '         </a>'
                    );
                  }
                }else{
                  $('#message').append(
                          '<div class="media d-flex align-items-center">' +
                  '<h6 class="font-weight-bolder mr-auto mb-0">Nessuna Notifica</h6>' +
                  '</div>'
                  );
                }
            });
            // Ask for new notifications every second

        };

        setInterval(getNewNotifications, 60000);


 </script>

