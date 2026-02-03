from flask import Flask, request, jsonify
from flask_cors import CORS
import cv2
import numpy as np
import mediapipe as mp
import os
import base64
import math

app = Flask(__name__)
CORS(app)

ASSETS_PATH = "/shared/assets/baju"
mp_pose = mp.solutions.pose

def rotate_image(image, angle):
    image_center = tuple(np.array(image.shape[1::-1]) / 2)
    rot_mat = cv2.getRotationMatrix2D(image_center, angle, 1.0)
    result = cv2.warpAffine(
        image,
        rot_mat,
        image.shape[1::-1],
        flags=cv2.INTER_LINEAR,
        borderMode=cv2.BORDER_CONSTANT,
        borderValue=(0, 0, 0, 0),
    )
    return result

def overlay_transparent(background, overlay, x, y):
    bg_h, bg_w, bg_channels = background.shape
    if bg_channels == 3:
        background = cv2.cvtColor(background, cv2.COLOR_BGR2BGRA)

    ol_h, ol_w, _ = overlay.shape

    if x >= bg_w or y >= bg_h:
        return background
    h, w = min(ol_h, bg_h - y), min(ol_w, bg_w - x)
    if h <= 0 or w <= 0:
        return background

    overlay_crop = overlay[:h, :w]
    background_crop = background[y : y + h, x : x + w]

    alpha_overlay = overlay_crop[:, :, 3] / 255.0
    alpha_bg = 1.0 - alpha_overlay

    for c in range(0, 3):
        background_crop[:, :, c] = (
            alpha_overlay * overlay_crop[:, :, c] + alpha_bg * background_crop[:, :, c]
        )

    background[y : y + h, x : x + w] = background_crop
    return background

@app.route("/process_ar", methods=["POST"])
def process_ar():
    try:
        with mp_pose.Pose(
            static_image_mode=True,
            model_complexity=0,
            min_detection_confidence=0.5
        ) as pose_instance:
            
            file = request.files['image'].read()
            num_people = int(request.form.get('num_people', 1))
            gender_mode = request.form.get('gender_mode', 'pria')
            baju_theme = request.form.get('baju')

            nparr = np.frombuffer(file, np.uint8)
            frame = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
            h_frame, w_frame, _ = frame.shape
            
            frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
            results = pose_instance.process(frame_rgb)

            if results.pose_landmarks:
                landmarks = results.pose_landmarks.landmark
                ls = landmarks[mp_pose.PoseLandmark.LEFT_SHOULDER]
                rs = landmarks[mp_pose.PoseLandmark.RIGHT_SHOULDER]

                if ls.visibility > 0.5 and rs.visibility > 0.5:
                    ls_x, ls_y = int(ls.x * w_frame), int(ls.y * h_frame)
                    rs_x, rs_y = int(rs.x * w_frame), int(rs.y * h_frame)

                    target_gender = "pria"
                    if num_people == 1:
                        target_gender = gender_mode
                    else:
                        target_gender = gender_mode.split('_')[0]

                    filename = f"{baju_theme}_{target_gender}.png"
                    baju_path = os.path.join(ASSETS_PATH, filename)
                    
                    if not os.path.exists(baju_path):
                        baju_path = os.path.join(ASSETS_PATH, f"{baju_theme}.png")

                    if os.path.exists(baju_path):
                        dy = rs_y - ls_y
                        dx = rs_x - ls_x
                        angle = math.degrees(math.atan2(dy, dx))
                        
                        if angle >= 90: angle -= 180
                        elif angle <= -90: angle += 180
                        final_angle = -angle

                        baju_img = cv2.imread(baju_path, cv2.IMREAD_UNCHANGED)
                        
                        shoulder_width = math.dist([ls_x, ls_y], [rs_x, rs_y])
                        scale_factor = 2.5
                        
                        target_width = int(shoulder_width * scale_factor)
                        aspect_ratio = baju_img.shape[0] / baju_img.shape[1]
                        target_height = int(target_width * aspect_ratio)
                        
                        baju_resized = cv2.resize(baju_img, (target_width, target_height))
                        baju_rotated = rotate_image(baju_resized, final_angle)

                        center_x = (ls_x + rs_x) // 2
                        center_y = (ls_y + rs_y) // 2
                        
                        ol_h, ol_w, _ = baju_rotated.shape
                        final_x = int(center_x - (ol_w / 2))
                        
                        offset_leher = int(ol_h * 0.06) 
                        final_y = center_y - offset_leher

                        frame = overlay_transparent(frame, baju_rotated, final_x, final_y)

            _, buffer = cv2.imencode(".jpg", frame)
            img_str = base64.b64encode(buffer).decode("utf-8")
            return jsonify({"status": "success", "image": img_str})

    except Exception as e:
        print(f"Error: {e}")
        return jsonify({"status": "error", "message": str(e)}), 500

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, threaded=False)
