from flask import Flask, request, jsonify
from flask_cors import CORS
import cv2
import numpy as np
import os

app = Flask(__name__)
# Mengaktifkan CORS agar Laravel/Frontend bisa mengakses API ini
CORS(app)

GALLERY_PATH = "/shared/assets/gallery"

def recognize_object(input_image_bytes):
    # 1. Decode gambar dari stream bytes
    nparr = np.frombuffer(input_image_bytes, np.uint8)
    img_query = cv2.imdecode(nparr, cv2.IMREAD_GRAYSCALE)
    if img_query is None: 
        return None

    # 2. Inisialisasi ORB Detector (Ditingkatkan untuk akurasi dunia nyata)
    detector = cv2.ORB_create(
        nfeatures=3000, 
        scaleFactor=1.2, 
        nlevels=8, 
        edgeThreshold=31, 
        patchSize=31
    )
    
    kp1, des1 = detector.detectAndCompute(img_query, None)
    if des1 is None: 
        return None

    best_match = None
    highest_quality_matches = 0

    # 3. Iterasi file di Gallery
    for filename in os.listdir(GALLERY_PATH):
        if not filename.lower().endswith(('.png', '.jpg', '.jpeg')): 
            continue
        
        img_train = cv2.imread(os.path.join(GALLERY_PATH, filename), cv2.IMREAD_GRAYSCALE)
        if img_train is None: 
            continue
        
        # Optimalisasi: Resize gambar gallery agar sebanding dengan resolusi kamera HP
        if img_train.shape[1] > 1000:
            scale_ratio = 1000 / img_train.shape[1]
            img_train = cv2.resize(img_train, None, fx=scale_ratio, fy=scale_ratio)

        kp2, des2 = detector.detectAndCompute(img_train, None)
        if des2 is None: 
            continue

        # 4. Matching menggunakan Brute Force Hamming
        bf = cv2.BFMatcher(cv2.NORM_HAMMING)
        matches = bf.knnMatch(des1, des2, k=2)

        # 5. Lowe's Ratio Test
        good_matches = []
        for m_n in matches:
            if len(m_n) == 2:
                m, n = m_n
                if m.distance < 0.75 * n.distance:
                    good_matches.append(m)

        # 6. Homography & RANSAC (Verifikasi Geometri)
        if len(good_matches) >= 10:
            src_pts = np.float32([kp1[m.queryIdx].pt for m in good_matches]).reshape(-1, 1, 2)
            dst_pts = np.float32([kp2[m.trainIdx].pt for m in good_matches]).reshape(-1, 1, 2)

            M, mask = cv2.findHomography(src_pts, dst_pts, cv2.RANSAC, 5.0)
            
            if M is not None:
                final_count = int(np.sum(mask)) # Jumlah titik yang sinkron secara posisi
                
                if final_count > highest_quality_matches:
                    highest_quality_matches = final_count
                    best_match = filename
                
                # --- LOGIKA BREAK ---
                # Jika ditemukan > 15 inliers, objek dianggap sangat valid.
                # Stop loop untuk mempercepat respon ke frontend.
                if final_count >= 15:
                    break

    # 7. Threshold Akhir (Min 7 inliers untuk dianggap sukses)
    return best_match if highest_quality_matches >= 7 else None


@app.route('/recognize', methods=['POST'])
def recognize():
    if 'image' not in request.files:
        return jsonify({"status": "error", "message": "No image uploaded"}), 400
        
    try:
        file = request.files['image'].read()
        filename = recognize_object(file)
        
        if filename:
            # Mengembalikan nama file agar Laravel bisa mencari detail di Database
            return jsonify({
                "status": "success", 
                "filename": filename
            })
        else:
            return jsonify({"status": "not_found"})
            
    except Exception as e:
        print(f"Server Error: {e}")
        return jsonify({"status": "error", "message": str(e)}), 500

if __name__ == '__main__':
    # Pastikan host 0.0.0.0 agar bisa diakses antar container/jaringan lokal
    app.run(host='0.0.0.0', port=5000, debug=True)