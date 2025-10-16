var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
// public/themes/modern/js/artikel.js
$(document).ready(function() {

    // Ambil base_url dari global scope atau definisikan jika belum ada
    var base_url = $('base').attr('href'); 
    
    // Inisialisasi TinyMCE
    tinymce.init({
        selector: '.tinymce',
        plugins: 'image paste advlist lists link wordcount codesample',
        toolbar: 'styleselect | bold italic underline strikethrough | forecolor | numlist bullist | image codesample',
        branding: false,
        image_title: true,
        image_description: true,
        statusbar: false,
        image_caption: true,
        
        // Konfigurasi untuk unggahan otomatis (Drag & Drop)
        automatic_uploads: true,
        images_upload_url: base_url + 'artikel/upload-image', // Fallback
        
        // FORCE HANDLER: Ini memastikan Drag & Drop menggunakan logika ini
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', base_url + 'artikel/upload-image');

            xhr.onload = function() {
                var json;

                if (xhr.status != 200) {
                    // Tampilkan pesan error jika upload gagal
                    failure('HTTP Error: ' + xhr.status + ' - Gagal mengunggah file.');
                    return;
                }

                try {
                    json = JSON.parse(xhr.responseText);
                } catch (e) {
                    failure('Invalid JSON response: ' + xhr.responseText);
                    return;
                }

                // Memastikan controller mengembalikan properti 'location' (seperti yang kita buat)
                if (!json || typeof json.location != 'string') {
                    failure('Upload failed. Server did not return a valid location URL.');
                    return;
                }

                // Sukses, berikan URL gambar ke TinyMCE
                success(json.location);
            };
            
            // Handle error koneksi
            xhr.onerror = function() {
                 failure('Connection error. Could not reach the server.');
            };

            formData = new FormData();
            // Pastikan field yang dikirim adalah 'file' sesuai dengan Controller PHP Anda
            formData.append('file', blobInfo.blob(), blobInfo.filename()); 

            xhr.send(formData);
        },
        
        // Konfigurasi file picker kustom untuk membuka dialog file lokal (tombol "Image")
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
        
        codesample_content_css: base_url + "public/vendors/prism/themes/prism-dark.css",
    }).then(function(editors) {
        // Logika dark mode Anda
        if ($('html').attr('data-bs-theme') == 'dark') {
            var iframe = editors[0].iframeElement; // Ambil elemen iframe editor
            if (iframe) {
                var $iframe_content = $(iframe).contents();
                $iframe_content.find('#theme-style').remove();
                $iframe_content.find("head").append('<style id="theme-style">body{color: #adb5bd}</style>');   
                $iframe_content.find("head").append('<style id="theme-style">::-webkit-scrollbar { width: 15px; height: 3px;}::-webkit-scrollbar-button {  background-color: #141925;height: 0; }::-webkit-scrollbar-track {  background-color: #646464;}::-webkit-scrollbar-track-piece { background-color: #202632;}::-webkit-scrollbar-thumb { height: 35px; background-color: #181c26;border-radius: 0;}::-webkit-scrollbar-corner { background-color: #646464;}}::-webkit-resizer { background-color: #666;}</style>');   
            }
        }
    });

});