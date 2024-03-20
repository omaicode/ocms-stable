@php
    $preview = $preview ?: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAAAAAAdwx7eAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAHdElNRQfmCgYFAAhrAl+bAAAC3klEQVRYw+3Y227iMBAA0P7/ZzgXEqFCKDStKG2gqNxVEtLQcknszJdsQlnEsnE8XndXXSnzAg/kyBrimbGv0r8WVxVd0RVd0acASGkc0/zza2mg0ajbdpx2dxRRJI6igS3vLI0cQrPulgyFY2jY9kyinYKYvS3GRtAQtc7gA96KELaYhnXzQs7s5lpsi+l9+zc5s9v7L6AHWmEMlGmI7GLaFqZbuGqPFNPEU131rsGjGzs1GgJD44QRgBo91rgxVqT7hCeTvhqdPvLpR7Vcp098+kmNhiGfHirmeqHzaH2hSL/bPNp+V/wbqcvbMi5VyzU/I8J8iGtI3ClcNukkoieFNKzsonptr4S9ANHAJgVlxJiIn0PQ7MW87I3mC/sSOmWz+i82qc8QMnLEibo1ctQJqXUj1EM4GljoOZah64bleCFuwkHPfJDGa3+x8Ndxih36JCZVOAT+999hCK7of0gDSL4iuFNBdo7ZfbwFy2Xw9rHDnmfERRXoxh/12td2Ld+NNfu63Rv5Gyrmy+lsD66Gt3UjP8ecilP21ajfDleifVlGAw0HjnmmnhU/opnOICw9jPFpiOdujXDHkEMNdOcxSNOQzG/0Eveo6zfzBCTp0DWE8AE33FCKpmMbBX924DHF04mHW/LPhXsJlqZ9HQ/nofcpjoaZISdns8MMUPS2IZGNY04aWwwNU1k4jylgVv0gvehs2Q+ohPT/ZNV9XK5b8rluoXKdHUPx++Uo2wHuDUnTqSlHm9MipZBmQ6k9ow8Zmk7pQMLWBxI1JLOfsVWEGM+c8xKvqLKJhSuq1oQ3a/O7jN9E2KTpc4GSBrbpmQKcmL2NfAPLbfZa2sOy7vVaNsWXDguwnzg8nOjOZF86LYjO6Pu5m9+mXrBEs9z5XvGMfrgDdj+HnGNkA46LuQ/G3KkC2wZj777TcpxW594bB1uGmfqw99eQMprEcUIZ+nL8W8zXFV3RFf3f0j8Awjk6YdjdQ5YAAAAldEVYdGRhdGU6Y3JlYXRlADIwMjItMTAtMDZUMDU6MDA6MDArMDA6MDAJFU7DAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDIyLTEwLTA2VDA1OjAwOjAwKzAwOjAweEj2fwAAAABJRU5ErkJggg==';
@endphp

<div class="card card-avatar mb-3">
    <div class="card-body">
        <div class="d-flex align-items-end">
            <div class="me-2">
                <div class="bg-light rounded" style="width: 68px; height: 68px; overflow: hidden;">
                    <img id="{{$id}}_preview" src="{{ $preview }}" style="height: 100%; width: 100%; object-fit: cover">
                </div>
            </div>        
            <div>
                <div class="mb-1 fw-bold">{{$label}}</div>
                <button type="button" class="btn btn-info btn-sm" id="{{$id}}_button">
                    <i class="fas fa-upload"></i>
                    @lang('messages.upload')
                </button>
            </div>
        </div>    
        <input type="file" class="d-none" name="{{$name}}" id="{{$id}}" {!! $attributes !!}>  
        @include('admin.form.help-block')
        @include('admin.form.error')          
    </div>
</div>