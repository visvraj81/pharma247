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

.cke_notification_warning {
    display: none !important;
}
</style>
<div class="card p-3">
    <h5>Edit FAQ</h5>
    <br>
    <form action="{{ route('update.faq') }}" method="POST" enctype="multipart/form-data">
        @csrf

         <div class="row mt-3">
            <div class="col-md-12">
                <label for="title">Category</label>
                <select name="faq_category" class="form-control">
                    <option value="front.index" <?= ($faqDelete->faq_category == 'front.index') ? 'selected' : '' ?>>Home</option>
                    <option value="product.features.index" <?= ($faqDelete->faq_category == 'product.features.index') ? 'selected' : '' ?>>Product Features</option>
                    <option value="pricing.index" <?= ($faqDelete->faq_category == 'pricing.index') ? 'selected' : '' ?>>Pricing</option>
                    <option value="demotrain.index" <?= ($faqDelete->faq_category == 'demotrain.index') ? 'selected' : '' ?>>Demo Training</option>
                    <option value="aboutus.index" <?= ($faqDelete->faq_category == 'aboutus.index') ? 'selected' : '' ?>>About Us</option>
                    <option value="contactus.index" <?= ($faqDelete->faq_category == 'contactus.index') ? 'selected' : '' ?>>Contact Us</option>
                    <option value="privacy-policys" <?= ($faqDelete->faq_category == 'privacy-policys') ? 'selected' : '' ?>>Privacy Policy</option>
                    <option value="referandearn.index" <?= ($faqDelete->faq_category == 'referandearn.index') ? 'selected' : '' ?>>Refer & Earn</option>
                    <option value="blogs.index" <?= ($faqDelete->faq_category == 'blogs.index') ? 'selected' : '' ?>>Blogs</option>
                    <option value="cancellationpolicy.index" <?= ($faqDelete->faq_category == 'cancellationpolicy.index') ? 'selected' : '' ?>>Cancellation and Refund Policy</option>
                    <option value="term-conditions" <?= ($faqDelete->faq_category == 'term-conditions') ? 'selected' : '' ?>>Terms & Conditions</option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <label for="title">Question</label>
                <input type="hidden" name="id" value="{{$faqDelete->id}}" />
                <input type="text" name="question" class="form-control"
                    value="{{isset($faqDelete->question) ? $faqDelete->question :''}}" placeholder="Enter Question">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <label for="answer" class="form-label">Answer</label>
                <textarea name="answer" id="answer" class="form-control" placeholder="Enter answer"
                    rows="6" style="resize: vertical;">{{ $faqDelete->answer }}</textarea>
            </div>
        </div>
        <br>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
    </form>
</div>

@endsection

@section('js')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
$(document).ready(function() {
    $('#numericInput').on('keypress', function(event) {
        var charCode = (event.which) ? event.which : event.keyCode;
        // Allow only numbers (48-57) and backspace (8)
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 8) {
            event.preventDefault();
        }
    });

    $('.image').change(function() {
        var curElement = $('.imageshow');
        console.log(curElement);
        var reader = new FileReader();

        reader.onload = function(e) {
            curElement.attr('src', e.target.result);
        };

        reader.readAsDataURL(this.files[0]);
    });

    // Initialize CKEditor for the description field
    CKEDITOR.replace('answer');
});
</script>
@endsection