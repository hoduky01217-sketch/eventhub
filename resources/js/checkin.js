
import QrScanner from 'qr-scanner';

document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('qr-video');
    const form = document.getElementById('checkin-form');

    if (!video || !form) return;

    const resultBox = document.getElementById('scan-result');
    const tokenInput = document.getElementById('token-input');
    const startButton = document.getElementById('start-camera');
    const stopButton = document.getElementById('stop-camera');

    let processing = false;

    // Hiển thị thông báo
    function showMessage(message, success = false) {
        resultBox.textContent = message;

        resultBox.className = success
            ? 'mt-6 p-4 rounded-lg bg-green-100 text-green-800'
            : 'mt-6 p-4 rounded-lg bg-red-100 text-red-700';
    }

    // Gửi QR token về Laravel
    async function submitCheckIn(token) {
        if (processing) return;

        processing = true;

        const cleanedToken = token.trim();

        if (!cleanedToken) {
            showMessage('Vui lòng nhập hoặc quét mã QR.');
            processing = false;
            return;
        }

        try {
            const csrf = document.querySelector(
                'meta[name="csrf-token"]'
            )?.content;

            if (!csrf) {
                throw new Error('Thiếu CSRF token. Kiểm tra layout Laravel.');
            }

            const response = await fetch(form.dataset.url, {
                method: 'POST',
                credentials: 'same-origin',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },

                body: JSON.stringify({
                    token: cleanedToken,
                }),
            });

            const result = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(
                    result.errors?.token?.[0] ||
                    result.message ||
                    'Không thể xác nhận check-in.'
                );
            }

            const message =
                'CHECK-IN THÀNH CÔNG! ' +
                'Người tham gia: ' + result.participant +
                ' | Sự kiện: ' + result.event +
                ' | Thời gian: ' + result.checked_in_at;

            showMessage(message, true);
            tokenInput.value = '';

        } catch (error) {
            showMessage(error.message || 'Đã xảy ra lỗi.');
        } finally {
            processing = false;
        }
    }

    // Khởi tạo thư viện quét QR
    const scanner = new QrScanner(
        video,

        (result) => {
            if (processing) return;

            // Dừng camera để tránh quét cùng mã nhiều lần
            scanner.stop();

            submitCheckIn(result.data);
        },

        {
            preferredCamera: 'environment',
            maxScansPerSecond: 5,
            highlightScanRegion: true,
            returnDetailedScanResult: true,
        }
    );

    // Bật camera
    startButton.addEventListener('click', async () => {
        try {
            if (!await QrScanner.hasCamera()) {
                showMessage('Không tìm thấy camera trên thiết bị.');
                return;
            }

            await scanner.start();
            resultBox.textContent = 'Camera đã bật. Hãy đưa QR vào khung hình.';

            resultBox.className =
                'mt-6 p-4 rounded-lg bg-blue-100 text-blue-800';

        } catch (error) {
            showMessage(
                'Không thể bật camera. Kiểm tra quyền truy cập hoặc HTTPS.'
            );
        }
    });

    // Tắt camera
    stopButton.addEventListener('click', () => {
        scanner.stop();
        resultBox.textContent = 'Camera đã tắt.';
        resultBox.className =
            'mt-6 p-4 rounded-lg bg-gray-100 text-gray-700';
    });

    // Nhập mã thủ công
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        scanner.stop();

        await submitCheckIn(tokenInput.value);
    });

    // Giải phóng camera khi rời trang
    window.addEventListener('pagehide', () => {
        scanner.destroy();
    });
});
