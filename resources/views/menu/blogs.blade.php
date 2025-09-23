@include('front.header')
<style>
.lonyo-section-padding7 {
    display: none;
}

.lonyo-cta-section {
    display: none;
}
</style>
<div class="breadcrumb-wrapper light-bg">
    <div class="container">

        <div class="breadcrumb-content">
            <h1 class="breadcrumb-title pb-0 text-white">Blog</h1>
            <div class="breadcrumb-menu-wrapper">
                <div class="breadcrumb-menu-wrap">
                    <div class="breadcrumb-menu">
                        <ul>
                            <li><a href="{{route('front.index')}}" class="text-white">Home</a></li>
                            >
                            <li aria-current="page" class="text-white">Blog</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- End breadcrumb -->

<div class="lonyo-section-padding9 overflow-hidden">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="row gap-4">
                    @foreach($blogs as $blog)
                    <div class="lonyo-blog-wrap col-lg-5" data-aos="fade-up" data-aos-duration="500">
                        <a href="{{ route('singleblog', ['title' => \Str::slug($blog->title)]) }}">
                            <div class="lonyo-blog-thumb">
                                <img src="{{ asset('/public/uploads/students/' . $blog->image) }}" alt="">
                            </div>
                            <div class="lonyo-blog-meta">
                                <ul>
                                    <li>
                                        <a href="{{ route('singleblog', ['title' => \Str::slug($blog->title)]) }}"><img
                                                src="{{asset('public/landing_desgin/assets/images/blog/date.svg')}}"
                                                alt="">{{ $blog->created_at->format('F d, Y') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="lonyo-blog-content">
                                <h4><a
                                        href="{{ route('singleblog', ['title' => \Str::slug($blog->title)]) }}">{{ $blog->title }}</a>
                                </h4>
                                <p>{!! \Illuminate\Support\Str::limit(htmlspecialchars_decode($blog->sort_descrption),
                                    150, '...') !!}</p>
                            </div>
                            <div class="lonyo-blog-btn">
                                <a class='lonyo-default-btn blog-btn'
                                    href="{{ route('singleblog', ['title' => \Str::slug($blog->title)]) }}">Continue reading</a>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
              
                <div class="lonyo-pagination center">
                    {{-- Previous page link --}}
                    @if ($blogs->onFirstPage())
                    <a class='pagi-btn btn2 disabled'>
                        <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.75 0.75L6 6L0.75 11.25" stroke="#001A3D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    @else
                    <a class='pagi-btn btn2' href="{{ $blogs->appends(request()->except('page'))->previousPageUrl() }}" rel="prev">
                        <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.75 0.75L6 6L0.75 11.25" stroke="#001A3D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    @endif

                    <ul>
                        {{-- First page link --}}
                        @if ($blogs->currentPage() > 2)
                        <li><a href="{{ $blogs->appends(request()->except('page'))->url(1) }}">1</a></li>
                        @if ($blogs->currentPage() > 3)
                        <li><span>...</span></li>
                        @endif
                        @endif

                        {{-- Dynamic page numbers --}}
                        @for ($i = max(1, $blogs->currentPage() - 1); $i <= min($blogs->lastPage(), $blogs->currentPage() + 1); $i++)
                            <li>
                                <a class="{{ $i == $blogs->currentPage() ? 'current' : '' }}"
                                    href="{{ $blogs->appends(request()->except('page'))->url($i) }}">
                                    {{ $i }}
                                </a>
                            </li>
                            @endfor

                            {{-- Last page link --}}
                            @if ($blogs->currentPage() < $blogs->lastPage() - 1)
                                @if ($blogs->currentPage() < $blogs->lastPage() - 2)
                                    <li><span>...</span></li>
                                    @endif
                                    <li><a href="{{ $blogs->appends(request()->except('page'))->url($blogs->lastPage()) }}">{{ $blogs->lastPage() }}</a></li>
                                    @endif
                    </ul>

                    {{-- Next page link --}}
                    @if ($blogs->hasMorePages())
                    <a class='pagi-btn' href="{{ $blogs->appends(request()->except('page'))->nextPageUrl() }}" rel="next">
                        <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.75 0.75L6 6L0.75 11.25" stroke="#001A3D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    @else
                    <a class='pagi-btn disabled'>
                        <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.75 0.75L6 6L0.75 11.25" stroke="#001A3D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
                      
            <div class="col-lg-4">
                <div class="lonyo-blog-sidebar" data-aos="fade-left" data-aos-duration="700">
                    <div class="lonyo-blog-widgets">
                        <form action="#">
                            <div class="lonyo-search-box">
                                <input type="search" id="search-input" placeholder="Type keyword here">
                                <button id="lonyo-search-btn" type="button"><i class="ri-search-line"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="lonyo-blog-widgets">
                        <h4>Categories:</h4>
                        <div class="lonyo-blog-categorie">
                            <ul id="category-list">
                                @if(isset($categoryData))
                                @foreach($categoryData as $listData)
                                <?php 
                                $blogData = App\Models\BlogModel::where('category_id',$listData->id)->count();
                                ?>
                                <li>
                                    <a href="{{ route('blogs.index', ['id' => $listData->id]) }}" class="category-item">{{ isset($listData->categories) ? $listData->categories :'' }}
                                        <span>{{$blogData}}</span></a>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="lonyo-blog-widgets">
                        <h4>Latest Posts</h4>
                        @foreach($blogsLatest as $blogData)
                        <a class='lonyo-blog-recent-post-item'
                            href="{{ route('singleblog', ['title' => \Str::slug($blogData->title)]) }}">
                            <div class="lonyo-blog-recent-post-thumb">
                                <img src="{{ asset('/public/uploads/students/' . $blogData->image) }}" alt=""
                                    style="width: 100px;height: 60px;">
                            </div>
                            <div class="lonyo-blog-recent-post-data">
                                <ul>
                                    <li><img src="{{asset('public/landing_desgin/assets/images/blog/date.svg')}}"
                                            alt="">{{ $blogData->created_at->format('F d, Y') }}</li>
                                </ul>
                                <div>
                                    <h4>{{ $blogData->title }}</h4>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <div class="lonyo-blog-widgets">
                        <h4>Tags</h4>
                        <div class="lonyo-blog-tags">
                            <ul>
                                @if(isset($blogTags->tags) && !empty($blogTags->tags))
                                @php
                                $tagsData = explode(',', $blogTags->tags);
                                @endphp
                                @foreach($tagsData as $tags)
                                <li><a href="{{ route('blogs.index', ['tag' => $tags]) }}">{{ $tags ?? '-' }}</a></li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('front.footer')