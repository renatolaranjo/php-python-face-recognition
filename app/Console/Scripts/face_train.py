import cv2
import numpy as np
from PIL import Image
import os
import re
import json
path = '../storage/app/faces'
recognizer = cv2.face.LBPHFaceRecognizer_create()
detector = cv2.CascadeClassifier("../app/Console/Scripts/haarcascade_frontalface_default.xml")

def getImagesAndLabels(path):
    imagePaths = [os.path.join(path,f) for f in os.listdir(path)]
    faceSamples=[]
    ids = []

    for imagePath in imagePaths:
        PIL_img = Image.open(imagePath).convert('L') # convert it to grayscale
        img_numpy = np.array(PIL_img,'uint8')
        id = int(re.search('\d+', imagePath)[0])
        faces = detector.detectMultiScale(img_numpy)
        for (x,y,w,h) in faces:
            faceSamples.append(img_numpy[y:y+h,x:x+w])
            ids.append(id)

    return faceSamples,ids

faces,ids = getImagesAndLabels(path)
if (faces):
    recognizer.train(faces, np.array(ids))
    recognizer.write('../app/Console/Scripts/trainer.yml')
    response={'status':'success'}
    print(json.JSONEncoder().encode(response))
else:
    response={'status':'error'}
    print(json.JSONEncoder().encode(response))