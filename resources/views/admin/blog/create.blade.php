@extends('layouts.main')
@section('main')
<style>
.btn-primary:hover,
.btn-primary:focus {
    color: #fff;
    background-color: #628a2f;
    border-color: #628a2f;
}

.btn-primary {
    color: #fff;
    background-color: #628a2f;
    border-color: #628a2f;
}

.cke_notification_warning {
    display: none !important;
}

.ck-editor__editable_inline {
    min-height: 300px;
}


.tags-input-wrapper {
    background: transparent;
    padding: 10px;
    border-radius: 4px;
    /* max-width: 400px; */
    border: 1px solid #ccc
}

.tags-input-wrapper input {
    border: none;
    background: transparent;
    outline: none;
    width: 140px;
    margin-left: 8px;
}

.tags-input-wrapper .tag {
    display: inline-block;
    background-color: #3a5e0b;
    color: white;
    border-radius: 40px;
    padding: 0px 3px 0px 7px;
    margin-right: 5px;
    margin-bottom: 5px;
}

.tags-input-wrapper .tag a {
    margin: 0 7px 3px;
    display: inline-block;
    cursor: pointer;
}
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="card p-3">
    <h5>Add Blog</h5>
    <br>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-3">
            <div class="col-md-5">
                <label for="image">Image</label>
                <input type="file" name="image" class="form-control image" id="imageInput" required>
                <img class="imageshow" id="imagePreview" src="http://via.placeholder.com/700x500" width="100px"
                    style="margin-top: 10px;">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter title" required>
            </div>
            <div class="col-md-6">
                <label for="title">Category</label>
                <select name="category_id" class="form-control">
                  @if(isset($CategoryData))
                  @foreach($CategoryData as $list)
                  <option value="{{$list->id}}">{{$list->categories}}</option>
                  @endforeach
                  @endif
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="sort_description">Short Description</label>
                <input type="text" name="sort_description" class="form-control" placeholder="Enter Short Description">
            </div>
            <div class="col-md-6">
                <label for="key_word">Keyword</label>
                <input type="text" name="key_word" class="form-control" placeholder="Enter Keyword">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <input type="text" name="tags" id="tag-input1" placeholder="Enter Tags">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="description">Description</label>
                <textarea name="description" id="editor"></textarea>
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
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#editor'), {
        ckfinder: {
            uploadUrl: "{{ route('ckeditor.upload') . '?_token=' . csrf_token() }}",
        }
    })
    .catch(error => console.error(error));

document.getElementById('imageInput').addEventListener('change', function(event) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('imagePreview').src = e.target.result;
    }
    reader.readAsDataURL(this.files[0]);
});


(function() {

    "use strict"


    // Plugin Constructor
    var TagsInput = function(opts) {
        this.options = Object.assign(TagsInput.defaults, opts);
        this.init();
    }

    // Initialize the plugin
    TagsInput.prototype.init = function(opts) {
        this.options = opts ? Object.assign(this.options, opts) : this.options;

        if (this.initialized)
            this.destroy();

        if (!(this.orignal_input = document.getElementById(this.options.selector))) {
            console.error("tags-input couldn't find an element with the specified ID");
            return this;
        }

        this.arr = [];
        this.wrapper = document.createElement('div');
        this.input = document.createElement('input');
        init(this);
        initEvents(this);

        this.initialized = true;
        return this;
    }

    // Add Tags
    TagsInput.prototype.addTag = function(string) {

        if (this.anyErrors(string))
            return;

        this.arr.push(string);
        var tagInput = this;

        var tag = document.createElement('span');
        tag.className = this.options.tagClass;
        tag.innerText = string;

        var closeIcon = document.createElement('a');
        closeIcon.innerHTML = '&times;';

        // delete the tag when icon is clicked
        closeIcon.addEventListener('click', function(e) {
            e.preventDefault();
            var tag = this.parentNode;

            for (var i = 0; i < tagInput.wrapper.childNodes.length; i++) {
                if (tagInput.wrapper.childNodes[i] == tag)
                    tagInput.deleteTag(tag, i);
            }
        })


        tag.appendChild(closeIcon);
        this.wrapper.insertBefore(tag, this.input);
        this.orignal_input.value = this.arr.join(',');

        return this;
    }

    // Delete Tags
    TagsInput.prototype.deleteTag = function(tag, i) {
        tag.remove();
        this.arr.splice(i, 1);
        this.orignal_input.value = this.arr.join(',');
        return this;
    }

    // Make sure input string have no error with the plugin
    TagsInput.prototype.anyErrors = function(string) {
        if (this.options.max != null && this.arr.length >= this.options.max) {
            console.log('max tags limit reached');
            return true;
        }

        if (!this.options.duplicate && this.arr.indexOf(string) != -1) {
            console.log('duplicate found " ' + string + ' " ')
            return true;
        }

        return false;
    }

    // Add tags programmatically 
    TagsInput.prototype.addData = function(array) {
        var plugin = this;

        array.forEach(function(string) {
            plugin.addTag(string);
        })
        return this;
    }

    // Get the Input String
    TagsInput.prototype.getInputString = function() {
        return this.arr.join(',');
    }


    // destroy the plugin
    TagsInput.prototype.destroy = function() {
        this.orignal_input.removeAttribute('hidden');

        delete this.orignal_input;
        var self = this;

        Object.keys(this).forEach(function(key) {
            if (self[key] instanceof HTMLElement)
                self[key].remove();

            if (key != 'options')
                delete self[key];
        });

        this.initialized = false;
    }

    // Private function to initialize the tag input plugin
    function init(tags) {
        tags.wrapper.append(tags.input);
        tags.wrapper.classList.add(tags.options.wrapperClass);
        tags.orignal_input.setAttribute('hidden', 'true');
        tags.orignal_input.parentNode.insertBefore(tags.wrapper, tags.orignal_input);
    }

    // initialize the Events
    function initEvents(tags) {
        tags.wrapper.addEventListener('click', function() {
            tags.input.focus();
        });


        tags.input.addEventListener('keydown', function(e) {
            var str = tags.input.value.trim();

            if (!!(~[9, 13, 188].indexOf(e.keyCode))) {
                e.preventDefault();
                tags.input.value = "";
                if (str != "")
                    tags.addTag(str);
            }

        });
    }


    // Set All the Default Values
    TagsInput.defaults = {
        selector: '',
        wrapperClass: 'tags-input-wrapper',
        tagClass: 'tag',
        max: null,
        duplicate: false
    }

    window.TagsInput = TagsInput;

})();

var tagInput1 = new TagsInput({
    selector: 'tag-input1',
    duplicate: false,
    max: 10
});
tagInput1.addData([])
</script>
@endsection