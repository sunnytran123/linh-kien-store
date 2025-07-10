    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Liên hệ</h3>
                <p><i class="fas fa-map-marker-alt"></i>164 Mỹ Tân</p>
                <p><i class="fas fa-phone"></i> 0914 090 763</p>
                <p><i class="fas fa-envelope"></i>phuongthuy091203@gmail.com</p>
            </div>
            
            <div class="footer-section">
                <h3>Liên kết nhanh</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-home"></i> Trang chủ</a></li>
                    <li><a href="#"><i class="fas fa-shopping-bag"></i> Sản phẩm</a></li>
                    <li><a href="#"><i class="fas fa-tags"></i> Khuyến mãi</a></li>
                    <li><a href="#"><i class="fas fa-shield-alt"></i> Chính sách bảo hành</a></li>
                </ul>
            </div>
    
            <div class="footer-section">
                <h3>Kết nối với chúng tôi</h3>
                <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
                <a href="#"><i class="fab fa-youtube"></i> YouTube</a>
                <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
        
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Phụ Kiện Giá Rẻ. Mọi quyền được bảo lưu.</p>
        </div>
    </footer>

    <style>
        footer {
            background-color: #333;
            color: white;
            padding: 40px 0 20px;
            margin-top: 50px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 0 20px;
        }

        .footer-section h3 {
            color: #8BC34A;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .footer-section p, 
        .footer-section a {
            color: #fff;
            text-decoration: none;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #8BC34A;
        }

        .footer-section i {
            color: #8BC34A;
            width: 20px;
            text-align: center;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            margin-top: 30px;
            border-top: 1px solid #444;
            color: #888;
        }

        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .footer-section {
                text-align: center;
            }

            .footer-section p, 
            .footer-section a {
                justify-content: center;
            }
        }

        .footer-map iframe {
            width: 100%;
            min-height: 320px;
            max-width: 100%;
            border-radius: 8px;
            border: 0;
        }

        @media (max-width: 700px) {
            .modal-content {
                width: 98vw;
                padding: 12px 2vw 12px 2vw;
            }
            .footer-map iframe {
                min-height: 180px;
                height: 180px;
            }
        }
    </style>
</body>
</html>
