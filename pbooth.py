# Fotobudka - Photobooth application for Raspberry Pi
# developed by Tymoteusz Sala



import uuid
import json
import requests
import picamera
import itertools
import cups
import subprocess
from shutil import copyfile
import sys
import time
import logging
import RPi.GPIO as GPIO
from time import sleep
from PIL import Image, ImageDraw, ImageFont, ImageSequence
import os

IMG1             = "1.jpg"
IMG2             = "2.jpg"
IMG3             = "3.jpg"
IMG5         = Image.open("/usr/local/src/boothy/logo.jpg")
CurrentWorkingDir= "/usr/local/src/boothy"
IMG4             = "4logo.png"
logDir           = "logs"
archiveDir       = "photos"
SCREEN_WIDTH     = 1024
SCREEN_HEIGHT    = 600
IMAGE_WIDTH      = 2592
IMAGE_HEIGHT     = 1944
BUTTON_PIN       = 26
PHOTO_DELAY      = 3
overlay_renderer = None
buttonEvent      = False



#setup GPIOs
GPIO.setmode(GPIO.BCM)
GPIO.setup(BUTTON_PIN, GPIO.IN, pull_up_down=GPIO.PUD_UP)

 

#merges the 4 images
def convertMergeImages(fileName):
    code = uuid.uuid4().hex[:6].upper()

    while compareCodes("code.txt", code) == True:
        code = uuid.uuid4().hex[:6].upper()
        appendFile("codes.txt", code)
        return code


    addPreviewOverlay(66,114,55,"merging images...\n Your code: %s" % (code))
    #now merge all the images
    subprocess.call(["montage",
                     IMG1,IMG2,IMG3,IMG4,
                     "-geometry", "+2+2",
                     fileName])
    logging.info("Images have been merged.")

def deleteImages(fileName):
    logging.info("Deleting any old images.")
    if os.path.isfile(IMG1):
        os.remove(IMG1)
    if os.path.isfile(IMG2):
        os.remove(IMG2)
    if os.path.isfile(IMG3):
        os.remove(IMG3)
    if os.path.isfile(fileName):
        os.remove(fileName);

def cleanUp():
    GPIO.cleanup()

def archiveImage(fileName):
    logging.info("Saving off image: "+fileName)
    copyfile(fileName,archiveDir+"/"+fileName)

def countdownFrom(secondsStr):
    secondsNum = int(secondsStr)
    if secondsNum >= 0 :
        while secondsNum > 0 :
            addPreviewOverlay(300,100,240,str(secondsNum))
            time.sleep(1)
            secondsNum=secondsNum-1

def captureImage(imageName):
    addPreviewOverlay(150,200,100,"smile!   :)")
    #save image
    camera.capture(imageName, resize=(IMAGE_WIDTH, IMAGE_HEIGHT))
    logging.info("Image "+imageName+" captured.")
    
def showLogo():
 
 # Load the arbitrarily sized image
    img = Image.open('/usr/local/src/boothy/logo.png')
    # Create an image padded to the required size with
    # mode 'RGB'
    pad = Image.new('RGBA', (
        ((img.size[0] + 0) // 32) * 32,
        ((img.size[1] + 8) // 16) * 16,
        ))
    # Paste the original image into the padded one
    pad.paste(img, (0, 0))

    # Add the overlay with the padded image as the source,
    # but the original image's dimensions

    o = camera.add_overlay(pad.tobytes(), size=img.size)
    # By default, the overlay is in layer 0, beneath the
    # preview (which defaults to layer 2). Here we make
    # the new overlay semi-transparent, then move it above
    # the preview
    o.alpha = 128
    o.layer = 3
 
        
    time.sleep(3)
    camera.remove_overlay(o)
    
   
               

def addPreviewOverlay(xcoord,ycoord,fontSize,overlayText):
    global overlay_renderer
  
    img = Image.new("RGBA", (640, 480))
    draw = ImageDraw.Draw(img)
    draw.font = ImageFont.truetype(
                    "/usr/share/fonts/truetype/freefont/FreeSerif.ttf",fontSize)
    draw.text((xcoord,ycoord), overlayText, (200,116,0))
 
    
    
    if not overlay_renderer:
        # Note: The call to add_overlay has changed since picamera v.1.10.
        # If you have a new version of picamera, then please change the
        # first parameter to:  img.tobytes()
        #
        overlay_renderer = camera.add_overlay(img.tobytes(),
                                              layer=3,
                                              size=img.size)
    else:
        overlay_renderer.update(img.tobytes())
        
def sendImg():
 #  f = open("/usr/local/src/boothy/11.jpg", 'rb')

   # imgg = open("/usr/local/src/boothy/12.jpg")
  #  url = "https://fotobudka.projektstudencki.pl/uploadPhoto.php"
  #  files = {'photo': imgg}
   # payload = {'code':'afdf'}
# just set files to a list of tuples of (form_field_name, file_info)
    
    #files = {'photo': open("/usr/local/src/boothy/11.jpg",'rb')}
    #text_data = {"code":"gk422"}
   # headers = {"Host": "fotobudka.projektstudencki.pl", 'Accept': '*/*'}
  #  r = requests.post(url, data=payload, files=files, headers=headers)
  #  print(r.text)
  
    url = "https://fotobudka.projektstudencki.pl/uploadPhoto.php"

    payload = {'code':'sfs1'}
    files = {'photo': open("/usr/local/src/boothy/11.jpg",'rb')}
    headers = {
        'content-type': "multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
        'Content-Type': "application/x-www-form-urlencoded",
        'User-Agent': "PostmanRuntime/7.13.0",
        'Accept': "*/*",
        'Cache-Control': "no-cache",
        'Postman-Token': "f87d182c-1c5b-4135-9048-0207d29ca2ef,3cd5dd2b-faf2-4e11-b5f5-e359c2156383",
        'Host': "fotobudka.projektstudencki.pl",
        'accept-encoding': "gzip, deflate",
        'content-length': "449924",
        'Connection': "keep-alive",
        'cache-control': "no-cache"
        }

    response = requests.request("POST", url, data=payload, files= files, headers=headers)

    print(response.text)


def appendFile(filename, code):
    space = " "
    code = space + code
    appendFile = open(filename, 'a')
    appendFile.write(code)
    appendFile.close()



def compareCodes(filename, codelist):

    file = open(filename, "r")
    read = file.readlines()
    file.close()
    for word in codelist:
        lower = word.lower()
        count = 0
        for sentance in read:
            line = sentance.split()
            for each in line:
                line2 = each.lower()
                line2 = line2.strip("!@#$%^&*(()_+=")
                if lower == line2:
                    count += 1
        if count >= 1:
            return True
        else:
            return False



#compareCodes("codes.txt", ["ABBB14"])


   

    

def play():

    fileName = time.strftime("%Y%m%d-%H%M%S")+".jpg"

    
    
    countdownFrom(PHOTO_DELAY)
    captureImage(IMG1)
    time.sleep(1)
    #sendImg()

    countdownFrom(PHOTO_DELAY)
    captureImage(IMG2)
    time.sleep(1)

    countdownFrom(PHOTO_DELAY)
    captureImage(IMG3)
    time.sleep(1)


    convertMergeImages(fileName)
    time.sleep(1)

    time.sleep(15)

    archiveImage(fileName)
    deleteImages(fileName)

def initCamera(camera):
    logging.info("Initializing camera.")
    #camera settings
    camera.resolution            = (1024, 600)
    camera.framerate             = 30
    camera.sharpness             = 0
    camera.contrast              = 0
    camera.brightness            = 50
    camera.saturation            = 0
    camera.ISO                   = 0
    camera.video_stabilization   = False
    camera.exposure_compensation = 0
    camera.exposure_mode         = 'auto'
    camera.meter_mode            = 'average'
    camera.awb_mode              = 'auto'
    camera.image_effect          = 'none'
    camera.color_effects         = None
    camera.rotation              = 0
    camera.hflip                 = False
    camera.vflip                 = True
    camera.crop                  = (0.0, 0.0, 1.0, 1.0)

def initLogger(output_dir):
    logger = logging.getLogger()
    logger.setLevel(logging.DEBUG)
    # create console handler and set level to info
    handler = logging.StreamHandler()
    handler.setLevel(logging.INFO)
    formatter = logging.Formatter("%(asctime)s - %(levelname)s - %(message)s")
    handler.setFormatter(formatter)
    logger.addHandler(handler)
    # create error file handler and set level to error
    handler = logging.FileHandler(output_dir+"/"+time.strftime("%Y%m%d")+"_error.log","w", encoding=None, delay="true")
    handler.setLevel(logging.ERROR)
    formatter = logging.Formatter("%(asctime)s - %(levelname)s - %(message)s")
    handler.setFormatter(formatter)
    logger.addHandler(handler)
    # create debug file handler and set level to debug
    handler = logging.FileHandler(output_dir+"/"+time.strftime("%Y%m%d")+"_debug.log","w", encoding=None, delay="true")
    handler.setLevel(logging.DEBUG)
    formatter = logging.Formatter("%(asctime)s - %(levelname)s - %(message)s")
    handler.setFormatter(formatter)
    logger.addHandler(handler)

def onButtonPress():
    logging.info("Big red button pressed!")
    play()
    #reset the initial welcome message
    addPreviewOverlay(20,200,55,"Press red button to begin!")
    

    


def onButtonDePress():
    logging.info("Big red button de-pressed!")


#start flow
with picamera.PiCamera() as camera:
    os.chdir(CurrentWorkingDir)

    try:
        initLogger(logDir)
        initCamera(camera)
       
        
        logging.info("Starting preview")
        

        
        camera.start_preview()
        showLogo()
        
    
    
        addPreviewOverlay(20,200,55,"Press red button to begin!")
    
        logging.info("Starting application loop")
        while True:
            input_state = GPIO.input(BUTTON_PIN)
            if input_state == False :
                if buttonEvent == False :
                    buttonEvent = True
                    onButtonPress()
            else :
                if buttonEvent == True :
                    buttonEvent = False
                    onButtonDePress()
    except BaseException:
        logging.error("Unhandled exception : " , exc_info=True)
        camera.close()
        cleanUp()
    finally:
        logging.info("quitting...")
        cleanUp()
        camera.close()
    

#end
