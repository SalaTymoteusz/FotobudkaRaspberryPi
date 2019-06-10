# Fotobudka - Photobooth application for Raspberry Pi



import uuid
import json
import requests
import picamera
import itertools
import cups
import subprocess
import os
from shutil import copyfile
import sys
import time
import logging
import RPi.GPIO as GPIO
from time import sleep
from PIL import Image, ImageDraw, ImageFont
#from resizeimage import resizeimage
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
o = None



#setup GPIOs
GPIO.setmode(GPIO.BCM)
GPIO.setup(BUTTON_PIN, GPIO.IN, pull_up_down=GPIO.PUD_UP)


def sendImage(url, code, imgPath, series_code):

    files = {'photo': open("%s" % (imgPath), 'rb')}
    payload = {'code': code, 'series_code': series_code}

    response = requests.request("POST", url, data=payload, files=files)
    print(response.url)
    print(response.text)


#sendImage("https://fotobudkaraspberry.000webhostapp.com/uploadPhoto.php","kod", "/usr/local/src/boothy/1.jpg", "1")

#merges the 4 images
def convertMergeImages(fileName):
    code = uuid.uuid4().hex[:6].upper()
    x = compareCodes("/usr/local/src/boothy/codes.txt",[code])
    logging.info(str(x))
    if x == False:
        logging.info("Code is unique")
    else:
        logging.info("Code is in use")
        while x == True:
            code = uuid.uuid4().hex[:6].upper()
            x = compareCodes("/usr/local/src/boothy/codes.txt",[code])
            logging.info("Generate new code")


    #logging.info(code)
    appendFile("codes.txt", code)
    sendImage("https://fotobudkaraspberry.000webhostapp.com/uploadPhoto.php","kod", "/usr/local/src/boothy/1.jpg", code)
    sendImage("https://fotobudkaraspberry.000webhostapp.com/uploadPhoto.php","kod", "/usr/local/src/boothy/2.jpg", code)
    sendImage("https://fotobudkaraspberry.000webhostapp.com/uploadPhoto.php","kod", "/usr/local/src/boothy/3.jpg", code)



    addPreviewOverlay(250,114,50,"Your code:\n%s \nwww.fotobudka.pl" % (code))
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

def showImage(path, x, y, remove, presentation_time):

 # Load the arbitrarily sized image
    img = Image.open(path)
    # Create an image padded to the required size with
    # mode 'RGB'
    pad = Image.new('RGBA', (
        ((img.size[0] + x) // 32) * 32,
        ((img.size[1] + y) // 16) * 16,
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
    time.sleep(presentation_time)

    if remove == True:
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






def play():

    fileName = time.strftime("%Y%m%d-%H%M%S")+".jpg"



    countdownFrom(PHOTO_DELAY)
    captureImage(IMG1)
    time.sleep(1)


    countdownFrom(PHOTO_DELAY)
    captureImage(IMG2)
    time.sleep(1)

    countdownFrom(PHOTO_DELAY)
    captureImage(IMG3)

    time.sleep(1)
    addPreviewOverlay(150,200,100,"KONIEC")


    presentation()
    time.sleep(1)
    convertMergeImages(fileName)

    showImage('/usr/local/src/boothy/noTextLogo.png', 0, 8, False, 0)





    time.sleep(2)
    #camera.remove_overlay(o)
    archiveImage(fileName)
    deleteImages(fileName)

def presentation():
    resizePhoto('/usr/local/src/boothy/1.jpg')
    showImage('/usr/local/src/boothy/test.jpg', 0, 8, False, 2)
    resizePhoto('/usr/local/src/boothy/2.jpg')
    showImage('/usr/local/src/boothy/test.jpg', 0, 8, False, 2)
    resizePhoto('/usr/local/src/boothy/3.jpg')
    showImage('/usr/local/src/boothy/test.jpg', 0, 8, False, 2)

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

def resizePhoto(path):
    basewidth = 1024
    img = Image.open(path)
    basehight = 600
    img = img.resize((basewidth,basehight), Image.ANTIALIAS)
    img.save('test.jpg')


#start flow
with picamera.PiCamera() as camera:
    os.chdir(CurrentWorkingDir)

    try:
        initLogger(logDir)
        initCamera(camera)


        logging.info("Starting preview")



        camera.start_preview()




        showImage('/usr/local/src/boothy/logo.png', 0, 8, True, 3)



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
