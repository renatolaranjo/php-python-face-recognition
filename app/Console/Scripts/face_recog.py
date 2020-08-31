import cv2
import numpy as np
import os
import sys
import base64
import json

recognizer = cv2.face.LBPHFaceRecognizer_create()
recognizer.read('../app/Console/Scripts/trainer.yml')
cascadePath = "../app/Console/Scripts/haarcascade_frontalface_default.xml"
faceCascade = cv2.CascadeClassifier(cascadePath)

font = cv2.FONT_HERSHEY_SIMPLEX

img = sys.argv[1]
path = '../storage/app/recon/' + img
image = cv2.imread(path)
gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

faces = faceCascade.detectMultiScale(
    gray,
    scaleFactor=1.2,
    minNeighbors=5,
    minSize=(30, 30)
)

confidence = ''
for (x, y, w, h) in faces:
    cv2.rectangle(image, (x, y), (x+w, y+h), (0, 255, 0), 2)
    id, confidence = recognizer.predict(gray[y:y+h, x:x+w])

if (confidence):
    response={'confidence':100 - confidence,'id':id}
    print(json.JSONEncoder().encode(response))
else:
    response={'confidence':101,'id':'no_face'}
    print(json.JSONEncoder().encode(response))
