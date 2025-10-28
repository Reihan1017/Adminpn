// public/themes/modern/js/halamanstatis.js

// Bagian ini untuk mengaktifkan tooltip Bootstrap jika ada
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

$(document).ready(function() {

    // Ambil base_url dari tag <base> di HTML Anda
    var base_url = $('base').attr('href');

    // Inisialisasi TinyMCE
    tinymce.init({
        // Targetkan semua textarea dengan class .tinymce di halaman Anda
        selector: '.tinymce', 
        
        // Menambahkan lebih banyak plugin untuk fungsionalitas yang lebih kaya
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview | image media link anchor codesample | ltr rtl',
        
        height: 650, // Tinggi editor
        branding: false,
        image_title: true,
        image_description: true,
        statusbar: false,
        image_caption: true,
        automatic_uploads: true,
        
        // URL untuk mengunggah gambar diubah agar sesuai dengan controller KelolaHalaman
        images_upload_url: base_url + 'kelola-halaman/upload-image',
        
        // Handler untuk menangani proses upload (Drag & Drop atau tombol)
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            // Pastikan URL POST-nya benar
            xhr.open('POST', base_url + 'kelola-halaman/upload-image');

            // Tambahkan CSRF token header untuk keamanan, diambil dari tag meta
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);

            xhr.onload = function() {
                var json;

                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status + ' - Gagal mengunggah file.');
                    return;
                }

                try {
                    json = JSON.parse(xhr.responseText);
                } catch (e) {
                    failure('Invalid JSON response: ' + xhr.responseText);
                    return;
                }

                if (json.error) {
                    let error_message = 'Server error: ';
                    for (const key in json.error) {
                        error_message += json.error[key] + ' ';
                    }
                    failure(error_message);
                    return;
                }

                if (!json || typeof json.location != 'string') {
                    failure('Upload failed. Server did not return a valid location URL.');
                    return;
                }

                success(json.location);
            };
            
            xhr.onerror = function() {
                failure('Connection error. Could not reach the server.');
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename()); 

            xhr.send(formData);
        },
        
        // Konfigurasi untuk tombol "Image"
        file_picker_types: 'image',
        file_picker_callback: (cb, value, meta) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
        
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    const id = 'blobid' + (new Date()).getTime();
                    const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), { title: file.name });
                });
                reader.readAsDataURL(file);
            });
            input.click();
        },
        
        // Logika untuk menyesuaikan tema editor dengan tema admin (dark/light mode)
        codesample_content_css: base_url + "public/vendors/prism/themes/prism-dark.css",
    }).then(function(editors) {
        if ($('html').attr('data-bs-theme') == 'dark') {
            var iframe = editors[0].iframeElement;
            if (iframe) {
                var $iframe_content = $(iframe).contents();
                $iframe_content.find('#theme-style').remove();
                $iframe_content.find("head").append('<style id="theme-style">body{color: #adb5bd}</style>');   
                $iframe_content.find("head").append('<style id="theme-style">::-webkit-scrollbar { width: 15px; height: 3px;}::-webkit-scrollbar-button {  background-color: #141925;height: 0; }::-webkit-scrollbar-track {  background-color: #646464;}::-webkit-scrollbar-track-piece { background-color: #202632;}::-webkit-scrollbar-thumb { height: 35px; background-color: #181c26;border-radius: 0;}::-webkit-scrollbar-corner { background-color: #646464;}}::-webkit-resizer { background-color: #666;}</style>');   
            }
        }
    });
});