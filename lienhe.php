<?php
// lienhe.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ - Sunny Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .contact-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 32px 24px 24px 24px;
        }
        h1 {
            text-align: center;
            color: #8BC34A;
            margin-bottom: 24px;
            font-size: 2rem;
        }
        .contact-info {
            margin-bottom: 24px;
        }
        .contact-info p {
            margin: 10px 0;
            color: #333;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .contact-info i {
            color: #8BC34A;
            min-width: 24px;
            text-align: center;
        }
        .footer-map {
            margin-top: 20px;
        }
        .footer-map iframe {
            width: 100%;
            min-height: 280px;
            max-width: 100%;
            border-radius: 8px;
            border: 0;
        }
        @media (max-width: 700px) {
            .contact-container {
                padding: 16px 4vw 16px 4vw;
            }
            .footer-map iframe {
                min-height: 180px;
                height: 180px;
            }
        }
        .modal-overlay {
  position: fixed;
  z-index: 9999;
  left: 0; top: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-content {
  background: #fff;
  border-radius: 12px;
  max-width: 95vw;
  width: 400px;
  padding: 24px 18px 18px 18px;
  box-shadow: 0 2px 16px rgba(0,0,0,0.18);
  position: relative;
  animation: modalShow 0.2s;
}
@keyframes modalShow {
  from { transform: translateY(-40px); opacity: 0;}
  to { transform: translateY(0); opacity: 1;}
}
.close-modal {
  position: absolute;
  top: 10px; right: 16px;
  font-size: 28px;
  color: #888;
  cursor: pointer;
}
.contact-info p {
  margin: 10px 0;
  color: #333;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  gap: 10px;
}
.contact-info i {
  color: #8BC34A;
  min-width: 24px;
  text-align: center;
}
.footer-map iframe {
  width: 100%;
  min-height: 180px;
  border-radius: 8px;
  border: 0;
}
    </style>
</head>
<body>
    <div class="contact-container">
        <h1>Liên hệ Sunny Store</h1>
        <div class="contact-info">
            <p><i class="fas fa-map-marker-alt"></i>  164 Mỹ Tân , Đầm Dơi , Cà Mau</p>
            <p><i class="fas fa-phone"></i>  0914090763</p>
            <p><i class="fas fa-envelope"></i>  phuongthuy091203@gmail.com</p>
        </div>
        <div class="footer-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126076.99581562124!2d105.2371718!3d9.0723171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a1450040a46c09%3A0xbb0c457bde4f5702!2sCh%C3%AD%20Khanh!5e0!3m2!1svi!2s!4v1752059828388!5m2!1svi!2s" width="100%" height="280" style="border:0; border-radius:8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
    <div id="contactModal" class="modal-overlay" style="display:none;">
  <div class="modal-content">
    <span class="close-modal" id="closeContactModal">&times;</span>
    <h2>Liên hệ Sunny Store</h2>
    <div class="contact-info">
      <p><i class="fas fa-map-marker-alt"></i>164 Mỹ Tân, Đầm Dơi, Cà Mau</p>
      <p><i class="fas fa-phone"></i>0914090763</p>
      <p><i class="fas fa-envelope"></i>phuongthuy091203@gmail.com</p>
    </div>
    <div class="footer-map" style="margin-top: 15px;">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126076.99581562124!2d105.2371718!3d9.0723171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a1450040a46c09%3A0xbb0c457bde4f5702!2sCh%C3%AD%20Khanh!5e0!3m2!1svi!2s!4v1752059828388!5m2!1svi!2s" width="100%" height="250" style="border:0; border-radius:8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</div>
<script>
document.getElementById('openContactModal').onclick = function(e) {
  e.preventDefault();
  document.getElementById('contactModal').style.display = 'flex';
};
document.getElementById('closeContactModal').onclick = function() {
  document.getElementById('contactModal').style.display = 'none';
};
// Đóng modal khi click ra ngoài
document.getElementById('contactModal').onclick = function(e) {
  if (e.target === this) this.style.display = 'none';
};
</script>
</body>
</html> 