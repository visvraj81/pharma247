<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  {{ isset($blogs->title) ? $blogs->title : ""}}</title>
    <meta name="description"
        content="{{isset($blogs->sort_descrption) ?$blogs->sort_descrption :''}}">
    <meta name="keywords"
        content="{{isset($blogs->key_word) ?$blogs->key_word :''}}">
    <meta name="author" content="Pharma24*7">
    <meta name='subject' content='Pharmacy Management Solutions'>
    <meta name='copyright' content="Pharma24*7">
    <meta name='classification' content="Healthcare and Pharmacy Solutions">
    <meta name='reply-to' content="inquiry@pharma247.in">
    <meta name='owner' content="Pharma24*7">
    <meta name='url' content="https://pharma247.in">

    <meta property="og:url" content="https://pharma247.in" />
    <meta property="og:title" content="{{ isset($blogs->title) ? $blogs->title : ''}}" />
    <meta property="og:description"
        content="{{isset($blogs->sort_descrption) ?$blogs->sort_descrption :''}}" />
    <meta property="og:image" content="https://medical.pharma247.in/pharmalogo.png" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Pharma24*7" />

    <meta name="twitter:site" content="@Pharma247">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ isset($blogs->title) ? $blogs->title : ''}}">
    <meta name="twitter:description"
        content="Offering innovative solutions to streamline pharmacy operations and enhance patient care.">
    <meta name="twitter:image" content="https://medical.pharma247.in/pharmalogo.png">

    <meta property="business:contact_data:street_address" content="SF-14/B, Dharti City Complex, Kadi - 382715" />
    <meta property="business:contact_data:country_name" content="India" />
    <meta property="business:contact_data:email" content="inquiry@pharma247.in" />
    <meta property="business:contact_data:phone_number" content="+91 908 1111 247" />
    <meta property="business:contact_data:website" content="https://pharma247.in">
    <link rel="shortcut icon" href="https://medical.pharma247.in/pharmalogo.png" type="image/x-icon">
    <link rel="icon" type="image/png" href="https://medical.pharma247.in/pharmalogo.png" />
    <link rel="canonical" href="https://pharma247.in/" />
    <link rel="alternate" hreflang="x" href="https://pharma247.in/">
    <link rel="stylesheet"
        href="{{ asset('public/landing_design/css/bootstrap-5.3.0-alpha1-dist/css/bootstrap.min.css') }}?v={{ rand(1111, 9999) }}">
    <link rel="stylesheet" href="{{ asset('public/landing_design/css/style.css') }}?v2={{ rand(1111, 9999) }}">
    <link rel="stylesheet" href="{{ asset('public/landing_design/css/grid.css') }}?v2={{ rand(1111, 9999) }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- slick slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <!-- swiper slider -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.1/css/swiper.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- animation  -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- flatpicker -->
    <link rel="stylesheet" href="flatpicker/flatpickr-master/config/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

</head>

<body class="home">
    @include('front.menu')

  <style>
    .ad-section {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    position: sticky;
    top: 150px;
}
  </style>
    <main class="singlblosmain ">
        <section class="section_margin bslogsec">
            <div class="blogbody">
                <div class="blogbodydiv">
                    <div class="container">
                      <div class="d-flex justify-content-between align-items-start mb-30" style="border: 1px solid #8080805c;
    padding: 6px 12px;
    border-radius: 10px;">
                        <a href="javascript:;" class="d-flex align-items-center m-auto ms-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <img src="https://pharma247.in/public/landing_design/images/logo.png" alt="writer" width="50" class="img-fluid rounded-circle me-3">
                                </div>
                                <div class="avatar-info text-start">
                                    <span class="small fw-medium text-muted">Written by</span>
                                    <h6 class="mb-0 avatar-name" style="color: black;">Pharma24*7</h6>
                                  
                                </div>
                            </div>
                        </a>

                        <div class="row share-blog">
                             
                            <ul class="list-unstyled footer-nav-list mb-lg-0 d-flex align-items-center justify-content-center" style="    gap: 12px;
">
                                Share &nbsp;
                                <li class="list-inline-item facebook"><a  target="_blank" style="color: rgb(88 96 108);" href="https://www.facebook.com/people/Pharma247/61568780619517/?mibextid=ZbWKwL" target="_blank" rel="noopener nofollow"><i class="fab fa-facebook-f"></i></a></li>
                                <li class="list-inline-item twitter"><a target="_blank" style="color: rgb(88 96 108);"  href="https://x.com/Pharma24_7?t=OGys8DNHJlt0tOoW98WJmw&s=09&mx=2" target="_blank" rel="noopener nofollow"><i class="fab fa-twitter"></i></a></li>
                                <li class="list-inline-item linkedin"><a target="_blank" style="color: rgb(88 96 108);"  href="https://www.instagram.com/pharma24_7/profilecard/?igsh=MTkwNWk1OXRlNXE0aA%3D%3D" target="_blank" rel="noopener nofollow"><i class="fa-brands fa-instagram"></i></a></li>

                            </ul>

                        </div>
                    </div>
                        <div class="row flex-column-reverse flex-lg-row row-gap-3 ">
                            <div class="col-xl-8 col-lg-12">
                                <div class="blogdivrow">
                                    <div class="accordion-item">
                                        <div class="bloglistdiv mt-4 mb-4">
                                            <div class="bloglistimg">
                                                <img src="{{ asset('/public/uploads/students/' . $blogs->image) }}"
                                                    class="img-fluid" alt="{{ 'Pharma24*7' }}"
                                                    title="{{ 'Pharma24*7' }}">>
                                            </div>
                                        </div>
                                        <div class="blogexpanddiv" aria-labelledby="flush-headingOne"
                                            data-bs-parent="#blogaccordian">
                                            <h5 class="blog-title my-3 fw-bold">
                                                {{ isset($blogs->title) ? $blogs->title : ""}}
                                            </h5>
                                            <p class="blog-description my-3 fw-meidum">
                                                <?php echo htmlspecialchars_decode($blogs->description); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <br>
                                    <div>
                                        <?php
                                        $tags = explode(',', $blogs->tags);
                                        ?>
                                        @if(is_array($tags) && count($tags) > 0)
                                        @foreach($tags as $list)
                                        <label class="p-2 px-2 py-0 py-1 rounded-5 mb-1"
                                            style="background-color:var(--themecolor); color:white">#{{isset($list) ? $list :""}}</label>
                                        @endforeach
                                        @endif
                                    </div>
                                    </d>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-12 mt-4" style="    border: 2px solid #f1f1f3;padding: 25px 12px;border-radius: 10px;">
                                <div class="ad-section text-center">
                                    <h4 class="mb-4 fw-bold theme-text text-start">Related Blogs</h4>
                                    <div class="d-flex flex-column gap-3">
                                        <?php
                                        $blogsData = App\Models\BlogModel::where('id','!=',$blogs->id)->take(5)->get();

                                        ?>
                                        @if(isset($blogsData))
                                        @foreach($blogsData as $listData)
                                       <a href="{{ route('singleblog', ['title' => \Str::slug($listData->title)]) }}">
                                        <div class="align-items-start d-flex gap-3">
                                            <img src="{{ asset('/public/uploads/students/' . $listData->image) }}" class="img-fluid" alt="Ad Image"
                                                width="100px" height="100px" style="object-fit:contain; border-radius:0.3125rem;">
                                            <div class="text-start">
                                                <h6 style="color:#020202; font-size:15px;">
                                                    {{ isset($blogs->title) ? $blogs->title : ""}}
                                                </h6>
                                                <p style="line-height:normal; font-size:14px;color: rgb(0 0 0);"> <?php echo htmlspecialchars_decode(\Illuminate\Support\Str::limit($listData->sort_descrption, 150, '...')); ?></p>
                                            </div>
                                        </div>
                                       </a>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('front.footer')