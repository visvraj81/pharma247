@extends('layouts.main')

@section('main')
<style>
    .btn-primary:hover {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }

    .btn-primary {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }

    .btn-primary.focus,
    .btn-primary:focus {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }

    .card-body {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .col-md-3 {
        flex: 1 0 45%;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Update Pages Data</h5>
                        <form method="POST" action="{{ route('update.page.meta') }}">
                            @csrf

                            @foreach ($pageMetaTags as $tag)
                                <div class="card mb-3" style="width: 100%; border: 1px solid #ddd;">
                                    <div class="card-header">
                                        <h6>Meta Data for URL: {{ $tag->url }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="ids[]" value="{{ $tag->id }}">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Meta Title</label>
                                                    <input type="text" class="form-control" name="meta_title[{{ $tag->id }}]" value="{{ $tag->meta_title }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Meta Description</label>
                                                    <textarea class="form-control" name="meta_description[{{ $tag->id }}]">{{ $tag->meta_description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Meta Keywords</label>
                                                    <input type="text" class="form-control" name="meta_keywords[{{ $tag->id }}]" value="{{ $tag->meta_keywords }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>OG Title</label>
                                                    <input type="text" class="form-control" name="og_title[{{ $tag->id }}]" value="{{ $tag->og_title }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>OG Description</label>
                                                    <textarea class="form-control" name="og_description[{{ $tag->id }}]">{{ $tag->og_description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Twitter Title</label>
                                                    <input type="text" class="form-control" name="twitter_title[{{ $tag->id }}]" value="{{ $tag->tiwter_title }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Twitter Description</label>
                                                    <textarea class="form-control" name="twitter_description[{{ $tag->id }}]">{{ $tag->tiwter_description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                          <br>
                            @endforeach

                            <button type="submit" class="btn btn-primary">Update Pages</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
