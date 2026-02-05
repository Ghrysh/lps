import cv2
import numpy as np
from flask import Flask, request, jsonify
from flask_cors import CORS
import os

app = Flask(__name__)
CORS(app)

GALLERY_PATH = "/shared/assets/gallery"

def get_points(input_bytes):
    nparr = np.frombuffer(input_bytes, np.uint8)
    img_query = cv2.imdecode(nparr, cv2.IMREAD_GRAYSCALE)
    if img_query is None: return None

    orb = cv2.ORB_create(nfeatures=1000)
    kp1, des1 = orb.detectAndCompute(img_query, None)
    if des1 is None: return None

    for filename in os.listdir(GALLERY_PATH):
        if not filename.lower().endswith(('.png', '.jpg', '.jpeg')): continue
        
        img_train = cv2.imread(os.path.join(GALLERY_PATH, filename), cv2.IMREAD_GRAYSCALE)
        if img_train is None: continue

        kp2, des2 = orb.detectAndCompute(img_train, None)
        if des2 is None: continue

        bf = cv2.BFMatcher(cv2.NORM_HAMMING)
        # knnMatch mengembalikan list berisi list (k=2)
        matches = bf.knnMatch(des1, des2, k=2)
        
        # PERBAIKAN DI SINI: ganti 'm' menjadi 'm_n[0]'
        good = []
        for m_n in matches:
            if len(m_n) == 2:
                m, n = m_n
                if m.distance < 0.75 * n.distance:
                    good.append(m)

        if len(good) > 15:
            src_pts = np.float32([kp2[m.trainIdx].pt for m in good]).reshape(-1, 1, 2)
            dst_pts = np.float32([kp1[m.queryIdx].pt for m in good]).reshape(-1, 1, 2)
            
            M, _ = cv2.findHomography(src_pts, dst_pts, cv2.RANSAC, 5.0)
            if M is not None:
                h, w = img_train.shape
                # Hitung pusat objek untuk posisi AR yang lebih stabil
                pts = np.float32([[0,0],[0,h],[w,h],[w,0]]).reshape(-1,1,2)
                dst = cv2.perspectiveTransform(pts, M)
                
                # Hitung center x dan y
                moments = cv2.moments(dst)
                if moments['m00'] != 0:
                    cx = int(moments['m10'] / moments['m00'])
                    cy = int(moments['m01'] / moments['m00'])
                    return {"filename": filename, "x": cx, "y": cy, "points": dst.reshape(4, 2).tolist()}
                    
    return None

@app.route('/recognize', methods=['POST'])
def recognize():
    # Pastikan key 'image' sesuai dengan yang dikirim FormData di JS
    if 'image' not in request.files:
        return jsonify({"status": "error", "message": "No image uploaded"}), 400
        
    res = get_points(request.files['image'].read())
    return jsonify({"status": "success", **res}) if res else jsonify({"status": "not_found"})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)