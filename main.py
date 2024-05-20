# import cv2
# import easyocr
# import pandas as pd
# import numpy as np
# import pytesseract
# from PIL import Image
# import imutils
# import matplotlib.pyplot as plt
# pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR/tesseract.exe"
# image = cv2.imread("car-ind-number-plate.png")
#
#
# gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
# gray = cv2.bilateralFilter(gray, 11,17,17)
# plt.imshow(cv2.cvtColor(gray, cv2.COLOR_BGR2RGB))
# plt.show()
#
#
# edge = cv2.Canny(gray, 170,200)
# cnts, new = cv2.findContours(edge.copy(), cv2.RETR_LIST, cv2.CHAIN_APPROX_SIMPLE)
# image1 = image.copy()
# cv2.drawContours(image1,cnts,-1,(0,225,0),3)
# cnts =sorted(cnts, key=cv2.contourArea, reverse=True)[:30]
# NumberPlateCount = None
# image2 = image.copy()
# cv2.drawContours(image2,cnts,-1,(0,255,0),3)
# count = 0
# name = 1
# for i in cnts:
#     perimeter = cv2.arcLength(i, True)
#     approx = cv2.approxPolyDP(i,0.02*perimeter,True)
#     if(len(approx)==4):
#         NumberPlateCount = approx
#         x,y,w,h = cv2.boundingRect(i)
#         crp_img = image[y:y+h, x:x+w]
#         cv2.imwrite(str(name)+ '.png', crp_img)
#         name +=1
#         break
#
# crp_img_loc = '1.png'
#
# image = cv2.imread(crp_img_loc)
# plt.imshow(cv2.cvtColor(image, cv2.COLOR_BGR2RGB))
# plt.show()
#
#
#
#
# reader = easyocr.Reader(['en'])
#
# # Load the image
# image_path = '1.png'
# result = reader.readtext(image_path)
#
#
# # this is used to detect the text
# for detection in result:
#    print(detection[1])  # Index 1 contains the recognized text






#saving the output to a csv file
#detected_license_plates = [detection[1] for detection in result]

# Create a DataFrame to store the data
#df = pd.DataFrame({'': detected_license_plates})

# Save DataFrame to CSV file
#df.to_csv('detected_license_plates.csv', index=False, mode = 'w')



#this is for the whole part where i send the data to the firebase real time databse
import cv2
import easyocr
import numpy as np
import matplotlib.pyplot as plt
import firebase_admin
from firebase_admin import credentials, db
import os

# Firebase setup
cred = credentials.Certificate("anpr-01-firebase-adminsdk-zg7if-d000a3e832.json")
firebase_admin.initialize_app(cred, {
    'databaseURL': 'https://anpr-01-default-rtdb.firebaseio.com/'
})

# Load the image
image = cv2.imread("img_anpr/Cars14.png")

# Preprocess the image
gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
gray = cv2.bilateralFilter(gray, 11, 17, 17)

# Detect edges
edge = cv2.Canny(gray, 170, 200)
cnts, _ = cv2.findContours(edge.copy(), cv2.RETR_LIST, cv2.CHAIN_APPROX_SIMPLE)
cnts = sorted(cnts, key=cv2.contourArea, reverse=True)[:30]

NumberPlateCount = None
for i in cnts:
    perimeter = cv2.arcLength(i, True)
    approx = cv2.approxPolyDP(i, 0.02 * perimeter, True)
    if len(approx) == 4:
        NumberPlateCount = approx
        x, y, w, h = cv2.boundingRect(i)
        crp_img = image[y:y+h, x:x+w]
        cv2.imwrite('1.png', crp_img)
        break

# Load cropped image
image_path = '1.png'
image = cv2.imread(image_path)

# Initialize EasyOCR reader
reader = easyocr.Reader(['en'])

# Detect text
result = reader.readtext(image_path)

# Extract detected license plate number
detected_license_plates = [detection[1] for detection in result]


def send_to_firebase(license_plates):
    ref = db.reference('license_plates')
    for index, plate in enumerate(license_plates):
        # Use 'lp' followed by the index to create a unique but identifiable key(which i want to fetch)
        ref.child(f'lp_{index}').set({'plate_number': plate})




# # Function to send data to Firebase
# def send_to_firebase(license_plates):
#     ref = db.reference('license_plates')
#     for plate in license_plates:
#         ref.push({'plate_number': plate}) #this creates a unique key everytime i push




# Send detected license plates to Firebase
send_to_firebase(detected_license_plates)

# Show the final image with detected plate 
plt.imshow(cv2.cvtColor(image, cv2.COLOR_BGR2RGB))
plt.show()

for detection in result:
    print(detection[1])

# Clean up the saved image file
os.remove('1.png')
