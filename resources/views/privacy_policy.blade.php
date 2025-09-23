@extends('layouts.main')
@section('main')
<style>
    .cke_notification_warning {
        display: none !important;
    }

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
</style>
<div class="card p-3">
    <form action="{{ route('privacy_policy.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="content">Privacy Policy</label>
            <textarea name="content" id="content" class="form-control">{{ isset($privacyProlicy->description) ? $privacyProlicy->description :""}}</textarea>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
    </form>
</div>

@endsection

@section('js')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content', {
        height: 400 // Set the height of the editor (in pixels)
    });
</script>
@endsection