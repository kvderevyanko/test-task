
let imageUploadBlock = $("#imageUploadBlock");
let cancelUploadImage = $("#cancelUploadImage");
let selectImageBtn = $("#selectImageBtn");
let progressImageUpload = $("#progressImageUpload");
let imageUploadBtn = $("#imageUploadBtn");
let imagesListBlock = $("#imagesListBlock");
let productGallery = $(".productGallery");

selectImageBtn.change(function(){
    let selectImageBtn = document.getElementById('selectImageBtn');
    $.each(selectImageBtn.files, function (i, file) {
        if(file){
            if(file.size > 1000000) {
                alert("Размер превышает 1 Мб");
            } else {
                uploadImage(file);
            }
        }
    });
    document.getElementById("selectImageBtn").value = "";
});

imagesListBlock.on('click', '.removeImage', function (){
    let id = $(this).parents(".img-product-prev").attr('data-id');
    if(id && confirm("Удалить изображение?")) {
        $.post(urlImageRemove, {
            id:id,
            _csrf:  $("[name='csrf-token']").attr('content')
        }, function (data) {
            updateImagesList();
        })
    }
});


imagesListBlock.on('change', '.defaultImage', function (){
    if($(this).prop("checked")) {
        let imageId = $(this).parents(".img-product-prev").attr('data-id');
        if(imageId) {
            $.post(urlImageDefault, {
                imageId:imageId,
                _csrf:  $("[name='csrf-token']").attr('content')
            }, function (data) {
                updateImagesList();
            })
        }
    }

});

function uploadImage(file){
    showUploadProgress();

    let prog = function (event) {
        let percent = parseInt(event.loaded / event.total * 100);
        progressImageUpload.width(percent+'%');
    };

    let formData = new FormData();
    formData.append("file", file);
    formData.append("_csrf", $("[name='csrf-token']").attr('content'));

    let xhr = new XMLHttpRequest();
    xhr.upload.addEventListener('progress', prog, false);
    xhr.open('POST', urlImageUpload, true);
    xhr.setRequestHeader('X-FILE-NAME', 'file');
    xhr.send(formData);

    xhr.onreadystatechange = function() { // (3)
        if (xhr.readyState !== 4) return;
        if (xhr.status !== 200) {
            cl(xhr.status + ': ' + xhr.statusText);
        } else {
            if(xhr.responseText !== 0){
                hideUploadProgress();
            }

        }
    };
    cancelUploadImage.on('click', function(){
        xhr.abort();
        hideUploadProgress();
    });
}

function updateImagesList(){
    $.get(urlImagesList, function(data) {
        imagesListBlock.html(data);
    });
}

function showUploadProgress(){
    imageUploadBlock.show();
    imageUploadBtn.hide();
}

function hideUploadProgress(){
    imageUploadBtn.show();
    imageUploadBlock.hide();
    updateImagesList();
}

if(imagesListBlock.length) {
    updateImagesList();
}


//ли есть галереи, то активируем photoswipe
if(productGallery.length) {
    let pswpElement = document.querySelectorAll('.pswp')[0];
    let gallery;

    $(".prevImage").on('click', function (e) {
        let imagesBlock = $(this).parents('.productGallery');
        let images = [];

        $.each(imagesBlock.find('img'), function () {
            images.push({
                src: $(this).data('big'),
                w: $(this).attr('w'),
                h: $(this).attr('h')
            });
        })
        let options = {
            index: $(this).index()
        };
        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, images, options);
        gallery.init();
    })
}


function cl() {
    for (let i = 0; i < arguments.length; i++) {
        console.log( arguments[i] );
    }
}