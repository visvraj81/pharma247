@include('front.header')

<body class="blogs">
    @include('front.menu')

    <style>
        .page-link {
            color: #628a2f;
        }

        .active>.page-link,
        .page-link.active {
            z-index: 3;
            color: var(--bs-pagination-active-color);
            background-color: #628a2f;
            border-color: #628a2f;
        }

        .page-link:hover {
            z-index: 2;
            color: #628a2f;
        }
    </style>

    <main class="blosmain">
        <section class="section_margin blogsec">
            <div class="abthalf--div title-block text-center">
                <h1 class="mb-4" style="font-size: 28px;">Blogs</h1>
            </div>
            <div class="container">
                <div class="row flex-column-reverse flex-lg-row row-gap-3">
                    <!-- Left Blog Section -->
                    <div class="col-xl-8 col-lg-12">
                        @foreach($blogs as $blog)
                        <div class="blog-card overflow-hidden serviceindustryslider mb-3">
                            <a href="{{ route('singleblog', ['title' => \Str::slug($blog->title)]) }}">
                                <div class="row align-content-center">
                                    <div class="col-md-6">
                                        <div class="blgimgdiv h-100">
                                            <img src="{{ asset('/public/uploads/students/' . $blog->image) }}"
                                                alt="{{ $blog->title }}" class="img-fluid h-100 w-100 object-fit-cover">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="blgocontentdiv p-4 rounded-lg shadow-sm">
                                            <h2 class="blog-title mb-3 text-dark font-weight-bold"
                                                style="font-size: 1.3rem; line-height: 1.5;">
                                                {{ $blog->title }}
                                            </h2>
                                            <p class="blog-description mb-4 text-muted"
                                                style="font-size: 1rem; line-height: 1.6;">
                                                {!! \Illuminate\Support\Str::limit(htmlspecialchars_decode($blog->sort_descrption), 150, '...') !!}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="font-weight-medium"
                                                    style="font-size: 0.875rem;color:rgb(98 138 47) !important;">
                                                    Created By: <strong>Pharma24*7</strong>
                                                </span>
                                                <p class="theme-text font-weight-semibold"
                                                    style="font-size: 0.875rem; color:rgb(98 138 47) !important;">
                                                    {{ $blog->created_at->format('F d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="pagination-container text-center mt-4">
                            <nav aria-label="Blog pagination">
                                <ul class="pagination justify-content-center">
                                    {{-- Previous page link --}}
                                    @if ($blogs->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link" aria-disabled="true">&laquo; Previous</span>
                                    </li>
                                    @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $blogs->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
                                    </li>
                                    @endif

                                    {{-- Page numbers with skipping logic --}}
                                    @php
                                        $start = max(1, $blogs->currentPage() - 1);
                                        $end = min($blogs->lastPage(), $blogs->currentPage() + 1);
                                    @endphp

                                    {{-- First page link --}}
                                    @if ($start > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $blogs->url(1) }}">1</a>
                                    </li>
                                    @if ($start > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    @endif

                                    {{-- Dynamic page numbers --}}
                                    @for ($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $i == $blogs->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $blogs->url($i) }}">{{ $i }}</a>
                                    </li>
                                    @endfor

                                    {{-- Last page link --}}
                                    @if ($end < $blogs->lastPage())
                                    @if ($end < $blogs->lastPage() - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $blogs->url($blogs->lastPage()) }}">{{ $blogs->lastPage() }}</a>
                                    </li>
                                    @endif

                                    {{-- Next page link --}}
                                    @if ($blogs->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $blogs->nextPageUrl() }}" rel="next">Next &raquo;</a>
                                    </li>
                                    @else
                                    <li class="page-item disabled">
                                        <span class="page-link" aria-disabled="true">Next &raquo;</span>
                                    </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <!-- Right Advertisement Section -->
                    <div class="col-xl-4 col-lg-12">
                        <div class="ad-section text-center">
                            <h3 class="mb-3 fw-bold theme-text">Pharma24*7 Ad</h3>
                            <img src="{{ asset('public/landing_design/images/mobileapp.png') }}" class="img-fluid"
                                alt="Ad Image" width="300px">
                            <p class="mt-3 fw-medium">Get a <strong>FREE Trial</strong> Now!</p>
                            <a href="https://medical.pharma247.in/Register">
                                <button class="btn btn-ad fw-medium">Sign Up Today</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('front.footer')
</body>
