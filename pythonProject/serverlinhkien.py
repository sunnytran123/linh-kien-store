from flask import Flask, request, jsonify
from openai import OpenAI
import mysql.connector
import json

app = Flask(__name__)

# Khởi tạo client OpenAI với API key của bạn
client = OpenAI(api_key="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")


# Kết nối MySQL
def create_connection():
    """Tạo và trả về kết nối tới cơ sở dữ liệu MySQL"""
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",  # Tên người dùng MySQL
            password="",  # Mật khẩu của người dùng
            database="shopdongho2"  # Tên cơ sở dữ liệu
        )
        return conn
    except mysql.connector.Error as e:
        print(f"Lỗi kết nối cơ sở dữ liệu: {e}")
        return None


# Hàm thực thi câu truy vấn MySQL
def execute_query(query):
    """Thực thi câu truy vấn MySQL và trả về kết quả"""
    try:
        conn = create_connection()
        if conn is None:
            return []  # Nếu không kết nối được, trả về danh sách rỗng
        cursor = conn.cursor(dictionary=True)
        cursor.execute(query)
        results = cursor.fetchall()
        cursor.close()
        conn.close()
        return results
    except mysql.connector.Error as e:
        print(f"Lỗi thực thi truy vấn MySQL: {e}")
        return []
    except Exception as e:
        print(f"Lỗi khác: {e}")
        return []


# Hàm xử lý câu hỏi tư vấn
def handle_query_product(query):
    """Xử lý câu hỏi tư vấn và tạo câu truy vấn MySQL cho sản phẩm, khuyến mãi và danh mục"""
    try:
        # Tạo câu truy vấn SQL theo câu hỏi của người dùng
        prompt = f"""Bạn là một chuyên viên tư vấn bán quần áo túi nam và nữ. Khách hàng hỏi: {query}

            Bạn dựa vào câu hỏi của khách hàng để tạo ra câu truy vấn MySQL tìm kiếm các thông tin theo yêu cầu của khách hàng. 
            #Chú ý: không cần trả lời câu hỏi của khách hàng mà chỉ cần tạo ra câu truy vấn mysql select tìm kiếm các thông tin theo yêu cầu của khách hàng.
            #Lưu ý: 
            - Câu truy vấn cần giới hạn tối đa 5 kết quả.
            - Đảm bảo không có ký tự đặc biệt trong câu truy vấn.
            # Trả về câu truy vấn MySQL, không cần giải thích gì thêm.
            Cơ sở dữ liệu `shopdongho2` bao gồm các bảng sau:

        - *sanpham*: Sản phẩm quần áo túi của nam và nữ, gồm `sanphamid` (ID, khóa chính), `tensanpham` (tên sản phẩm), `mota` (mô tả sản phẩm), `gia` (giá bán), `madanhmuc` (liên kết với danh mục), `makhuyenmai` (liên kết với khuyến mãi), `chatlieu` (chất liệu), `thuonghieu` (thương hiệu), `baohanh` (bảo hành).
        - *khuyenmai*: Khuyến mãi, gồm `khuyenmaiid` (ID, khóa chính), `tenkhuyenmai` (tên khuyến mãi), `giatri` (giá trị giảm giá), `ngaybatdau` (ngày bắt đầu), `ngayketthuc` (ngày kết thúc).
        - *danhmuc*: Danh mục sản phẩm, gồm `danhmucid` (ID, khóa chính), `tendanhmuc` (tên danh mục).
        - *mausac*: Màu sắc sản phẩm, gồm `mausacid` (ID, khóa chính), `tenmau` (tên màu), `mamau` (mã màu hex).
        - *size*: Kích cỡ sản phẩm, gồm `sizeid` (ID, khóa chính), `kichco` (kích cỡ sản phẩm).

            """

        # Sử dụng OpenAI API để tạo câu truy vấn SQL
        response = client.chat.completions.create(
            model="gpt-4o-mini-2024-07-18",
            messages=[{"role": "system", "content": "Bạn là chuyên gia truy vấn dữ liệu MySQL."},
                      {"role": "user", "content": prompt}],
            temperature=0.5,
            max_tokens=2000
        )

        query_sql = response.choices[0].message.content.strip().replace('```sql', '').replace('```','')  # Xóa các ký tự không cần thiết
        return  query_sql
        # In câu truy vấn SQL để kiểm tra
        print(f"Câu truy vấn SQL: {query_sql}")

    except Exception as e:
        print(f"Lỗi xử lý tư vấn: {e}")
        return "Xin lỗi, tôi gặp khó khăn khi xử lý yêu cầu. Tôi có thể giúp gì về quần áo nam nữ?"


# Định nghĩa API route để nhận câu hỏi từ người dùng
@app.route('/api/chat', methods=['POST'])
def chat():
    try:
        # Lấy dữ liệu từ client
        data = request.json
        message_text = data.get("message", "").strip()

        if not message_text:
            return jsonify({"status": "error", "message": "Vui lòng nhập tin nhắn"}), 400

        # Gọi hàm tư vấn để xử lý câu hỏi
        response = handle_query_product(message_text)
        print(f"Trả về câu hỏi từ hàm tư vấn: {response}")

        # Trả về kết quả
        return jsonify({"status": "success", "response": response}), 200

    except Exception as e:
        print(f"Lỗi xử lý: {e}")
        return jsonify({"status": "error", "message": "Đã xảy ra lỗi. Vui lòng thử lại."}), 500


if __name__ == "__main__":
    app.run(debug=True)
