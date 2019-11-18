# Fotobudka - Photobooth application for Raspberry Pi



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
from PIL import Image, ImageDraw, ImageFont
#from resizeimage import resizeimage
import os
import shutil
import png
import pyqrcode
import urllib2
import os.path

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
CODE = ""



#setup GPIOs
GPIO.setmode(GPIO.BCM)
GPIO.setup(BUTTON_PIN, GPIO.IN, pull_up_down=GPIO.PUD_UP)

def internet_on():
    try:
        urllib2.urlopen('http://216.58.192.142', timeout=1)
        return True
    except urllib2.URLError as err:
        return False


def sendImage(url, imgPath, series_code):

    files = {'photo': open("%s" % (imgPath), 'rb')}
    payload = {'series_code': series_code}

    response = requests.request("POST", url, data=payload, files=files)
    print(response.url)
    print(response.text)



def codeGenerate():
    code = uuid.uuid4().hex[:6].upper()
    x = compareCodes("/usr/local/src/boothy/codes.txt", [code])
    logging.info(str(x))
    if x == False:
        logging.info("Code is unique")
    else:
        logging.info("Code is in use")
        while x == True:
            code = uuid.uuid4().hex[:6].upper()
            x = compareCodes("/usr/local/src/boothy/codes.txt", [code])
            logging.info("Generate new code")
    #logging.info(code)
    
    if internet_on() == True:
        appendFile("codes.txt", code)
        print("Added code to codes.txt")
    else:
        print("Added code to code.txt")
        appendFile("code.txt", code)
    return code

#merges the 4 images
def convertMergeImages(fileName, code):
    showImage2('/usr/local/src/boothy/noTextLogo.png', 0, 8, True, fileName, code)

    # addPreviewOverlay(250,114,50,"Your code:\n%s \nwww.fotobudka.pl" % (code))

    #now merge all the images

def showQrcode():
 # Load the arbitrarily sized image
    img = Image.open('/usr/local/src/boothy/code.png')
    # Create an image padded to the required size with
    # mode 'RGB'
    pad = Image.new('RGBA', (
        ((img.size[0] + 24) // 32) * 32,
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
    o.alpha = 255
    o.alpha = 255
    o.layer = 3
    time.sleep(5)

    camera.remove_overlay(o)

def generateQRCode(code):
    big_code = pyqrcode.create(code, error='L', version=None, mode='binary')
    big_code.png('code.png', scale=8, module_color=[0, 0, 0, 128], background=[0xff, 0xff, 0xcc])


def deleteImages():
    logging.info("Deleting any old images.")
    if os.path.isfile(IMG1):
        os.remove(IMG1)
    if os.path.isfile(IMG2):
        os.remove(IMG2)
    if os.path.isfile(IMG3):
        os.remove(IMG3)
    if os.path.isfile("1.jpg"):
        os.remove("1r.jpg")
    if os.path.isfile("2.jpg"):
        os.remove("2r.jpg")
    if os.path.isfile("3.jpg"):
        os.remove("3r.jpg")
    if os.path.isfile("4.jpg"):
        os.remove("4r.jpg")

def cleanUp():
    GPIO.cleanup()

def archiveImage(catalogName, path):
    logging.info("Saving off image: " + catalogName)

    if not os.path.exists(path + catalogName):
        os.makedirs(path + catalogName)
        shutil.move("1.jpg", path + catalogName)
        shutil.move("2.jpg", path + catalogName)
        shutil.move("3.jpg", path + catalogName)
        shutil.move("4.jpg", path + catalogName)

    else:
        shutil.move("1.jpg", path + catalogName)
        shutil.move("2.jpg", path + catalogName)
        shutil.move("3.jpg", path + catalogName)
        shutil.move("4.jpg", path + catalogName)



    logging.info("copy image: " + catalogName)


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
    o.alpha = 255
    o.alpha = 255
    o.layer = 3
    time.sleep(presentation_time)

    if remove == True:
        camera.remove_overlay(o)




def showImage2(path, x, y, remove, fileName, code):

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
    o.alpha = 255
    o.layer = 3



    addPreviewOverlay(250, 114, 50, "Your code:\n%s \nwww.fotobudka.pl" % (code))
    
    #The assembly process of three photos and delay for showing code
    montage()
    
    if remove == True:
          img = Image.new("RGBA", (640, 480))
          overlay_renderer.update(img.tobytes())
          camera.remove_overlay(o)


def addPreviewOverlay2(xcoord,ycoord,fontSize,overlayText):
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

    time.sleep(2)

    img = Image.new("RGBA", (640, 480))
    overlay_renderer.update(img.tobytes())

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


def remove_overlays(camera):
    # Remove all overlays from the camera preview
    for o in camera.overlays:
        camera.remove_overlay(o)


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
        print("Ten sam kod")
        return True
    else:
        print("Wygenerowano nowy kod")
        return False
        
def generateListOfCodes(fileName):
    listOfCodes = []
    file = open(fileName, "r")
    read = file.readlines()
    file.close()
    for sentance in read:
        line = sentance.split()
        for each in line:
            line2 = each.lower()
            line2 = line2.strip("!@#$%^&*(()_+=")
            listOfCodes.append(line2)
            print(listOfCodes[-1])
            

def montage():
    subprocess.call(["montage",
                 IMG1, IMG2, IMG3,
                 "-geometry", "+2+1",
                 "4.jpg"])
    logging.info("Images have been merged.")




def play():
    generateListOfCodes("code.txt")
    catalogName = time.strftime("%Y%m%d-%H%M%S")

    fileName = catalogName +".jpg"
    code = codeGenerate()


    countdownFrom(PHOTO_DELAY)
    captureImage(IMG1)
    time.sleep(1)


    countdownFrom(PHOTO_DELAY)
    captureImage(IMG2)
    time.sleep(1)

    countdownFrom(PHOTO_DELAY)
    captureImage(IMG3)

    addPreviewOverlay2(150,200,100,"KONIEC")

    #presentation()
    #present QRCode
    generateQRCode(code)
    showQrcode()
    os.remove('code.png')
    convertMergeImages(fileName, code)
#    send(fileName, code)
#    archiveImage(fileName, catalogName, "photos/")
#    deleteImages(fileName)
    
    if internet_on() == True:
        print("Internet ON")
        send(catalogName, code)
        archiveImage(catalogName, "photos/")
        deleteImages()
    else:
        print("Internet OFF")
        destination = '/usr/local/src/boothy/toSend/'
        archiveImage(code, destination)
        deleteImages()

#def saveToFile(code):
#    path = "/usr/local/src/boothy/code.txt"
#    print(path)
#    file = open(path, 'w')
#    file.write(code)
#    file.close()

def send(catalogName, code):
    sendImage("https://fotobudka.projektstudencki.pl/uploadPhoto.php", "/usr/local/src/boothy/1.jpg", code)
    sendImage("https://fotobudka.projektstudencki.pl/uploadPhoto.php", "/usr/local/src/boothy/2.jpg", code)
    sendImage("https://fotobudka.projektstudencki.pl/uploadPhoto.php", "/usr/local/src/boothy/3.jpg", code)
    sendImage("https://fotobudka.projektstudencki.pl/uploadPhoto.php", "/usr/local/src/boothy/4.jpg", code)
    


def presentation():

    resizePhoto('/usr/local/src/boothy/1.jpg', '1r')
    resizePhoto('/usr/local/src/boothy/2.jpg', '2r')
    resizePhoto('/usr/local/src/boothy/3.jpg', '3r')

    showImage('/usr/local/src/boothy/1r.jpg', 0, 8, True, 3)
    showImage('/usr/local/src/boothy/2r.jpg', 0, 8, True, 3)
    showImage('/usr/local/src/boothy/3r.jpg', 0, 8, True, 3)


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

    addPreviewOverlay(20,200,55,"Press red button to begin!!")





def onButtonDePress():
    logging.info("Big red button de-pressed!")

def resizePhoto(path, name):
    basewidth = 1024
    img = Image.open(path)
    basehight = 600
    img = img.resize((basewidth,basehight), Image.ANTIALIAS)
    img.save('%s.jpg' % (name))
    img.close()


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
