

from ultralytics import YOLO
from dotenv import load_dotenv
import os
load_dotenv()

# Retrieve base_path from environment variable
base_path = '/srv/http/renter/storage/model'

def yolo_prediction(img, file=f"{base_path}/detect/train2/weights/best.pt"):
    # model = YOLO('detect\train2\weights\best.pt')
    # results = model.predict(source=img,save_crop=True,device='cpu')
    model = YOLO(file)
    model.predict(source=img, save_crop=True, device="cpu", project=f"{base_path}/runs/detect")



import os
import shutil

def delete_yolo_prediction():
    shutil.rmtree(f"{base_path}/runs/detect/predict")


import os

def get_images_in_predict_folder(predict_folder_path):
    images_dict = {}
    # print(predict_folder_path)
    # print(predict_folder_path)
    # print(predict_folder_path)
#     if not os.path.exists(predict_folder_path):
#         os.makedirs(predict_folder_path)

    # List all subdirectories in the predict folder
    for subdir in os.listdir(predict_folder_path):
        subdir_path = os.path.join(predict_folder_path, subdir)
        # print('heres')
        # Check if it's a directory
        if os.path.isdir(subdir_path):
            # List all files in the subdirectory
            files = os.listdir(subdir_path)

            # Filter out non-image files
            images = [file for file in files if file.lower().endswith(('.png', '.jpg', '.jpeg'))]

            # Add to the dictionary
            images_dict[subdir] = images

    return images_dict

import easyocr
def predict_imgs(images_dict):
    reader = easyocr.Reader(['ar'])
    # reader = easyocr.Reader(['en','ar'])

    results = {}

    for folder, images in images_dict.items():
        # print(folder, images)
        folder_results = []
        for image in images:
            image_path = os.path.join(fr"{base_path}/runs/detect/predict/crops/{folder}",image)
            # print(image_path)
            # print('--'*40)
            result = reader.readtext(image_path)
            folder_results.append(result)

        results[folder] = folder_results
    # x= get_images_in_predict_folder('detect\predict\crops')
    return results


# x=predict_imgs(x)

# # print(x)

def get_img(img):
    yolo_prediction(img)
    x= get_images_in_predict_folder(f'{base_path}/runs/detect/predict/crops')
    x=predict_imgs(x)
    delete_yolo_prediction()
    return x
