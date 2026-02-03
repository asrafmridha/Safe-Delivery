<style>

.app-btn.blu {
    background-color: #101010;
    transition: background-color 0.25s linear;
}
.app-btn {
    width: 45%;
    max-width: 160px;
    color: #fff;
    margin: 10px 10px;
    padding: 10px 0;
    text-align: left;
    border-radius: 5px;
    text-decoration: none;
    font-family: "Lucida Grande", sans-serif;
    font-size: 10px;
    text-transform: uppercase;
}
.flex {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
<footer class="main-footer mt-5 pt-5">
    <div class="footer-road">
        <div class="container position-relative" data-aos="fade-right">

            @if(!empty($application->logo))
                <img class="position-absolute footer-logo" height="90" src="{{ asset('uploads/application/'.$application->logo) }}" alt="Road">
            @else
                <img class="position-absolute footer-logo" height="90" src="{{ asset('uploads/application/'.$application->photo) }}" alt="Road">
            @endif

            {{--<img class="position-absolute footer-logo" height="90" src="{{ asset('assets/img/flyerlogo.jpg') }}" alt="Road">--}}
        </div>
    </div>
    <div class="footer-container text-light"  >
         <!--style="background: #053574;"-->
        <div class="container text-center text-sm-left">


            <div class="row mt-3">
                <div class="col-lg-4 col-md-9">
                    </br></br>
                    <p class="font-weight-thin">
                        {{ $application->address }}<br>
                        E-mail: {{ $application->email }} <br>
                        Hotline: {{ $application->contact_number }}
                    </p>
                </div>        
               

                <div class="col-lg-4 col-md-3 text-md-right mb-4 mb-md-0" style="padding-top: 70px">

                    @foreach ($socialLinks  as  $socialLink)


                    
                    <a href="{{$socialLink->url }}" class="text-decoration-none mr-1" style="font-size: 40px">
                     <i class="{{$socialLink->icon }}"></i>
                    </a>
                    
                    @endforeach

                    
                </div>
                
                <div class="col-lg-4 col-md-3 text-md-right mb-4 mb-md-0" style="padding-top: 40px">
                    <div class="flex social-btns" style="margin-top:17px;">
                        <a class="app-btn blu flex vert" href="{{ $application->app_link }}">
                        <i class="fab fa-google-play" style="font-size: 25px;"></i>
                        <p>Get it on <br/> <span class="big-txt">Google Play</span></p>
                      </a>
                        

                    </div>
                </div>
            </div>
        </div>
        


      <p class="footer-copytight text-center mt-4 mb-0">
        <a href="{{ route('frontend.getPrivacyPolicy') }}" class="nav-link text-white">Privacy Policy</a> Copyright &copy; {{ now()->year }} | {{ $application->name }}  
                </p>
    </div>

</footer>