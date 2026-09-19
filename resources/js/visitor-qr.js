import QRCode from 'qrcode';

document.addEventListener('DOMContentLoaded', async () => {
    const qrCanvas = document.getElementById('visitor-qr-code');

    if (!qrCanvas) {
        return;
    }

    const url = qrCanvas.dataset.url?.trim();

    if (!url) {
        console.error('URL QR Code tidak ditemukan.');
        return;
    }

    const size = Number(
        qrCanvas.dataset.size || 220
    );

    try {
        await QRCode.toCanvas(
            qrCanvas,
            url,
            {
                width: size,
                margin: 2,
                errorCorrectionLevel: 'H',
                color: {
                    dark: '#111827',
                    light: '#FFFFFF',
                },
            }
        );
    } catch (error) {
        console.error(
            'QR Code gagal dibuat:',
            error
        );

        const errorMessage =
            document.getElementById(
                'visitor-qr-error'
            );

        if (errorMessage) {
            errorMessage.classList.remove(
                'hidden'
            );
        }
    }
});