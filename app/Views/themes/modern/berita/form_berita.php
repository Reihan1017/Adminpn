<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <strong>Gagal menyimpan! Periksa input Anda:</strong>
        <ul><?php foreach (session()->getFlashdata('errors') as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-light">
    <div class="card-header bg-white py-3">
           <h5 class="card-title mb-0 text-primary"><i class="fas fa-edit mr-2"></i><?= esc($current_module['judul_module'] ?? 'Form Berita') ?></h5>
    </div>
    <div class="card-body">
        <form id="formBerita" action="<?= site_url('pengumuman/simpanBerita') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= esc($berita['id'] ?? '') ?>">

            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="judul" class="form-label font-weight-bold">Judul Berita <span class="text-danger">*</span></label>
                    <input type="text" id="judul" name="judul" class="form-control form-control-lg" value="<?= old('judul', $berita['judul'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                     <label for="tanggal_publish" class="form-label font-weight-bold">Tanggal Publish <span class="text-danger">*</span></label>
                     <?php 
                         $tanggalValue = date('Y-m-d\TH:i'); // Default current date and time
                         if (old('tanggal_publish')) { 
                             $tanggalValue = old('tanggal_publish'); 
                         } elseif (!empty($berita['tanggal_publish'])) { 
                             $tanggalValue = date('Y-m-d\TH:i', strtotime($berita['tanggal_publish'])); 
                         }
                     ?>
                     <input type="datetime-local" id="tanggal_publish" name="tanggal_publish" class="form-control form-control-lg" value="<?= esc($tanggalValue, 'attr') ?>" required>
                </div>
            </div>

            <div class="form-group mb-3">
            <label for="slug" class="form-label font-weight-bold">Slug (URL) <span class="text-danger">*</span></label>
            <input type="text" id="slug" name="slug" class="form-control" value="<?= old('slug', $berita['slug'] ?? '') ?>" required>
            <small class="form-text text-muted">Bagian URL unik. Gunakan huruf kecil, angka, dan tanda hubung (-). Contoh: pelantikan-sekretaris-pn-ciamis</small>
            </div>

            <div class="form-group mb-3" style="max-width: 250px;">
                <label for="status" class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                <select class="form-control" id="status" name="status" required>
                    <option value="published" <?= (old('status', $berita['status'] ?? 'published') == 'published') ? 'selected' : '' ?>>
                        Published (Tampilkan)
                    </option>
                    <option value="draft" <?= (old('status', $berita['status'] ?? '') == 'draft') ? 'selected' : '' ?>>
                        Draft (Simpan Konsep)
                    </option>
                </select>
                <small class="form-text text-muted">Pilih 'Published' untuk menampilkan di website.</small>
            </div>
            <div class="form-group mb-4">
                <label for="isi_berita" class="form-label font-weight-bold">Isi Berita <span class="text-danger">*</span></label>
                <textarea id="isi_berita" name="isi_berita" class="form-control" rows="15"><?= old('isi_berita', $berita['isi_berita'] ?? '') ?></textarea>
            </div>

            <div class="border rounded p-3 mb-4 bg-light">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="form-group mb-md-0">
                            <label for="gambar" class="font-weight-bold">Gambar Utama (Cover) <?= empty($berita['id']) ? '<span class="text-danger">*</span>' : '(Opsional)' ?></label>
                            <div id="drop-zone-utama" class="drop-zone">
                                <div class="custom-upload-container">
                                    <input type="file" class="hidden-file-input" id="gambar" name="gambar" accept="image/jpeg,image/png,image/jpg" <?= empty($berita['id']) ? 'required' : '' ?>>
                                    <label for="gambar" class="btn btn-secondary custom-choose-btn">Choose File</label>
                                    <span class="chosen-file-name" id="chosen-file-name-utama">No file chosen</span>
                                </div>
                                <div class="drop-zone-text">Pilih foto atau drop di sini</div>
                            </div>
                            <small class="form-text text-muted mt-1">Format: JPG, PNG. Maks 2MB. <?= empty($berita['id']) ? 'Wajib.' : 'Kosongkan jika tak diubah.' ?></small>
                        </div>
                    </div>
                    <div class="col-md-6 text-center" id="preview-utama">
                    <?php if (!empty($berita['gambar']) && file_exists(FCPATH . 'uploads/berita/' . $berita['gambar'])): ?>
                        <div class="existing-image-preview preview-item-wrapper mb-2">
                            <p class="mb-1"><small><strong>Gambar Utama Saat Ini:</strong></small></p>
                            <img src="<?= base_url('uploads/berita/' . esc($berita['gambar'])) ?>" alt="Gambar Berita" class="img-fluid img-thumbnail" style="max-height: 150px; object-fit: contain;" data-original-src="<?= base_url('uploads/berita/' . esc($berita['gambar'])) ?>">
                            </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="border rounded p-3 mb-4 bg-light">
                <div class="form-group mb-0">
                    <label for="gambar_tambahan" class="font-weight-bold">Gambar Tambahan (Galeri) (Opsional)</label>
                    <div id="drop-zone-tambahan" class="drop-zone">
                        <div class="custom-upload-container">
                            <input type="file" class="hidden-file-input" id="gambar_tambahan" name="gambar_tambahan[]" multiple accept="image/jpeg,image/png,image/jpg">
                            <label for="gambar_tambahan" class="btn btn-secondary custom-choose-btn">Choose Files</label>
                            <span class="chosen-file-name" id="chosen-file-name-tambahan">No file chosen</span>
                        </div>
                        <div class="drop-zone-text">Pilih foto atau drop di sini</div>
                    </div>
                    <small class="form-text text-muted mt-1">Format: JPG, PNG. Maks 2MB per file.</small>

                    <div id="preview-tambahan" class="mt-3">
                    <?php if (!empty($gambar_tambahan)): ?>
                        <div class="preview-current-files mb-2 row">
                            <p class="col-12"><strong>Gambar Tambahan Saat Ini:</strong></p>
                            <?php foreach($gambar_tambahan as $img): ?>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-2 text-center gallery-item-wrapper preview-item-wrapper" data-image-id="<?= $img['id'] ?>">
                                    <img src="<?= base_url('uploads/berita/' . esc($img['nama_file'])) ?>" class="img-thumbnail" style="height: 100px; width: 100%; object-fit: contain;">
                                    </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="text-right">
                <a href="<?= site_url('pengumuman/beritaTerkini') ?>" class="btn btn-outline-secondary rounded-pill px-4 mr-2">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-save mr-1"></i> Simpan Berita
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* CSS dari sebelumnya tetap dipertahankan */
.drop-zone { 
    border: 2px dashed #adb5bd; border-radius: .25rem; padding: 25px; text-align: center; 
    transition: background-color 0.2s ease, border-color 0.2s ease; cursor: pointer; 
    background-color: #f8f9fa; 
    position: relative; /* Penting untuk penempatan elemen di dalamnya */
    overflow: hidden; /* Pastikan konten tidak keluar */
}
.drop-zone.dragover { background-color: #e2e6ea; border-color: #007bff; }

/* Styling baru untuk input file kustom */
.custom-upload-container {
    display: flex;
    align-items: center;
    justify-content: center; /* Pusatkan di tengah drop zone */
    margin-bottom: 10px; /* Jarak antara input dan teks drop zone */
    flex-wrap: wrap; /* Untuk tampilan responsif jika nama file panjang */
}

.custom-upload-container .hidden-file-input {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}

.custom-choose-btn {
    background-color: #e0e0e0; /* Warna abu-abu */
    color: #333;
    border: 1px solid #ccc;
    padding: .375rem .75rem; /* Ukuran Bootstrap default */
    border-radius: .25rem;
    cursor: pointer;
    font-size: 1rem;
    line-height: 1.5;
    transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    margin-right: 10px; /* Jarak antara tombol dan nama file */
}
.custom-choose-btn:hover {
    background-color: #d0d0d0;
    border-color: #bbb;
    text-decoration: none;
}
.btn.custom-choose-btn:focus, .btn.custom-choose-btn.focus {
    box-shadow: 0 0 0 .2rem rgba(130, 138, 145, .5); /* Shadow fokus */
}

.chosen-file-name {
    flex-grow: 1; /* Agar mengambil sisa ruang */
    text-align: left;
    white-space: nowrap; /* Agar nama file tidak pecah baris */
    overflow: hidden;
    text-overflow: ellipsis; /* Tampilkan ... jika terlalu panjang */
    color: #555;
    font-size: 0.95rem;
}

.drop-zone-text {
    font-size: 0.9rem;
    color: #888;
    margin-top: 10px; /* Jarak dari area tombol/nama file */
}

/* Tambahan: Styling untuk preview gambar lama agar bisa disembunyikan */
.existing-image-preview {
    display: block; /* Default visible */
}

#preview-utama .img-thumbnail {
    max-height: 150px;
    width: auto; /* Biarkan lebarnya menyesuaikan */
    object-fit: contain; /* Ubah ini */
    display: block;
    margin: 0 auto; /* Tengah secara horizontal jika perlu */
}

/* Ubah style untuk gambar preview galeri */
#preview-tambahan .img-thumbnail {
    height: 100px; /* Tingginya bisa disesuaikan */
    width: 100%;
    object-fit: contain; /* Ubah ini */
}

/* Style untuk tombol hapus (X) */
.remove-preview {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: rgba(211, 47, 47, 0.8); /* Merah semi-transparan */
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    line-height: 18px; /* Sesuaikan agar X di tengah */
    text-align: center;
    cursor: pointer;
    padding: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    display: none; /* Sembunyikan default, tampilkan saat hover */
}

/* Wrapper untuk preview item agar bisa diposisikan */
.preview-item-wrapper {
    position: relative;
    display: inline-block; /* Atau sesuaikan dengan layout grid Anda */
    margin-bottom: 10px;
}
.preview-item-wrapper:hover .remove-preview {
    display: block; /* Tampilkan saat hover */
}
</style>

<script src="https://cdn.tiny.cloud/1/9dbv1pgxxzp507v0r0oxg8sresmaztgtf10ru9c8zwmqfhq7/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#isi_berita', height: 500,
        plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount image',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image media | help',
        images_upload_url: '<?= site_url('api/uploadImage') ?>', // PASTIKAN URL INI BENAR
        automatic_uploads: true, file_picker_types: 'image', paste_data_images: true,
        // HAPUS file_picker_callback JIKA ADA
    });

    document.getElementById('formBerita').addEventListener('submit', function(e) {
        tinymce.triggerSave();
        // Saat submit, kita perlu memastikan input file berisi file yang benar dari fileStore
        syncFileInputs(); 
    });

    // --- JavaScript BARU untuk Custom File Input, Drag & Drop, Preview, dan Multiple Files ---

    // Objek untuk menyimpan file yang dipilih (karena FileList read-only)
    const fileStore = {
        gambar: null, // Hanya satu file untuk gambar utama
        gambar_tambahan: [] // Array untuk multiple file galeri
    };

    // Fungsi untuk update label input file kustom
    function updateCustomFileInputLabel(inputId, files) {
        const inputElement = document.getElementById(inputId);
        const container = inputElement.closest('.custom-upload-container');
        const chosenFileNameSpan = container.querySelector('.chosen-file-name');
        
        if (files && files.length > 0) {
            let fileNames = files.map(f => f.name); // Ambil nama dari array fileStore
            chosenFileNameSpan.textContent = fileNames.join(', ');
        } else {
            chosenFileNameSpan.textContent = 'No file chosen';
        }
    }

    // Fungsi untuk menampilkan/memperbarui SEMUA preview gambar
    function renderPreviews(inputId) {
        const isMultiple = (inputId === 'gambar_tambahan');
        const previewContainerId = isMultiple ? 'preview-tambahan' : 'preview-utama';
        const previewContainer = document.getElementById(previewContainerId);
        if (!previewContainer) return;

        // Kosongkan HANYA preview yang dibuat oleh JS sebelumnya
        previewContainer.querySelectorAll('.js-preview-item').forEach(el => el.remove());

        const filesToPreview = isMultiple ? fileStore.gambar_tambahan : (fileStore.gambar ? [fileStore.gambar] : []);

        // Sembunyikan/Tampilkan preview gambar LAMA dari DB
        const existingPreviewDiv = previewContainer.querySelector('.existing-image-preview, .preview-current-files');
        if (existingPreviewDiv) {
             existingPreviewDiv.style.display = filesToPreview.length > 0 ? 'none' : (isMultiple ? 'flex' : 'block');
        }

        if (filesToPreview.length > 0) {
            // Buat container untuk preview baru jika belum ada atau jika kosong
            let newPreviewWrapper = previewContainer.querySelector('.preview-new-files');
            if (!newPreviewWrapper) {
                 newPreviewWrapper = document.createElement('div');
                 newPreviewWrapper.classList.add('border', 'rounded', 'p-3', 'bg-light', 'mt-2', 'preview-new-files', 'row');
                 const title = document.createElement('p');
                 title.className = 'col-12';
                 title.innerHTML = '<strong>Pratinjau Gambar Baru:</strong>';
                 newPreviewWrapper.appendChild(title);
                 previewContainer.appendChild(newPreviewWrapper);
            }
             // Pastikan row ada di dalam wrapper
            let rowDiv = newPreviewWrapper.querySelector('.row') || newPreviewWrapper;
            if (!rowDiv.classList.contains('row')) { // Jika wrapper bukan row, buat row di dalamnya
                rowDiv = document.createElement('div');
                rowDiv.className = 'row';
                newPreviewWrapper.appendChild(rowDiv);
            }


            filesToPreview.forEach((file, index) => {
                if (!file || !file.type.startsWith('image/')) { return; } // Pastikan file valid

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItemWrapper = document.createElement('div');
                    previewItemWrapper.className = isMultiple ? 
                                           'col-6 col-sm-4 col-md-3 col-lg-2 mb-2 text-center js-preview-item preview-item-wrapper' : 
                                           'text-center js-preview-item preview-item-wrapper mb-2';
                    previewItemWrapper.dataset.fileIndex = index; // Index dalam fileStore
                    previewItemWrapper.dataset.inputId = inputId; // Tandai asal input

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Preview ' + file.name;
                    img.className = 'img-fluid img-thumbnail';
                    img.style.maxHeight = isMultiple ? '80px' : '150px';
                    img.style.width = isMultiple ? '100%' : 'auto';
                    img.style.objectFit = 'contain';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-preview';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.title = 'Hapus gambar ini';
                    removeBtn.addEventListener('click', function() {
                        const wrapper = this.closest('.preview-item-wrapper');
                        const idxToRemove = parseInt(wrapper.dataset.fileIndex);
                        const sourceInputId = wrapper.dataset.inputId;

                        if (sourceInputId === 'gambar') {
                            fileStore.gambar = null;
                        } else if (sourceInputId === 'gambar_tambahan') {
                            fileStore.gambar_tambahan.splice(idxToRemove, 1); // Hapus dari array store
                        }
                        
                        syncFileInputs(); // Sinkronkan input file asli
                        renderPreviews(sourceInputId); // Render ulang preview untuk input ini
                        updateCustomFileInputLabel(sourceInputId, sourceInputId === 'gambar' ? (fileStore.gambar ? [fileStore.gambar] : []) : fileStore.gambar_tambahan); // Update label
                    });

                    previewItemWrapper.appendChild(img);
                    previewItemWrapper.appendChild(removeBtn);
                    rowDiv.appendChild(previewItemWrapper); // Tambahkan ke row
                };
                reader.readAsDataURL(file);
            });
        }
    }
    
    // Fungsi untuk Sinkronisasi fileStore ke input file asli (PENTING sebelum submit)
    function syncFileInputs() {
        // Untuk gambar utama
        const inputUtama = document.getElementById('gambar');
        const dtUtama = new DataTransfer();
        if (fileStore.gambar) {
            dtUtama.items.add(fileStore.gambar);
        }
        inputUtama.files = dtUtama.files;

        // Untuk gambar tambahan
        const inputTambahan = document.getElementById('gambar_tambahan');
        const dtTambahan = new DataTransfer();
        fileStore.gambar_tambahan.forEach(file => {
             if(file) dtTambahan.items.add(file); // Pastikan file valid
        });
        inputTambahan.files = dtTambahan.files;
    }


    // Fungsi setup Drop Zone (diperbarui untuk fileStore)
    function setupDropZone(dropZoneId, inputId) {
        const dropZone = document.getElementById(dropZoneId);
        const fileInput = document.getElementById(inputId);
        const isMultiple = fileInput.multiple;

        if (!dropZone || !fileInput) return;

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });

        dropZone.addEventListener('drop', handleDrop, false);
        dropZone.addEventListener('click', function(e) {
            if (!e.target.classList.contains('custom-choose-btn')) {
                fileInput.click();
            }
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const droppedFiles = Array.from(dt.files); // Konversi ke array

            if (isMultiple) {
                // Gabungkan file lama (jika ada) dengan file baru yang di-drop
                fileStore[inputId] = fileStore[inputId].concat(droppedFiles);
            } else {
                // Ganti file lama dengan yang baru (hanya ambil yg pertama jika > 1)
                fileStore[inputId] = droppedFiles[0] || null; 
            }
            
            syncFileInputs(); // Update input file asli
            updateCustomFileInputLabel(inputId, isMultiple ? fileStore[inputId] : (fileStore[inputId] ? [fileStore[inputId]] : [])); // Update label
            renderPreviews(inputId); // Render ulang preview
        }
    }

    // Event Listener untuk perubahan pada input file asli
    document.addEventListener('DOMContentLoaded', function() {
        const inputGambarUtama = document.getElementById('gambar');
        const inputGambarTambahan = document.getElementById('gambar_tambahan');

        inputGambarUtama.addEventListener('change', function() {
            fileStore.gambar = this.files[0] || null; // Simpan file ke store
            updateCustomFileInputLabel('gambar', fileStore.gambar ? [fileStore.gambar] : []);
            renderPreviews('gambar');
        });

        inputGambarTambahan.addEventListener('change', function() {
            // Gabungkan file yang sudah ada di store dengan file baru yang dipilih
            const newFiles = Array.from(this.files);
            fileStore.gambar_tambahan = fileStore.gambar_tambahan.concat(newFiles); 
            
            // Perbarui input file asli dengan gabungan file (penting!)
            syncFileInputs(); 
            
            updateCustomFileInputLabel('gambar_tambahan', fileStore.gambar_tambahan);
            renderPreviews('gambar_tambahan');
        });

        // Inisialisasi awal
        setupDropZone('drop-zone-utama', 'gambar');
        setupDropZone('drop-zone-tambahan', 'gambar_tambahan');
        updateCustomFileInputLabel('gambar', fileStore.gambar ? [fileStore.gambar] : []);
        updateCustomFileInputLabel('gambar_tambahan', fileStore.gambar_tambahan);

        // --- (Kode SweetAlert untuk hapus gambar galeri LAMA dari DB via AJAX, jika ada) ---
    });

</script>