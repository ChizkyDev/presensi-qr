@extends('layouts.siswa')

@section('title', 'Scan QR Code - Student Dashboard')

@section('content')
<div class="container-fluid px-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Scan QR Code</h3>
            <small class="text-muted">Attendance Check-In</small>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="bi bi-qr-code-scan fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark" style="font-size: 16px;">Scan Attendance QR</h5>
                            <small class="text-muted" style="font-size: 12px;">Expired on 15 minutes</small>
                        </div>
                    </div>
                    <span id="camera-label" class="text-muted fw-medium" style="font-size: 11px; letter-spacing: 0.5px;">HD · READY</span>
                </div>

                <div class="mb-3">
                    <span id="scanner-status-badge" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1 rounded-pill fw-semibold" style="font-size: 11px;">
                        ● Klik tombol biru untuk memulai kamera / Scan
                    </span>
                </div>

                <div class="position-relative rounded-4 overflow-hidden bg-light border d-flex align-items-center justify-content-center" style="min-height: 440px;">
                    
                    {{-- Area Reader (Placeholder, Live Video, & Success Checkmark) --}}
                    <div id="reader" style="width: 100%; height: 100%; border: none !important; display: flex; align-items: center; justify-content: center;" class="rounded-4 p-0 position-relative">
                        
                        {{-- Standby / Upload Placeholder --}}
                        <div id="upload-placeholder" class="text-center p-4">
                            <i class="bi bi-qr-code display-4 text-muted mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Scanner Standby</h6>
                            <p class="text-muted small mb-0">Klik tombol scan biru di bawah atau upload gambar QR dari Quick Actions.</p>
                        </div>

                        {{-- Element Video Live Camera --}}
                        <video id="webcam-video" class="w-100 h-100 object-fit-cover rounded-4" style="display: none;" playsinline></video>

                        {{-- Success State Centang Hijau --}}
                        <div id="success-checkmark" class="text-center p-4" style="display: none; z-index: 10;">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-3" style="width: 88px; height: 88px;">
                                <i class="bi bi-check-lg" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold text-success mb-1">QR Code Berhasil Terbaca!</h5>
                            <p id="decoded-result-text" class="text-muted small mb-0 text-break"></p>
                        </div>

                        {{-- Canvas Tersembunyi untuk Decoder jsQR --}}
                        <canvas id="hidden-canvas" style="display: none;"></canvas>
                    </div>

                    {{-- Overlay Siku-Siku --}}
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center pointer-events-none" style="z-index: 5;">
                        <div class="position-relative d-flex align-items-center justify-content-center" style="width: 360px; height: 360px;">
                            <div class="position-absolute top-0 start-0 border-top border-start border-primary border-4 rounded-top-left" style="width: 50px; height: 50px;"></div>
                            <div class="position-absolute top-0 end-0 border-top border-end border-primary border-4 rounded-top-right" style="width: 50px; height: 50px;"></div>
                            <div class="position-absolute bottom-0 start-0 border-bottom border-start border-primary border-4 rounded-bottom-left" style="width: 50px; height: 50px;"></div>
                            <div class="position-absolute bottom-0 end-0 border-bottom border-end border-primary border-4 rounded-bottom-right" style="width: 50px; height: 50px;"></div>
                        </div>

                        <div class="mt-3">
                            <span class="badge bg-dark text-white rounded-pill px-4 py-2 shadow-sm fw-normal" style="font-size: 11px; letter-spacing: 0.3px;">
                                Align the QR code within the frame
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Kontrol Bawah --}}
                <div class="d-flex align-items-center justify-content-center gap-3 my-3">
                    <button type="button" class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center text-muted shadow-sm" style="width: 45px; height: 45px;" title="Toggle Flash">
                        <i class="bi bi-lightning-charge"></i>
                    </button>
                    
                    {{-- Tombol Scan Biru --}}
                    <button type="button" id="btn-camera-scan" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 55px; height: 55px; background-color: #2563eb;" title="Start Camera Scan">
                        <i class="bi bi-fullscreen fs-5 text-white"></i>
                    </button>
                    
                    <button type="button" class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center text-muted shadow-sm" style="width: 45px; height: 45px;" title="Refresh Page" onclick="location.reload();">
                        <i class="bi bi-arrow-repeat"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top text-muted" style="font-size: 11px; letter-spacing: 0.5px;">
                    <span><i class="bi bi-shield-lock me-1"></i> ENCRYPTED SCAN PROCESSING</span>
                    <div>
                        <span class="text-muted me-3">TEST</span>
                        <span class="text-muted me-3">INVALID</span>
                        <span class="text-muted">CODE</span>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-4 d-flex flex-column gap-4">
            
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <span class="text-uppercase text-muted fw-bold mb-2" style="font-size: 10px; letter-spacing: 0.5px;">Current Class</span>
                <h4 class="fw-bold text-dark mb-1">Mathematics</h4>
                <p class="text-muted small mb-3">XII-A • Lab 1</p>
                <div class="alert alert-success bg-success-subtle border-0 text-success rounded-3 py-2 px-3 small fw-semibold mb-0">
                    <i class="bi bi-clock-history me-1"></i> Session Open – 22 min Left
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <span class="text-uppercase text-muted fw-bold mb-3" style="font-size: 10px; letter-spacing: 0.5px;">Quick Actions</span>
                
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-light border text-start py-2 px-3 rounded-3 d-flex justify-content-between align-items-center fw-semibold text-dark">
                        <span><i class="bi bi-keyboard text-primary me-2"></i> Enter session code</span>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </button>
                    
                    <label class="btn btn-light border text-start py-2 px-3 rounded-3 d-flex justify-content-between align-items-center fw-semibold text-dark mb-0" style="cursor: pointer;">
                        <span><i class="bi bi-upload text-primary me-2"></i> Upload QR image</span>
                        <i class="bi bi-chevron-right small text-muted"></i>
                        <input type="file" id="qr-file-input" accept="image/*" class="d-none">
                    </label>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-warning bg-opacity-10 border-warning">
                <div class="d-flex gap-2 align-items-start">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                    <div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Informasi Scanner</h6>
                        <p class="text-muted mb-0" style="font-size: 12px;">Klik tombol biru di bawah untuk menyalakan kamera live, atau gunakan menu upload file QR di atas.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const fileInput = document.getElementById('qr-file-input');
        const video = document.getElementById('webcam-video');
        const canvas = document.getElementById('hidden-canvas');
        const ctx = canvas.getContext('2d');
        const placeholder = document.getElementById('upload-placeholder');
        const successCheckmark = document.getElementById('success-checkmark');
        const decodedResultText = document.getElementById('decoded-result-text');
        const btnCameraScan = document.getElementById('btn-camera-scan');
        const statusBadge = document.getElementById('scanner-status-badge');
        const cameraLabel = document.getElementById('camera-label');

        // URL tujuan penyimpanan absensi (route siswa.scan.store) + token CSRF
        const scanUrl = @json(route('siswa.scan.store'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || @json(csrf_token());

        let videoStream = null;
        let isScanning = false;
        let isSubmitting = false;

        function setBadge(type, text) {
            const styles = {
                success: "bg-success-subtle text-success border border-success-subtle",
                danger:  "bg-danger-subtle text-danger border border-danger-subtle",
                neutral: "bg-secondary-subtle text-secondary border border-secondary-subtle",
            };
            statusBadge.className = "badge " + styles[type] + " px-3 py-1 rounded-pill fw-semibold";
            statusBadge.textContent = text;
        }

        function stopCamera() {
            isScanning = false;
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
        }

        // Tampil setelah server mengonfirmasi absensi tersimpan
        function showSuccessState(message) {
            stopCamera();
            placeholder.style.display = 'none';
            video.style.display = 'none';
            successCheckmark.style.display = 'block';
            decodedResultText.textContent = message;
            setBadge('success', "● " + message);
        }

        // Tampil kalau server menolak (sesi berakhir, kelas salah, sudah absen, dll.)
        function showErrorState(message) {
            stopCamera();
            successCheckmark.style.display = 'none';
            video.style.display = 'none';
            placeholder.style.display = 'block';
            placeholder.innerHTML = `
                <i class="bi bi-x-circle display-4 text-danger mb-2"></i>
                <h6 class="fw-bold text-danger mb-1">Absensi Gagal</h6>
                <p class="text-muted small mb-0"></p>
            `;
            placeholder.querySelector('p').textContent = message;
            setBadge('danger', "● " + message);
        }

        // Kirim isi QR ke server
        async function submitAttendance(payload) {
            if (isSubmitting) return;
            isSubmitting = true;
            stopCamera();
            setBadge('neutral', "● Mengirim absensi...");

            try {
                const res = await fetch(scanUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ payload: payload }),
                });
                const data = await res.json().catch(() => ({}));

                if (res.ok && data.success) {
                    showSuccessState(data.message);
                } else {
                    showErrorState(data.message || "Gagal mengirim absensi. Coba lagi.");
                }
            } catch (e) {
                showErrorState("Tidak dapat terhubung ke server.");
            } finally {
                isSubmitting = false;
            }
        }

        function scanCameraFrame() {
            if (!isScanning) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (code) {
                    submitAttendance(code.data);
                    return;
                }
            }
            requestAnimationFrame(scanCameraFrame);
        }

        // Trigger Live Camera hanya saat tombol biru diklik
        btnCameraScan.addEventListener('click', function () {
            stopCamera();
            successCheckmark.style.display = 'none';

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showNoCameraError();
                return;
            }

            navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                .then(function (stream) {
                    videoStream = stream;
                    video.srcObject = stream;
                    video.setAttribute("playsinline", true);
                    video.play();
                    
                    placeholder.style.display = 'none';
                    video.style.display = 'block';
                    
                    setBadge('success', "● Kamera Aktif - Scanning...");
                    cameraLabel.textContent = "HD · LIVE CAMERA";

                    isScanning = true;
                    requestAnimationFrame(scanCameraFrame);
                })
                .catch(function (err) {
                    showNoCameraError();
                });
        });

        function showNoCameraError() {
            setBadge('danger', "● Tidak ada kamera di perangkat Anda");
            cameraLabel.textContent = "HD · NO CAMERA";

            placeholder.style.display = 'block';
            video.style.display = 'none';
            placeholder.innerHTML = `
                <i class="bi bi-camera-video-off display-4 text-danger mb-2"></i>
                <h6 class="fw-bold text-danger mb-1">Tidak Ada Kamera di Perangkat Anda</h6>
                <p class="text-muted small mb-0">Perangkat tidak mendeteksi kamera. Silakan gunakan <strong>Upload QR image</strong>.</p>
            `;
            alert("Tidak ada kamera di perangkat Anda! Silakan gunakan fitur 'Upload QR image' di Quick Actions.");
        }

        // Handle Upload File Gambar QR
        fileInput.addEventListener('change', e => {
            if (e.target.files.length == 0) return;
            const imageFile = e.target.files[0];
            stopCamera();
            
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.onload = function() {
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0, img.width, img.height);
                    
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });
                    
                    if (code) {
                        submitAttendance(code.data);
                    } else {
                        alert("Gagal membaca QR dari gambar. Pastikan file gambar memiliki QR Code yang jelas.");
                    }
                    fileInput.value = "";
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(imageFile);
        });
    });
</script>
   
<style>
    #reader {
        background-color: #ffffff;
        min-height: 440px;
    }
    .pointer-events-none {
        pointer-events: none !important;
    }
</style>
@endpush