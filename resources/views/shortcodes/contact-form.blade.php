<div class="contact-wrap w-100 p-md-5 p-4" id="contactContainer">
    <h3 class="mb-4">{{__('messages.contact_us')}}</h3>
    @if(session('success'))
        <div class="alert alert-success">
            <span class="icon"></span>
            @lang('messages.contact_sent')
        </div>
    @else
    <form method="POST" id="contactForm" name="contactForm" action="{{ route('contact.submit') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{old('name', '')}}" required>
                    @error('name')
                    <small style="color: red">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{old('email', '')}}" required>
                    @error('email')
                    <small style="color: red">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="{{old('subject', '')}}" required>
                    @error('subject')
                    <small style="color: red">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="7" placeholder="Enter content" required>{{old('content', '')}}</textarea>
                    @error('content')
                    <small style="color: red">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-rounded px-4 py-2">
                        Submit
                    </button>
                    <div class="submitting"></div>
                </div>
            </div>
        </div>
    </form>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(){
        var form     = document.getElementById('contactContainer');
        var hasError = @if($errors->any() || session('success')) true @else false @endif;

        if(hasError) {
            setTimeout(() => {
                form.scrollIntoView()
            }, 1000);
        }
    })
</script>
