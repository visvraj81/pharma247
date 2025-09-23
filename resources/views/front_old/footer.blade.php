<!--<hr>
 <button class="chatbot-btn" onclick="toggleChat()">💬</button>-->

    <!-- Chatbot Container (Expanded) -->
    <!-- <div id="chatbot-container" class="chatbot-container">
        <div>
            <div class="chatbot-header">
                <div style="display: flex; align-items: center;">
                    <img src="{{ asset('public/imgpsh_fullsize_anim.png') }}" alt="Chatbot Logo">
                    <span>Pharma24*7</span>
                </div>
                <button class="close-btn" onclick="closeChat()">×</button>
            </div>
            <div class="chatbot-body" id="chatbot-body">-->
                <!-- Admin's Constant Message -->
                <!--<div class="message admin-message">
                    <img src="{{ asset('public/imgpsh_fullsize_anim.png') }}"
                        alt="Admin Icon" class="icon">
                    <span>Hello! How can I help you with your pharmacy needs today?</span>
                </div>
            </div>
        </div>
        <div class="user-input-wrapper user-input">
            <input type="text" id="user-input" class="user-inputt"
                placeholder="Ask about pharmacy services..." />
            <button class="send-icon" onclick="sendMessage()">➤</button>
        </div>
    </div>-->
<footer class="mt-5 mb-0 p-md-5 px-0 py-5">
    <div class="container-fluid ">
        <div class="ftrrow">
            <div class="row justify-content-between row-gap-4">
                <div class="col-lg-3">
                    <div class="footerlinkdiv">
                        <ul class="footerul list-unstyled ">
                            <li class="footerli">
                                <a class="navbar-brand col-md-2 col-sm-3 col-4" href="">
                                    <img src="{{asset('public/landing_design/images/logo.png')}}" class="img-fluid"
                                        width="150"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}">>
                                </a>
                                <p class="mt-3">
                                    Pharma24*7 is your trusted partner in pharmacy management, offering innovative
                                    solutions
                                    to streamline your operations and enhance patient care.
                                </p>
                            </li>
                            <li class="footerli d-flex gap-3 mt-3">
                                <a href="https://www.facebook.com/profile.php?id=61568780619517&mibextid=ZbWKwL"
                                    class="footerlink socialicon"><i class="fa-brands fa-facebook-f"
                                        style="font-size:x-large; color: #115e9c;"></i></a>
                                <a href="https://www.instagram.com/pharma24_7/profilecard/?igsh=MTkwNWk1OXRlNXE0aA=="
                                    class="footerlink socialicon"><i class="fa-brands fa-instagram"
                                        style="font-size:x-large; color:#d62976;"></i></a>
                                <a href="https://x.com/Pharma24_7?t=OGys8DNHJlt0tOoW98WJmw&s=09"
                                    class="footerlink socialicon"><i class="fa-brands fa-x-twitter"
                                        style="font-size:x-large"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footerlinkdiv">
                        <span style="font-size: 25px;" class="fw-bold mb-4">Quick Links</span>
                        <ul class="footerul row text-capitalize">
                            <li class="footerli col-sm-6 mb-3">
                                <a href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="footerli col-sm-6 mb-3">
                                <a href="{{ route('product.features.index') }}">product & Features</a>
                            </li>
                            <li class="footerli col-sm-6 mb-3">
                                <a href="{{ route('pricing.index') }}">pricing</a>
                            </li>
                            <li class="footerli col-sm-6 mb-3">
                                <a href="{{ route('demotrain.index') }}">Demo & Training</a>
                            </li>
                            <li class="footerli col-sm-6 mb-3">
                                <a href="{{ route('aboutus.index') }}">about us</a>
                            </li>
                            <li class="footerli col-sm-6 mb-3">
                                <a href="{{ route('referandearn.index') }}">Refer & Earn</a>
                            </li>
                            <li class="footerli col-sm-6 mb-3">
                                <a href="{{ route('contactus.index') }}">contact us</a>
                            </li>
                            <li class="footerli col-sm-6 mb-3">
                                <a href="{{ route('blogs.index') }}">blogs</a>
                            </li>
                            <li class="footerli col-sm-6 mb-3">
                                <a href="{{ route('privacy-policys') }}">privacy policy</a>
                            </li>
                            <li class="footerli col-sm-6 mb-3">
                                <a href="{{ route('cancellationpolicy.index') }}">Cancellation and Refund Policy</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footerlinkdiv">
                        <span style="font-size: 25px;" class="fw-bold">Contact Info</h4>
                        <ul class="footerul list-unstyled p-0">
                            <li class="footerli">
                                <a href="https://maps.app.goo.gl/c48UAV1fhRB31Kg19" class="d-flex gap-3">
                                    <span><i class="fa fa-map-marker" aria-hidden="true"
                                            style="color:var(--themecolor)"></i></span>
                                    SF-14/B DHARTI CITY COMPLEX KADI 382715
                                </a>
                            </li>
                            <li class="footerli">
                                <p class="d-flex gap-3">
                                    <span><i class="fa fa-phone" aria-hidden="true"
                                            style="color:var(--themecolor)"></i></span>
                                    <span class="rightcontactbox">
                                        <!-- Tel : <b class="phoneno_line"> +91 261 6501377</b></br> -->
                                        <?php
                                                $UserData = \App\Models\User::with('roles')->first();
                                            ?>
                                        <a href="#"
                                            class="phoneno_line">{{ isset($UserData->phone_number) ? $UserData->phone_number :""}}</a><br>
                                    </span>
                                </p>
                            </li>
                            <li class="footerli">
                                <p class="d-flex gap-3">
                                    <span><i class="fa fa-envelope" aria-hidden="true"
                                            style="color:var(--themecolor)"></i></span>
                                    <span class="rightcontactbox">
                                        <a href="mailto:info@slbanthia.in">inquiry@pharma247.in</a>
                                    </span>
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

  <script>
    // Toggles the chatbot visibility
    function toggleChat() {
      const chatContainer = document.getElementById('chatbot-container');
      chatContainer.classList.toggle('open');
    }

    // Closes the chatbot
    function closeChat() {
      const chatContainer = document.getElementById('chatbot-container');
      chatContainer.classList.remove('open');
    }

    // Function to send user input and display response
    function sendMessage() {
      const userInput = document.getElementById('user-input').value;
      const chatBody = document.getElementById('chatbot-body');

      if (userInput.trim()) {
        // Display user message
        const userMessage = document.createElement('div');
        userMessage.classList.add('message', 'user-message');
        userMessage.innerHTML = `<span>${userInput}</span>`;
        chatBody.appendChild(userMessage);

        // Clear input field
        document.getElementById('user-input').value = '';

        // Scroll to bottom
        chatBody.scrollTop = chatBody.scrollHeight;

        // Add a simple pharmacy response based on keyword matching
        setTimeout(() => {
          const botMessage = document.createElement('div');
          botMessage.classList.add('message', 'bot-message');
          let response = '';

       if (userInput.toLowerCase().includes('medication') || userInput.toLowerCase().includes('medicine')) {
        response = '<img src="{{ asset('public/imgpsh_fullsize_anim.png') }}" style="width: 25px;" alt="Admin Icon" /> We have a wide range of medications available, including prescriptions and over-the-counter drugs. What can I assist you with?'; 
    } else if (userInput.toLowerCase().includes('prescription')) {
        response = '<img src="{{ asset('public/imgpsh_fullsize_anim.png') }}" style="width: 25px;" alt="Admin Icon" /> You can submit your prescription online, and we can prepare it for pickup or delivery.';
    } else if (userInput.toLowerCase().includes('price')) {
        response = '<img src="{{ asset('public/imgpsh_fullsize_anim.png') }}" style="width: 25px;" alt="Admin Icon" /> Please specify the medication you want to inquire about, and I can provide the price.';
    } else {
        response = '<img src="{{ asset('public/imgpsh_fullsize_anim.png') }}" style="width: 25px;" alt="Admin Icon" /> I\'m here to assist you with any pharmacy-related questions. Can you please specify?';
    }


          botMessage.innerHTML = `<span>${response}</span>`;
          chatBody.appendChild(botMessage);

          // Scroll to bottom
          chatBody.scrollTop = chatBody.scrollHeight;
        }, 1000);
      }
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('public/landing_design/css/bootstrap-5.3.0-alpha1-dist/js/bootstrap.bundle.js')}}?v2={{ rand(1111, 9999) }}"></script>
<script
    src="{{asset('public/landing_design/css/bootstrap-5.3.0-alpha1-dist/js/bootstrap.bundle.js')}}?v2={{ rand(1111, 9999) }}">
</script>
<!-- slick slider -->
<script src="https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick.min.js"></script>
<!-- swiper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.1/js/swiper.min.js"></script>

<!-- ion icons -->
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<!-- animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>
<script>
// sticky header
const header = document.querySelector(".page-header");
const toggleClass = "is-sticky";

window.addEventListener("scroll", () => {
    const currentScroll = window.pageYOffset;
    if (currentScroll > 100) {
        header.classList.add(toggleClass);
    } else {
        header.classList.remove(toggleClass);
    }
});
</script>
<script>
    @if(Session::has('success'))
    toastr.success("{{ Session::get('success') }}");
    @endif


    @if(Session::has('info'))
    toastr.info("{{ Session::get('info') }}");
    @endif


    @if(Session::has('warning'))
    toastr.warning("{{ Session::get('warning') }}");
    @endif


    @if(Session::has('error'))
    toastr.error("{{ Session::get('error') }}");
    @endif
</script>
<script>
    $(".servindu00").slick({
        slidesToShow: 5,
        infinite: false,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        responsive: [{
                breakpoint: 991,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 2,
                }
            }
        ]
        // dots: false, Boolean
        // arrows: false, Boolean
    });
$(".servindu00").slick({
    slidesToShow: 5,
    infinite: false,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [{
            breakpoint: 991,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 575,
            settings: {
                slidesToShow: 2,
            }
        }
    ]
    // dots: false, Boolean
    // arrows: false, Boolean
});
</script>

<!-- testimonial -->
<script>
$(document).ready(function() {
    $('#contact_form').submit(function(e) {
        e.preventDefault();

        // Clear any previous validation feedback
        $('.invalid-feedback').hide();

        // Flag for invalid form
        var isValid = true;

        // Check if 'Pharmacy Name' field is empty
        if (!$('#name_data').val()) {
            // Instead of `.next()`, use `.siblings()` to find the feedback message
            $('#name_data').siblings('.invalid-feedback').css('display',
            'block'); // Force display of error message
            isValid = false; // Form is not valid
        }

        // Check other fields similarly
        if (!$('#email').val()) {
            $('#email').next('.invalid-feedback').css('display', 'block'); // Show error message
            isValid = false;
        }

        if (!$('#phone').val()) {
            $('#phone').next('.invalid-feedback').css('display', 'block'); // Show error message
            isValid = false;
        }

        // If any field is invalid, stop the form submission
        if (!isValid) {
            $(this).addClass('was-validated');
            return; // Don't submit the form
        }

        // If form is valid, proceed with AJAX submission
        var formData = $(this).serialize();


        $.ajax({
            url: "{{ route('insert_contact') }}",
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thank you!',
                    text: 'Your contact form has been submitted successfully.',
                    showConfirmButton: true
                }).then(() => {
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'An error occurred',
                    text: 'Please try again later.',
                    showConfirmButton: true
                });
            }
        });
    });
});



$(document).on('click', '.btn-outline-themegreen', function() {
    var time = $(this).text();
    var date = $("#datepicker").val();
    const url = new URL(window.location.href);

    const id = url.searchParams.get('id');
    time = time.trim();
    $("#plan_id_select").val(id);
    $("#time_select").val(time);
    $("#data_select").val(date);

});
$(document).ready(function() {
    var googleswiper = new Swiper('.googleslider', {
        loop: true,
        slidesPerView: 3, // Set to 1 for better control of slide duration
        spaceBetween: 20,
        speed: 1000, // 1-second transition speed between slides
        autoplay: 2000, // In Swiper 3.x, autoplay is set directly to the delay time in ms
        nextButton: '.swiper-button-nextt',
        prevButton: '.swiper-button-prevv',
        autoplayDisableOnInteraction: false, // Continue autoplay after interaction
        breakpoints: {
            // When window width is <= 991px
            1199: {
                slidesPerView: 2,
            },
            // When window width is <= 768px
            768: {
                slidesPerView: 1,
            }
        }
    });
});
</script>


<script>
$(document).ready(function() {
    // Swiper: Slider
    new Swiper('.whywediff--slider', {
        loop: true,
        nextButton: '.swiper-button-nextt',
        prevButton: '.swiper-button-prevv',
        slidesPerView: 3.5,
        paginationClickable: true,
        spaceBetween: 20,
        breakpoints: {
            1299: {
                slidesPerView: 3,
                spaceBetween: 30
            },
            1199: {
                slidesPerView: 2.5,
                spaceBetween: 30
            },
            768: {
                slidesPerView: 1.5,
                spaceBetween: 30
            },
            575: {
                slidesPerView: 1,
                spaceBetween: 10
            }
        }
    });
});
</script>

<!-- hero slider -->
<script>
$(document).ready(function() {
    // Swiper: Slider for version 3.4.1
    var mySwiper = new Swiper('.mySwiper', {
        loop: true,
        nextButton: '.swiper-button-nextt', // For Swiper 3.x, use nextButton
        prevButton: '.swiper-button-prevv', // For Swiper 3.x, use prevButton
        slidesPerView: 1,
        spaceBetween: 20,
        speed: 1000, // 1-second transition speed between slides
        autoplay: 2000, // In Swiper 3.x, autoplay is set directly to the delay time in ms
        autoplayDisableOnInteraction: false, // Continue autoplay after interaction
    });
});
</script>
<!-- hero slider end -->

<script>
AOS.init({
    duration: 1200,
})
</script>

<script>
// Initialize Flatpickr for date selection
flatpickr("#datepicker", {
    dateFormat: "Y-m-d",
    inline: true,
    onChange: function(selectedDates, dateStr) {
        // Show the time picker when a date is selected
        //document.getElementById("time-picker").style.display = "block";
        document.getElementById("time-picker").classList.add("d-block");
    }
});

// Initialize Flatpickr for time selection
flatpickr("#timepicker", {
    //   enableTime: true,
    noCalendar: true,
    inline: true
    //dateFormat: "H:i"
});
</script>
<script>
// Initialize Flatpickr for date selection
flatpickr("#datepickerscdl", {
    dateFormat: "Y-m-d",
    inline: true,
    onChange: function(selectedDates, dateStr) {
        // Show the time picker when a date is selected
        //document.getElementById("time-picker").style.display = "block";
        document.getElementById("time-pickerscdl").classList.add("d-block");
    }
});

// Initialize Flatpickr for time selection
flatpickr("#timepickerscdl", {
    //   enableTime: true,
    noCalendar: true,
    inline: true
    //dateFormat: "H:i"
});
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-NZPGLEP3J4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-NZPGLEP3J4');
</script>
</body>

</html>