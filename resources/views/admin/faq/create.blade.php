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
    <h5>Add Blog</h5>
    <br>
    <form action="{{ route('faq.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

       <div class="row mt-3">
            <div class="col-md-12">
                <label for="title">Category</label>
              <select name="faq_category" class="form-control">
                  <option value="front.index">home</option>
                  <option value="product.features.index">Product Features</option>
                  <option value="pricing.index">Pricing</option>
                  <option value="demotrain.index">Demo Training</option>
                  <option value="aboutus.index">About Us</option>
                  <option value="contactus.index">Contact US</option>
                  <option value="privacy-policys">Privacy Policy</option>
                  <option value="referandearn.index">Refer Earn</option>
                  <option value="blogs.index">Blogs</option>
                  <option value="privacy-policys">Privacy Policys</option>
                  <option value="cancellationpolicy.index">Cancellation and Refund Policy</option>
                  <option value="term-conditions">Term  Conditions</option>
              </select>
            </div>
        </div>
      
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="title">Question</label>
                <input type="text" name="question" class="form-control" placeholder="Enter Question">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <label for="description">Answer</label>
                <textarea name="answer" id="answer" class="form-control" placeholder="Enter answer"></textarea>
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
